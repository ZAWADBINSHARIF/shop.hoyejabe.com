<?php

namespace App\Services;

use App\Models\SmsConfiguration;
use Illuminate\Support\Facades\Log;

class BulkSMSBDService
{
    protected string $apiUrl;
    protected string $apiKey;
    protected string $senderId;

    public function __construct()
    {
        // Get SMS configuration from database
        $config = SmsConfiguration::first();
        
        // Use database config if available, otherwise fallback to env config
        $this->apiUrl = config('services.bulksmsbd.endpoint', 'http://bulksmsbd.net/api/smsapi');
        $this->apiKey = $config?->bulksmsbd_api_key ?? config('services.bulksmsbd.api_key');
        $this->senderId = $config?->bulksmsbd_sender_id ?? config('services.bulksmsbd.sender_id');
    }

    /**
     * Send SMS to single or multiple recipients
     *
     * @param string|array $numbers Phone number(s) - can be string for single or array for multiple
     * @param string $message The message to send
     * @param array $options Optional parameters like custom sender_id
     * @return array Response with success status and details
     */
    public function sendSMS($numbers, string $message, array $options = []): array
    {
        try {
            // Format phone numbers
            if (is_array($numbers)) {
                $formattedNumbers = array_map([$this, 'formatPhoneNumber'], $numbers);
                $numberString = implode(',', $formattedNumbers);
            } else {
                $numberString = $this->formatPhoneNumber($numbers);
            }

            // Prepare API parameters
            $data = [
                'api_key' => $this->apiKey,
                'senderid' => $options['sender_id'] ?? $this->senderId,
                'number' => $numberString,
                'message' => $message
            ];

            // Send request using cURL as per BulkSMSBD documentation
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                Log::error('BulkSMSBD cURL error', [
                    'error' => $error,
                    'numbers' => $numberString
                ]);
                
                return [
                    'success' => false,
                    'error' => 'cURL error: ' . $error,
                    'http_code' => $httpCode
                ];
            }

            // Parse response
            $responseData = json_decode($response, true);
            
            if ($httpCode === 200 && isset($responseData['response_code']) && $responseData['response_code'] == 202) {
                Log::info('BulkSMSBD SMS sent successfully', [
                    'numbers' => $numberString,
                    'message_length' => strlen($message),
                    'response' => $responseData
                ]);

                return [
                    'success' => true,
                    'message_id' => $responseData['message_id'] ?? null,
                    'success_message' => $responseData['success_message'] ?? 'SMS sent successfully',
                    'response' => $responseData
                ];
            }

            Log::warning('BulkSMSBD SMS sending failed', [
                'numbers' => $numberString,
                'response' => $response,
                'http_code' => $httpCode
            ]);

            return [
                'success' => false,
                'error' => $responseData['error_message'] ?? 'Failed to send SMS',
                'response' => $responseData,
                'http_code' => $httpCode
            ];

        } catch (\Exception $e) {
            Log::error('BulkSMSBD exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => 'Exception: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Send bulk SMS to multiple recipients with different messages
     *
     * @param array $recipients Array of ['phone' => 'number', 'message' => 'text']
     * @param string|null $defaultMessage Default message if not specified per recipient
     * @return array
     */
    public function sendBulkSMS(array $recipients, ?string $defaultMessage = null): array
    {
        $results = [
            'total' => count($recipients),
            'success' => 0,
            'failed' => 0,
            'details' => []
        ];

        foreach ($recipients as $recipient) {
            $phone = $recipient['phone'] ?? $recipient['phone_number'] ?? null;
            $message = $recipient['message'] ?? $defaultMessage;

            if (!$phone || !$message) {
                $results['failed']++;
                $results['details'][] = [
                    'phone' => $phone,
                    'success' => false,
                    'error' => 'Missing phone or message'
                ];
                continue;
            }

            $response = $this->sendSMS($phone, $message);
            
            if ($response['success']) {
                $results['success']++;
            } else {
                $results['failed']++;
            }

            $results['details'][] = [
                'phone' => $phone,
                'success' => $response['success'],
                'response' => $response
            ];

            // Add delay to avoid rate limiting
            usleep(100000); // 100ms delay between requests
        }

        return $results;
    }

    /**
     * Send promotional SMS to multiple recipients
     *
     * @param array|string $phoneNumbers Array of phone numbers or comma-separated string
     * @param string $message Promotional message
     * @return array
     */
    public function sendPromotionalSMS($phoneNumbers, string $message): array
    {
        return $this->sendSMS($phoneNumbers, $message);
    }

    /**
     * Send transactional SMS (order confirmations, OTPs, etc.)
     *
     * @param string $phoneNumber Single phone number
     * @param string $message Transactional message
     * @return array
     */
    public function sendTransactionalSMS(string $phoneNumber, string $message): array
    {
        return $this->sendSMS($phoneNumber, $message);
    }

    /**
     * Check SMS balance
     *
     * @return array
     */
    public function checkBalance(): array
    {
        try {
            $data = [
                'api_key' => $this->apiKey,
                'action' => 'balance'
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $responseData = json_decode($response, true);

            return [
                'success' => $httpCode === 200,
                'balance' => $responseData['balance'] ?? 0,
                'currency' => $responseData['currency'] ?? 'BDT',
                'response' => $responseData
            ];

        } catch (\Exception $e) {
            Log::error('BulkSMSBD balance check failed', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
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

    /**
     * Validate Bangladesh phone number
     *
     * @param string $phoneNumber
     * @return bool
     */
    public function validatePhoneNumber(string $phoneNumber): bool
    {
        $formatted = $this->formatPhoneNumber($phoneNumber);
        
        // Bangladesh phone numbers should be 13 digits (880 + 10 digits)
        // and start with valid operator codes
        $pattern = '/^880(1[3-9])\d{8}$/';
        
        return preg_match($pattern, $formatted) === 1;
    }

    /**
     * Send OTP SMS
     *
     * @param string $phoneNumber
     * @param string $otp
     * @param string|null $appName
     * @return array
     */
    public function sendOTP(string $phoneNumber, string $otp, ?string $appName = null): array
    {
        $appName = $appName ?? config('app.name');
        $message = "Your OTP for {$appName} is: {$otp}. Valid for 5 minutes. Do not share with anyone.";
        
        return $this->sendTransactionalSMS($phoneNumber, $message);
    }

    /**
     * Send order confirmation SMS
     *
     * @param string $phoneNumber
     * @param string $orderId
     * @param float $amount
     * @return array
     */
    public function sendOrderConfirmation(string $phoneNumber, string $orderId, float $amount): array
    {
        $appName = config('app.name');
        $message = "Order #{$orderId} confirmed! Total: {$amount} BDT. Track your order at " . url('/track-order') . ". Thank you for shopping with {$appName}.";
        
        return $this->sendTransactionalSMS($phoneNumber, $message);
    }

    /**
     * Send delivery notification SMS
     *
     * @param string $phoneNumber
     * @param string $orderId
     * @return array
     */
    public function sendDeliveryNotification(string $phoneNumber, string $orderId): array
    {
        $appName = config('app.name');
        $message = "Good news! Your order #{$orderId} has been delivered. Thank you for shopping with {$appName}. We hope you enjoy your purchase!";
        
        return $this->sendTransactionalSMS($phoneNumber, $message);
    }
}