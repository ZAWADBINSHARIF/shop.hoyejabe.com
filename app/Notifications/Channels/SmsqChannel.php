<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsqChannel
{
    /**
     * Send the given notification via SMSQ SMS API.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        // Get the phone number from the notifiable
        $phoneNumber = $notifiable->routeNotificationFor('smsq') ?? $notifiable->phone_number;

        if (!$phoneNumber) {
            Log::warning('No phone number found for SMS notification');
            return;
        }

        // Get the SMS message from the notification
        $message = $notification->toSmsq($notifiable);

        if (is_string($message)) {
            $message = ['message' => $message];
        }

        // Send the SMS via SMSQ API
        $this->sendSms($phoneNumber, $message);
    }

    /**
     * Send SMS via SMSQ API
     *
     * @param string $phoneNumber
     * @param array $message
     * @return void
     */
    protected function sendSms(string $phoneNumber, array $message)
    {
        try {
            $params = [
                'ApiKey' => config('services.smsq.api_key'),
                'ClientId' => config('services.smsq.client_id'),
                'MobileNumbers' => $this->formatPhoneNumber($phoneNumber),
                'Message' => $message['message'],
                'SenderId' => $message['sender_id'] ?? config('services.smsq.sender_id'),
                'Is_Unicode' => $message['is_unicode'] ?? false,
                'Is_Flash' => $message['is_flash'] ?? false,
                'SchedTime' => $message['schedule_time'] ?? null,
                'TemplateId' => $message['template_id'] ?? null,
            ];

            // Use GET method by default, but allow POST method as fallback
            $method = config('services.smsq.method', 'GET');

            if (strtoupper($method) === 'POST') {
                $response = Http::post(config('services.smsq.endpoint', 'https://console.smsq.global/api/v2/SendSMS'), $params);
            } else {
                $response = Http::get(config('services.smsq.endpoint', 'https://console.smsq.global/api/v2/SendSMS'), $params);
            }
            if ($response->successful()) {
                Log::info('SMS sent successfully', [
                    'phone' => $phoneNumber,
                    'response' => $response->json()
                ]);
            } else {
                Log::error('Failed to send SMS', [
                    'phone' => $phoneNumber,
                    'response' => $response->json(),
                    'status' => $response->status()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('SMS sending exception', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage()
            ]);
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
