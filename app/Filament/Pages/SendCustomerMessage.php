<?php

namespace App\Filament\Pages;

use App\Models\Customer;
use App\Models\SmsConfiguration;
use App\Services\SmsService;
use App\Services\BulkSMSBDService;
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

        // Get the active provider from database configuration
        $smsConfig = SmsConfiguration::first();
        $provider = $smsConfig?->active_provider ?? 'smsq';

        $selectAll = $data['select_all'] ?? false;

        // Handle customer selection based on select_all toggle
        if ($selectAll) {
            // Get all customers with phone numbers when select_all is true
            $customers = Customer::whereNotNull('phone_number')->get();
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
            $customers = Customer::whereIn('id', $customerIds)
                ->whereNotNull('phone_number')
                ->get();
        }

        if ($customers->isEmpty()) {
            Notification::make()
                ->title('No Valid Recipients')
                ->body('Selected customers do not have phone numbers.')
                ->danger()
                ->send();
            return;
        }

        try {
            $result = [];

            if ($provider === 'bulksmsbd') {
                // Use BulkSMSBD service
                $smsService = new BulkSMSBDService();

                // Prepare recipients array for BulkSMSBD
                $recipients = $customers->map(function ($customer) use ($message) {
                    return [
                        'phone' => $customer->phone_number,
                        'message' => str_replace(
                            ['{name}', '{first_name}'],
                            [$customer->full_name, explode(' ', $customer->full_name)[0]],
                            $message
                        )
                    ];
                })->toArray();

                $result = $smsService->sendBulkSMS($recipients);

                if ($result['success'] > 0) {
                    Notification::make()
                        ->title('Messages Sent Successfully')
                        ->body("Successfully sent {$result['success']} message(s). Failed: {$result['failed']}")
                        ->success()
                        ->send();

                    Log::info('BulkSMSBD SMS sent', [
                        'provider' => 'bulksmsbd',
                        'success' => $result['success'],
                        'failed' => $result['failed'],
                        'message' => $message,
                        'sent_by' => auth()->user()->id ?? null,
                    ]);
                } else {
                    Notification::make()
                        ->title('Failed to Send Messages')
                        ->body("All messages failed to send. Please check your BulkSMSBD credentials and balance.")
                        ->danger()
                        ->send();
                }
            } else {
                // Use SMSQ service with direct bulk sending method
                $smsService = new SmsService();

                // Check if message contains placeholders for personalization
                $hasPlaceholders = strpos($message, '{name}') !== false || strpos($message, '{first_name}') !== false;

                if ($hasPlaceholders) {
                    // Message has placeholders - personalize for each customer and group by unique messages
                    $messageGroups = [];

                    foreach ($customers as $customer) {
                        $personalizedMessage = str_replace(
                            ['{name}', '{first_name}'],
                            [$customer->full_name, explode(' ', $customer->full_name)[0]],
                            $message
                        );

                        // Group customers by identical messages
                        $messageHash = md5($personalizedMessage);
                        if (!isset($messageGroups[$messageHash])) {
                            $messageGroups[$messageHash] = [
                                'message' => $personalizedMessage,
                                'phones' => []
                            ];
                        }
                        $messageGroups[$messageHash]['phones'][] = $smsService->formatPhoneNumber($customer->phone_number ?? $customer->customer_mobile);
                    }

                    // Send each message group using sendBulkSameMessage
                    $totalSuccess = 0;
                    $totalFailed = 0;
                    $errors = [];

                    foreach ($messageGroups as $group) {
                        // Call sendBulkSameMessage directly (now public)
                        $groupResult = $smsService->sendBulkSameMessage($group['phones'], $group['message']);

                        if ($groupResult['success']) {
                            $totalSuccess += $groupResult['sent_count'];
                        } else {
                            $totalFailed += count($group['phones']);
                            $errors[] = $groupResult['error'] ?? 'Unknown error';
                        }

                        // Small delay between API calls
                        usleep(100000); // 100ms
                    }

                    $result = [
                        'success' => $totalSuccess > 0,
                        'sent_count' => $totalSuccess,
                        'failed_count' => $totalFailed,
                        'total_groups' => count($messageGroups),
                        'error' => !empty($errors) ? implode('; ', array_unique($errors)) : null
                    ];
                } else {
                    // No placeholders - collect all phone numbers and send in one API call
                    $phoneNumbers = [];
                    foreach ($customers as $customer) {
                        $phoneNumbers[] = $smsService->formatPhoneNumber($customer->phone_number ?? $customer->customer_mobile);
                    }

                    // Call sendBulkSameMessage directly (now public)
                    $result = $smsService->sendBulkSameMessage($phoneNumbers, $message);
                }

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

                    Log::info('SMSQ SMS sent', [
                        'provider' => 'smsq',
                        'recipients_count' => $result['sent_count'],
                        'failed_count' => $result['failed_count'] ?? 0,
                        'message' => $message,
                        'has_placeholders' => $hasPlaceholders,
                        'total_groups' => $result['total_groups'] ?? 1,
                        'sent_by' => auth()->user()->id ?? null,
                    ]);
                } else {
                    Notification::make()
                        ->title('Failed to Send Messages')
                        ->body($result['error'] ?? 'Could not send messages to customers.')
                        ->danger()
                        ->send();
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to send bulk SMS', [
                'provider' => $provider,
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
