<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Services\SmsManager;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Livewire\Attributes\On;
use Filament\Notifications\Notification;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('generateOrderMessage')
                ->label('Generate Order Message')
                ->icon('heroicon-o-envelope')
                ->color('info')
                ->modalWidth('4xl')
                ->form([
                    \Filament\Forms\Components\Textarea::make('message')
                        ->label('Order Message')
                        ->rows(20)
                        ->default(fn() => $this->generateOrderMessage($this->record))
                        ->extraAttributes([
                            'style' => 'font-family: monospace; font-size: 14px;',
                            'onclick' => 'this.select();',
                        ])
                        ->helperText('Click on the text area to select all text, then copy (Ctrl+C or Cmd+C). You can also edit the message before sending.')
                ])
                ->modalHeading('Order Message for ' . ($this->record?->order_tracking_id ?? 'Order'))
                ->modalDescription('The order message has been generated. You can edit and send it via SMS.')
                ->modalSubmitActionLabel('Done')
                ->modalCancelAction(false)
                ->extraModalFooterActions(fn(Actions\Action $action): array => [
                    Actions\Action::make('copyMessage')
                        ->label('Copy Message')
                        ->icon('heroicon-o-clipboard-document')
                        ->color('gray')
                        ->action(function () use ($action) {
                            // Get the current form data from the parent action
                            $data = $action->getLivewire()->mountedActionsData[0] ?? [];
                            $message = $data['message'] ?? $this->generateOrderMessage($this->record);
                            
                            // Use Alpine.js to copy to clipboard
                            $this->dispatch('copy-to-clipboard', text: $message);
                            
                            Notification::make()
                                ->title('Message Copied')
                                ->body('Order message has been copied to clipboard')
                                ->success()
                                ->duration(2000)
                                ->send();
                        }),
                    Actions\Action::make('sendSms')
                        ->label('Send SMS to Customer')
                        ->icon('heroicon-o-device-phone-mobile')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Send SMS to Customer')
                        ->modalDescription('Send this order message to ' . $this->record->customer_name . ' at ' . $this->record->customer_mobile . '?')
                        ->modalSubmitActionLabel('Send SMS')
                        ->action(function () use ($action) {
                            // Get the current form data from the parent action
                            $data = $action->getLivewire()->mountedActionsData[0] ?? [];
                            $message = $data['message'] ?? $this->generateOrderMessage($this->record);

                            $smsManager = new SmsManager();
                            $success = $smsManager->sendSms(
                                $this->record->customer_mobile,
                                $message
                            );

                            if ($success) {
                                Notification::make()
                                    ->title('SMS Sent Successfully')
                                    ->body('Order message has been sent to ' . $this->record->customer_mobile)
                                    ->success()
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('SMS Failed')
                                    ->body('Failed to send SMS to customer. Please check SMS configuration.')
                                    ->danger()
                                    ->send();
                            }
                        }),
                ]),

            Actions\DeleteAction::make(),
        ];
    }

    protected function generateOrderMessage($order): string
    {
        $message = "Order Details\n";
        $message .= "================\n";
        $message .= "Order ID: {$order->order_tracking_id}\n";
        $message .= "Customer: {$order->customer_name}\n";
        $message .= "Phone: {$order->customer_mobile}\n";
        $message .= "Address: {$order->address}, {$order->city}";

        if ($order->upazila) {
            $message .= ", {$order->upazila}";
        }
        if ($order->thana) {
            $message .= ", {$order->thana}";
        }
        if ($order->post_code) {
            $message .= " - {$order->post_code}";
        }

        $message .= "\n\nProducts:\n";
        $message .= "----------\n";

        $totalQuantity = 0;
        foreach ($order->orderedProducts as $index => $orderedProduct) {
            $itemNo = $index + 1;
            $message .= "{$itemNo}. {$orderedProduct->product_name}\n";
            $message .= "   Quantity: {$orderedProduct->quantity}\n";

            if ($orderedProduct->selected_size) {
                $message .= "   Size: {$orderedProduct->selected_size}\n";
            }

            if ($orderedProduct->selected_color_code) {
                $message .= "   Color: {$orderedProduct->selected_color_code}\n";
            }

            $message .= "   Price: BDT {$orderedProduct->product_total_price}\n\n";
            $totalQuantity += $orderedProduct->quantity;
        }

        $message .= "Summary:\n";
        $message .= "----------\n";
        $message .= "Total Items: {$totalQuantity}\n";
        $message .= "Shipping: BDT " . ($order->shipping_cost + $order->extra_shipping_cost) . "\n";
        $message .= "Total Amount: BDT {$order->total_price}\n";
        $message .= "Status: {$order->order_status->getLabel()}\n";

        return $message;
    }

    #[On('refreshPage')]
    public function refreshPage(): void
    {
        $this->refreshFormData([
            'total_price',
        ]);
    }
    
    protected function getListeners(): array
    {
        return array_merge(parent::getListeners(), [
            'copy-to-clipboard' => 'handleCopyToClipboard',
        ]);
    }
    
    public function handleCopyToClipboard($text): void
    {
        $escapedText = json_encode($text);
        $this->dispatch('execute-js', js: "
            navigator.clipboard.writeText({$escapedText}).then(function() {
                console.log('Text copied to clipboard');
            }).catch(function(err) {
                console.error('Failed to copy text: ', err);
                // Fallback for older browsers
                const textarea = document.createElement('textarea');
                textarea.value = {$escapedText};
                textarea.style.position = 'fixed';
                textarea.style.opacity = '0';
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
            });
        ");
    }
}
