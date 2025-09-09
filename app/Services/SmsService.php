<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Order;
use App\Models\SmsConfiguration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * SMSQ API Error Codes
     * All error codes come under HTTP 200 success response
     */
    const ERROR_CODES = [
        0 => 'Success',
        1 => 'Invalid API Key',
        2 => 'Invalid Client ID',
        3 => 'Invalid Sender ID',
        4 => 'Invalid Mobile Number',
        5 => 'Empty Message',
        6 => 'Invalid Message',
        7 => 'Insufficient Balance',
        8 => 'API Disabled',
        9 => 'Account Suspended',
        10 => 'Account Expired',
        11 => 'Invalid Template ID',
        12 => 'Template Not Approved',
        13 => 'Invalid Schedule Time',
        14 => 'Invalid Unicode Parameter',
        15 => 'Invalid Flash Parameter',
        16 => 'Rate Limit Exceeded',
        17 => 'DND Number',
        18 => 'Blacklisted Number',
        19 => 'Route Not Available',
        20 => 'Server Error',
        99 => 'Unknown Error'
    ];

    /**
     * Check if error code indicates authentication issue
     */
    protected function isAuthenticationError(int $errorCode): bool
    {
        return in_array($errorCode, [1, 2, 3, 8, 9, 10]);
    }

    /**
     * Check if error code indicates balance issue
     */
    protected function isBalanceError(int $errorCode): bool
    {
        return $errorCode === 7;
    }

    /**
     * Get human-readable error message
     */
    protected function getErrorMessage(int $errorCode): string
    {
        return self::ERROR_CODES[$errorCode] ?? 'Unknown error code: ' . $errorCode;
    }
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
     * Send promotional SMS to customers - optimized version
     *
     * @param string $message
     * @param array $customerIds
     * @return array
     */
    public function sendBulkPromotional(string $message, array $customerIds = []): array
    {
        $query = Customer::query();

        if (!empty($customerIds)) {
            $query->whereIn('id', $customerIds);
        }

        $customers = $query->whereNotNull('phone_number')->get();

        if ($customers->isEmpty()) {
            return [
                'success' => false,
                'error' => 'No customers found with phone numbers',
                'sent_count' => 0
            ];
        }

        // Check if message contains placeholders
        $hasPlaceholders = strpos($message, '{name}') !== false || strpos($message, '{first_name}') !== false;

        if (!$hasPlaceholders) {
            // No placeholders - send same message to all
            $phoneNumbers = $customers->pluck('phone_number')->toArray();
            return $this->sendBulkSameMessage($phoneNumbers, $message);
        } else {
            // Has placeholders - personalize each message
            $recipientDetails = [];
            foreach ($customers as $customer) {
                $personalizedMessage = str_replace(
                    ['{name}', '{first_name}'],
                    [$customer->full_name, explode(' ', $customer->full_name)[0]],
                    $message
                );
                $recipientDetails[] = [
                    'phone' => $customer->phone_number,
                    'message' => $personalizedMessage
                ];
            }
            return $this->sendBulkDifferentMessages($recipientDetails);
        }
    }

    /**
     * Send bulk SMS using SMSQ API - optimized version
     * Sends same message to multiple recipients in a single API call when possible
     *
     * @param array $recipients Array of ['phone' => 'number', 'message' => 'text'] or Collection of customers
     * @param string|null $defaultMessage Default message if not specified per recipient
     * @param array $options Optional parameters (Is_Unicode, Is_Flash, DataCoding, ScheduleTime)
     * @return array
     */
    public function sendBulkSms($recipients, ?string $defaultMessage = null, array $options = []): array
    {
        try {
            // Collect phone numbers and check if all have the same message
            $phoneNumbers = [];
            $messages = [];
            $recipientDetails = [];

            // Handle different input formats
            if ($recipients instanceof \Illuminate\Support\Collection) {
                foreach ($recipients as $recipient) {
                    $phone = $this->formatPhoneNumber($recipient->phone_number ?? $recipient->customer_mobile);
                    $message = $defaultMessage ?? 'Message from ' . config('app.name');
                    $phoneNumbers[] = $phone;
                    $messages[] = $message;
                    $recipientDetails[] = ['phone' => $phone, 'message' => $message];
                }
            } elseif (is_array($recipients)) {
                foreach ($recipients as $recipient) {
                    if (is_array($recipient)) {
                        $phone = $this->formatPhoneNumber($recipient['phone'] ?? $recipient['phone_number']);
                        $message = $recipient['message'] ?? $defaultMessage ?? 'Message from ' . config('app.name');
                    } else {
                        // Simple phone number array
                        $phone = $this->formatPhoneNumber($recipient);
                        $message = $defaultMessage ?? 'Message from ' . config('app.name');
                    }
                    $phoneNumbers[] = $phone;
                    $messages[] = $message;
                    $recipientDetails[] = ['phone' => $phone, 'message' => $message];
                }
            }

            if (empty($phoneNumbers)) {
                return [
                    'success' => false,
                    'error' => 'No valid recipients',
                    'sent_count' => 0
                ];
            }

            // Check if all messages are the same (for optimization)
            $uniqueMessages = array_unique($messages);

            if (count($uniqueMessages) === 1) {
                // All recipients get the same message - use single API call with comma-separated numbers
                return $this->sendBulkSameMessage($phoneNumbers, $messages[0], $options);
            } else {
                // Different messages for different recipients - use bulk endpoint or multiple calls
                return $this->sendBulkDifferentMessages($recipientDetails, $options);
            }
        } catch (\Exception $e) {
            Log::error('Bulk SMS exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => 'Exception: ' . $e->getMessage(),
                'sent_count' => 0
            ];
        }
    }

    /**
     * Send same message to multiple recipients in a single API call
     *
     * @param array $phoneNumbers Array of formatted phone numbers
     * @param string $message The message to send
     * @param array $options Optional parameters
     * @return array
     */
    public function sendBulkSameMessage(array $phoneNumbers, string $message, array $options = []): array
    {
        try {
            // Get SMS configuration from database
            $smsConfig = SmsConfiguration::first();

            // Fallback to env config if no database config exists
            $apiKey = $smsConfig?->smsq_api_key ?? config('services.smsq.api_key');
            $clientId = $smsConfig?->smsq_client_id ?? config('services.smsq.client_id');
            $senderId = $smsConfig?->smsq_sender_id ?? config('services.smsq.sender_id');

            // Join phone numbers with comma as per SMSQ API
            $mobileNumbers = implode(',', $phoneNumbers);

            // Prepare API parameters
            $params = [
                'ApiKey' => $apiKey,
                'ClientId' => $clientId,
                'SenderId' => $senderId,
                'MobileNumbers' => $mobileNumbers,
                'Message' => $message
            ];

            // Add optional parameters if provided
            if (isset($options['Is_Unicode'])) {
                $params['Is_Unicode'] = $options['Is_Unicode'];
            }
            if (isset($options['Is_Flash'])) {
                $params['Is_Flash'] = $options['Is_Flash'];
            }
            if (isset($options['DataCoding'])) {
                $params['DataCoding'] = $options['DataCoding'];
            }
            if (isset($options['ScheduleTime'])) {
                $params['SchedTime'] = $options['ScheduleTime'];
            }

            // Use standard SendSMS endpoint with multiple numbers
            $endpoint = config('services.smsq.endpoint', 'https://console.smsq.global/api/v2/SendSMS');
            $method = config('services.smsq.method', 'GET');

            if (strtoupper($method) === 'POST') {
                $response = Http::post($endpoint, $params);
            } else {
                $response = Http::get($endpoint, $params);
            }

            Log::info('Bulk SMS API Response', [
                'response' => $response->body(),
                'status' => $response->status()
            ]);

            if ($response->successful()) {
                $responseData = $response->json();

                // Check SMSQ specific error codes (all come under HTTP 200)
                $errorCode = $responseData['error_code'] ?? $responseData['ErrorCode'] ?? null;

                if ($errorCode !== null && $errorCode != 0) {
                    $errorMessage = $this->getErrorMessage($errorCode);
                    $errorDescription = $responseData['error_description'] ?? $responseData['ErrorDescription'] ?? $errorMessage;

                    // Log different error types with appropriate severity
                    if ($this->isAuthenticationError($errorCode)) {
                        Log::critical('SMS Authentication Error', [
                            'error_code' => $errorCode,
                            'error' => $errorDescription,
                            'message' => 'Check SMS Settings in admin panel'
                        ]);
                    } elseif ($this->isBalanceError($errorCode)) {
                        Log::warning('SMS Balance Insufficient', [
                            'error_code' => $errorCode,
                            'error' => $errorDescription,
                            'recipients_count' => count($phoneNumbers)
                        ]);
                    } else {
                        Log::error('Bulk SMS API error', [
                            'error_code' => $errorCode,
                            'error' => $errorDescription
                        ]);
                    }

                    return [
                        'success' => false,
                        'error' => $errorDescription,
                        'error_code' => $errorCode,
                        'error_type' => $this->isAuthenticationError($errorCode) ? 'authentication' : ($this->isBalanceError($errorCode) ? 'balance' : 'general'),
                        'sent_count' => 0
                    ];
                }

                Log::info('Bulk SMS sent successfully', [
                    'recipients_count' => count($phoneNumbers),
                    'message' => substr($message, 0, 50) . '...',
                    'response' => $responseData
                ]);

                return [
                    'success' => true,
                    'sent_count' => count($phoneNumbers),
                    'response' => $responseData
                ];
            }

            Log::error('Failed to send bulk SMS', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return [
                'success' => false,
                'error' => 'Failed to send bulk SMS: ' . $response->body(),
                'sent_count' => 0
            ];
        } catch (\Exception $e) {
            Log::error('Bulk same message SMS exception', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Exception: ' . $e->getMessage(),
                'sent_count' => 0
            ];
        }
    }

    /**
     * Send different messages to different recipients
     *
     * @param array $recipientDetails Array of ['phone' => 'number', 'message' => 'text']
     * @param array $options Optional parameters
     * @return array
     */
    protected function sendBulkDifferentMessages(array $recipientDetails, array $options = []): array
    {
        $successCount = 0;
        $failedCount = 0;
        $errors = [];

        // Group recipients by message to optimize API calls
        $messageGroups = [];
        foreach ($recipientDetails as $detail) {
            $messageHash = md5($detail['message']);
            if (!isset($messageGroups[$messageHash])) {
                $messageGroups[$messageHash] = [
                    'message' => $detail['message'],
                    'phones' => []
                ];
            }
            $messageGroups[$messageHash]['phones'][] = $detail['phone'];
        }

        // Send each message group
        foreach ($messageGroups as $group) {
            $result = $this->sendBulkSameMessage($group['phones'], $group['message'], $options);

            if ($result['success']) {
                $successCount += $result['sent_count'];
            } else {
                $failedCount += count($group['phones']);
                $errors[] = $result['error'];
            }

            // Add small delay between API calls to avoid rate limiting
            usleep(100000); // 100ms delay
        }

        return [
            'success' => $successCount > 0,
            'sent_count' => $successCount,
            'failed_count' => $failedCount,
            'errors' => $errors,
            'total_groups' => count($messageGroups)
        ];
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
            // Get SMS configuration from database
            $smsConfig = SmsConfiguration::first();

            // Fallback to env config if no database config exists
            $apiKey = $smsConfig?->smsq_api_key ?? config('services.smsq.api_key');
            $clientId = $smsConfig?->smsq_client_id ?? config('services.smsq.client_id');
            $senderId = $smsConfig?->smsq_sender_id ?? config('services.smsq.sender_id');

            $phoneNumber = $this->formatPhoneNumber($phoneNumber);
            $params = [
                'ApiKey' => $apiKey,
                'ClientId' => $clientId,
                'MobileNumbers' => $phoneNumber,
                'Message' => $message,
                'SenderId' => $options['sender_id'] ?? $senderId,
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
                $responseData = $response->json();

                // Check SMSQ specific error codes (all come under HTTP 200)
                $errorCode = $responseData['error_code'] ?? $responseData['ErrorCode'] ?? null;

                if ($errorCode !== null && $errorCode != 0) {
                    $errorMessage = $this->getErrorMessage($errorCode);
                    $errorDescription = $responseData['error_description'] ?? $responseData['ErrorDescription'] ?? $errorMessage;

                    // Log different error types with appropriate severity
                    if ($this->isAuthenticationError($errorCode)) {
                        Log::critical('SMS Authentication Error', [
                            'error_code' => $errorCode,
                            'error' => $errorDescription,
                            'phone' => $phoneNumber,
                            'message' => 'Check SMS Settings in admin panel'
                        ]);
                    } elseif ($this->isBalanceError($errorCode)) {
                        Log::warning('SMS Balance Insufficient', [
                            'error_code' => $errorCode,
                            'error' => $errorDescription,
                            'phone' => $phoneNumber
                        ]);
                    } else {
                        Log::error('SMS API error', [
                            'error_code' => $errorCode,
                            'error' => $errorDescription,
                            'phone' => $phoneNumber
                        ]);
                    }

                    return false;
                }

                Log::info('SMS sent successfully', [
                    'phone' => $phoneNumber,
                    'message' => substr($message, 0, 50) . '...',
                    'response' => $responseData
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
            // Get SMS configuration from database
            $smsConfig = SmsConfiguration::first();

            // Fallback to env config if no database config exists
            $apiKey = $smsConfig?->smsq_api_key ?? config('services.smsq.api_key');
            $clientId = $smsConfig?->smsq_client_id ?? config('services.smsq.client_id');

            $response = Http::get('https://console.smsq.global/api/v2/MessageStatus', [
                'ApiKey' => $apiKey,
                'ClientId' => $clientId,
                'MessageId' => $messageId,
            ]);

            if ($response->successful()) {
                $responseData = $response->json();

                // Check for error codes in status check
                $errorCode = $responseData['error_code'] ?? $responseData['ErrorCode'] ?? null;

                if ($errorCode !== null && $errorCode != 0) {
                    $errorMessage = $this->getErrorMessage($errorCode);
                    $errorDescription = $responseData['error_description'] ?? $responseData['ErrorDescription'] ?? $errorMessage;

                    Log::error('SMS Status Check Error', [
                        'messageId' => $messageId,
                        'error_code' => $errorCode,
                        'error' => $errorDescription
                    ]);

                    return [
                        'success' => false,
                        'error_code' => $errorCode,
                        'error' => $errorDescription,
                        'messageId' => $messageId
                    ];
                }

                return $responseData;
            }

            Log::error('Failed to check SMS status - HTTP error', [
                'messageId' => $messageId,
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Failed to check SMS status - Exception', [
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
    public function formatPhoneNumber(string $phoneNumber): string
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
