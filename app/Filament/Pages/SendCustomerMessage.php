<?php

namespace App\Filament\Pages;

use App\Models\Customer;
use App\Models\SmsConfiguration;
use App\Services\SmsManager;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Radio;
use Illuminate\Support\Facades\Log;

class SendCustomerMessage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static string $view = 'filament.pages.send-customer-message';

    protected static ?string $title = 'Send Customer Message';

    protected static ?string $navigationLabel = 'Send Message';

    protected static ?string $navigationGroup = 'Communications';

    protected static ?int $navigationSort = 10;

    protected float $perMessageCost = 0.35003;

    protected int $perMessageCharacters = 160;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Message Details')
                    ->description('Send custom SMS messages to one or multiple customers')
                    ->schema([
                        Toggle::make('select_all')
                            ->label('Send to All Customers')
                            ->helperText('Enable this to send message to all customers with phone numbers')
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    // Select all customers with phone numbers
                                    $allCustomerIds = Customer::whereNotNull('phone_number')
                                        ->pluck('id')
                                        ->toArray();
                                    $set('customers', $allCustomerIds);
                                } else {
                                    $set('customers', []);
                                }
                            }),

                        Select::make('customers')
                            ->label('Select Customers')
                            ->options(function () {
                                return Customer::whereNotNull('phone_number')
                                    ->get()
                                    ->mapWithKeys(function ($customer) {
                                        $label = $customer->full_name;
                                        if ($customer->phone_number) {
                                            $label .= ' (' . $customer->phone_number . ')';
                                        }
                                        return [$customer->id => $label];
                                    });
                            })
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder('Select one or more customers')
                            ->reactive()
                            ->disabled(fn(callable $get) => $get('select_all'))
                            ->helperText(function ($state, callable $get) {
                                $count = is_array($state) ? count($state) : 0;
                                $totalCustomers = Customer::whereNotNull('phone_number')->count();

                                if ($get('select_all')) {
                                    return "All {$totalCustomers} customers with phone numbers will receive the message";
                                }

                                if ($count === 0) {
                                    return "You can select multiple customers to send the same message (Total available: {$totalCustomers})";
                                }
                                return "Selected: {$count} customer(s) out of {$totalCustomers} available";
                            }),

                        Textarea::make('message')
                            ->label('Message')
                            ->required()
                            ->rows(5)
                            ->maxLength(500)
                            ->reactive()
                            ->helperText(function ($state, callable $get) {
                                $length = strlen($state ?? '');
                                $remaining = 500 - $length;
                                $smsCount = $length > 0 ? ceil($length / $this->perMessageCharacters) : 0;
                                $costPerRecipient = $smsCount * $this->perMessageCost;

                                // Get customer count based on selection mode
                                if ($get('select_all')) {
                                    $customerCount = Customer::whereNotNull('phone_number')->count();
                                } else {
                                    $customers = $get('customers');
                                    $customerCount = is_array($customers) ? count($customers) : 0;
                                }

                                $totalCost = $costPerRecipient * $customerCount;

                                if ($length === 0) {
                                    return "Characters: 0/500 | SMS Parts: 0 | Cost: ৳0.00";
                                }

                                $helperText = "Characters: {$length}/500 (Remaining: {$remaining}) | SMS Parts: {$smsCount} | Cost per recipient: ৳" . number_format($costPerRecipient, 2);

                                if ($customerCount > 0) {
                                    $prefix = $get('select_all') ? 'ALL' : '';
                                    $helperText .= " | Total cost for {$prefix} {$customerCount} recipient(s): ৳" . number_format($totalCost, 2);
                                }

                                return $helperText;
                            })
                            ->placeholder('Type your message here...')
                            ->columnSpanFull(),
                    ])
                    ->columns(1),
            ])
            ->statePath('data');
    }

    public function send(): void
    {
        $data = $this->form->getState();

        $message = $data['message'];
        $selectAll = $data['select_all'] ?? false;

        // Handle customer selection based on select_all toggle
        if ($selectAll) {
            // Get all customers with phone numbers when select_all is true
            $customerIds = [];
        } else {
            // Get selected customers
            $customerIds = $data['customers'] ?? [];
            if (empty($customerIds)) {
                Notification::make()
                    ->title('No Customers Selected')
                    ->body('Please select at least one customer or enable "Send to All Customers".')
                    ->danger()
                    ->send();
                return;
            }
        }

        try {
            // Use the unified SmsManager
            $smsManager = new SmsManager();
            
            // Send bulk promotional messages (handles both providers internally)
            $result = $smsManager->sendBulkPromotional($message, $customerIds);

            if ($result['success']) {
                $successMessage = "Successfully sent messages to {$result['sent_count']} customer(s).";
                if (isset($result['failed_count']) && $result['failed_count'] > 0) {
                    $successMessage .= " Failed: {$result['failed_count']}";
                }
                if (isset($result['total_groups']) && $result['total_groups'] > 1) {
                    $successMessage .= " (Sent in {$result['total_groups']} batch(es))";
                }

                Notification::make()
                    ->title('Messages Sent Successfully')
                    ->body($successMessage)
                    ->success()
                    ->send();

                Log::info('Bulk SMS sent via SmsManager', [
                    'provider' => $smsManager->getActiveProvider(),
                    'recipients_count' => $result['sent_count'],
                    'failed_count' => $result['failed_count'] ?? 0,
                    'message' => $message,
                    'sent_by' => auth()->user()->id ?? null,
                ]);
            } else {
                Notification::make()
                    ->title('Failed to Send Messages')
                    ->body($result['error'] ?? 'Could not send messages to customers.')
                    ->danger()
                    ->send();
            }
        } catch (\Exception $e) {
            Log::error('Failed to send bulk SMS', [
                'error' => $e->getMessage()
            ]);

            Notification::make()
                ->title('Error Sending Messages')
                ->body('An error occurred while sending messages: ' . $e->getMessage())
                ->danger()
                ->send();
        }

        // Reset form
        $this->form->fill();
    }
}
