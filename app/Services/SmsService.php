<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Send order confirmation SMS
     *
     * @param Order $order
     * @return void
     */
    public function sendOrderConfirmation(Order $order): void
    {
        $message = "Dear {$order->customer_name}, your order #{$order->order_tracking_id} has been confirmed. Total: {$order->total_price} BDT. Thank you for shopping with " . config('app.name');

        $this->sendSms($order->customer_mobile, $message);
    }

    /**
     * Send order status update SMS
     *
     * @param Order $order
     * @return void
     */
    public function sendOrderStatusUpdate(Order $order): void
    {
        $statusMessages = [
            'pending' => 'is being processed',
            'confirmed' => 'has been confirmed',
            'shipped' => 'has been shipped',
            'delivered' => 'has been delivered',
            'cancelled' => 'has been cancelled',
        ];

        $status = $statusMessages[$order->order_status->value] ?? 'status updated';

        $message = "Dear {$order->customer_name}, your order #{$order->order_tracking_id} {$status}. Track at: " . url('/track-order');

        $this->sendSms($order->customer_mobile, $message);
    }

    /**
     * Send promotional SMS to customers
     *
     * @param string $message
     * @param array $customerIds
     * @return void
     */
    public function sendBulkPromotional(string $message, array $customerIds = []): void
    {
        $query = Customer::query();

        if (!empty($customerIds)) {
            $query->whereIn('id', $customerIds);
        }

        $customers = $query->whereNotNull('phone_number')->get();

        foreach ($customers as $customer) {
            $personalizedMessage = str_replace(
                ['{name}', '{first_name}'],
                [$customer->full_name, explode(' ', $customer->full_name)[0]],
                $message
            );

            $this->sendSms($customer->phone_number, $personalizedMessage);
        }
    }

    /**
     * Send SMS directly via SMSQ API
     *
     * @param string $phoneNumber
     * @param string $message
     * @param array $options
     * @return bool
     */
    public function sendSms(string $phoneNumber, string $message, array $options = []): bool
    {
        try {
            $phoneNumber = $this->formatPhoneNumber($phoneNumber);
            $params = [
                'ApiKey' => config('services.smsq.api_key'),
                'ClientId' => config('services.smsq.client_id'),
                'MobileNumbers' => $phoneNumber,
                'Message' => $message,
                'SenderId' => $options['sender_id'] ?? config('services.smsq.sender_id'),
                'Is_Unicode' => $options['is_unicode'] ?? false,
                'Is_Flash' => $options['is_flash'] ?? false,
                'SchedTime' => $options['schedule_time'] ?? null,
                'TemplateId' => $options['template_id'] ?? null,
            ];
            $method = config('services.smsq.method', 'GET');

            if (strtoupper($method) === 'POST') {
                $response = Http::post(config('services.smsq.endpoint', 'https://console.smsq.global/api/v2/SendSMS'), $params);
            } else {
                $response = Http::get(config('services.smsq.endpoint', 'https://console.smsq.global/api/v2/SendSMS'), $params);
            }

            if ($response->successful()) {

                Log::info('SMS sent successfully', [
                    'phone' => $phoneNumber,
                    'message' => substr($message, 0, 50) . '...'
                ]);
                return true;
            }

            Log::error('Failed to send SMS', [
                'phone' => $phoneNumber,
                'response' => $response->json()
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('SMS sending exception', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Check SMS delivery status
     *
     * @param string $messageId
     * @return array|null
     */
    public function checkSmsStatus(string $messageId): ?array
    {
        try {
            $response = Http::get('https://console.smsq.global/api/v2/MessageStatus', [
                'ApiKey' => config('services.smsq.api_key'),
                'ClientId' => config('services.smsq.client_id'),
                'MessageId' => $messageId,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Failed to check SMS status', [
                'messageId' => $messageId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Format phone number for Bangladesh
     *
     * @param string $phoneNumber
     * @return string
     */
    protected function formatPhoneNumber(string $phoneNumber): string
    {
        // Remove any spaces or special characters
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        // If number starts with 0, replace with 880
        if (str_starts_with($phoneNumber, '0')) {
            $phoneNumber = '880' . substr($phoneNumber, 1);
        }

        // If number doesn't start with 880, add it
        if (!str_starts_with($phoneNumber, '880')) {
            $phoneNumber = '880' . $phoneNumber;
        }

        return $phoneNumber;
    }
}
