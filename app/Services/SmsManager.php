<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Order;
use App\Models\SmsConfiguration;
use Illuminate\Support\Facades\Log;

class SmsManager
{
    protected ?SmsService $smsqService = null;
    protected ?BulkSMSBDService $bulkSMSBDService = null;
    protected ?string $activeProvider = null;

    public function __construct()
    {
        $this->determineActiveProvider();
    }

    /**
     * Determine which SMS provider is active
     */
    protected function determineActiveProvider(): void
    {
        $config = SmsConfiguration::first();
        $this->activeProvider = $config?->active_provider ?? 'smsq';
        
        Log::info('SMS Manager initialized', [
            'active_provider' => $this->activeProvider
        ]);
    }

    /**
     * Get the active SMS service instance
     */
    protected function getActiveService()
    {
        if ($this->activeProvider === 'bulksmsbd') {
            if (!$this->bulkSMSBDService) {
                $this->bulkSMSBDService = new BulkSMSBDService();
            }
            return $this->bulkSMSBDService;
        }

        // Default to SMSQ
        if (!$this->smsqService) {
            $this->smsqService = new SmsService();
        }
        return $this->smsqService;
    }

    /**
     * Send SMS to a single recipient
     *
     * @param string $phoneNumber
     * @param string $message
     * @param array $options
     * @return bool
     */
    public function sendSms(string $phoneNumber, string $message, array $options = []): bool
    {
        try {
            if ($this->activeProvider === 'bulksmsbd') {
                $service = $this->getActiveService();
                $result = $service->sendSMS($phoneNumber, $message, $options);
                return $result['success'] ?? false;
            }

            // Use SMSQ service
            $service = $this->getActiveService();
            return $service->sendSms($phoneNumber, $message, $options);
        } catch (\Exception $e) {
            Log::error('SMS Manager send failed', [
                'provider' => $this->activeProvider,
                'phone' => $phoneNumber,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send bulk SMS to multiple recipients with the same message
     *
     * @param array $phoneNumbers
     * @param string $message
     * @param array $options
     * @return array
     */
    public function sendBulkSameMessage(array $phoneNumbers, string $message, array $options = []): array
    {
        try {
            if ($this->activeProvider === 'bulksmsbd') {
                $service = $this->getActiveService();
                $result = $service->sendSMS($phoneNumbers, $message, $options);
                
                return [
                    'success' => $result['success'] ?? false,
                    'sent_count' => $result['success'] ? count($phoneNumbers) : 0,
                    'error' => $result['error'] ?? null,
                    'response' => $result
                ];
            }

            // Use SMSQ service
            $service = $this->getActiveService();
            return $service->sendBulkSameMessage($phoneNumbers, $message, $options);
        } catch (\Exception $e) {
            Log::error('SMS Manager bulk send failed', [
                'provider' => $this->activeProvider,
                'count' => count($phoneNumbers),
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'sent_count' => 0,
                'error' => 'Exception: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Send bulk promotional SMS to customers
     *
     * @param string $message
     * @param array $customerIds
     * @return array
     */
    public function sendBulkPromotional(string $message, array $customerIds = []): array
    {
        try {
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
                $phoneNumbers = [];
                foreach ($customers as $customer) {
                    $phoneNumbers[] = $this->formatPhoneNumber($customer->phone_number);
                }
                return $this->sendBulkSameMessage($phoneNumbers, $message);
            }

            // Has placeholders - personalize each message
            if ($this->activeProvider === 'bulksmsbd') {
                // BulkSMSBD - send personalized messages one by one or in groups
                $service = $this->getActiveService();
                $recipients = [];
                
                foreach ($customers as $customer) {
                    $personalizedMessage = str_replace(
                        ['{name}', '{first_name}'],
                        [$customer->full_name, explode(' ', $customer->full_name)[0]],
                        $message
                    );
                    $recipients[] = [
                        'phone' => $customer->phone_number,
                        'message' => $personalizedMessage
                    ];
                }
                
                $result = $service->sendBulkSMS($recipients);
                
                return [
                    'success' => $result['success'] > 0,
                    'sent_count' => $result['success'],
                    'failed_count' => $result['failed'],
                    'total' => $result['total']
                ];
            }

            // SMSQ - use the existing bulk promotional method
            $service = $this->getActiveService();
            return $service->sendBulkPromotional($message, $customerIds);
        } catch (\Exception $e) {
            Log::error('SMS Manager bulk promotional failed', [
                'provider' => $this->activeProvider,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'sent_count' => 0,
                'error' => 'Exception: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Send order confirmation SMS
     *
     * @param Order $order
     * @return bool
     */
    public function sendOrderConfirmation(Order $order): bool
    {
        try {
            $message = "Dear {$order->customer_name}, your order #{$order->order_tracking_id} has been confirmed. Total: {$order->total_price} BDT. Thank you for shopping with " . config('app.name');

            if ($this->activeProvider === 'bulksmsbd') {
                $service = $this->getActiveService();
                $result = $service->sendOrderConfirmation(
                    $order->customer_mobile,
                    $order->order_tracking_id,
                    $order->total_price
                );
                return $result['success'] ?? false;
            }

            // Use SMSQ service
            $service = $this->getActiveService();
            $service->sendOrderConfirmation($order);
            return true;
        } catch (\Exception $e) {
            Log::error('SMS Manager order confirmation failed', [
                'provider' => $this->activeProvider,
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send order status update SMS
     *
     * @param Order $order
     * @return bool
     */
    public function sendOrderStatusUpdate(Order $order): bool
    {
        try {
            $statusMessages = [
                'pending' => 'is being processed',
                'confirmed' => 'has been confirmed',
                'shipped' => 'has been shipped',
                'delivered' => 'has been delivered',
                'cancelled' => 'has been cancelled',
            ];

            $status = $statusMessages[$order->order_status->value] ?? 'status updated';
            $message = "Dear {$order->customer_name}, your order #{$order->order_tracking_id} {$status}. Track at: " . url('/track-order');

            return $this->sendSms($order->customer_mobile, $message);
        } catch (\Exception $e) {
            Log::error('SMS Manager order status update failed', [
                'provider' => $this->activeProvider,
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send OTP SMS
     *
     * @param string $phoneNumber
     * @param string $otp
     * @param string|null $appName
     * @return bool
     */
    public function sendOTP(string $phoneNumber, string $otp, ?string $appName = null): bool
    {
        try {
            $appName = $appName ?? config('app.name');
            
            if ($this->activeProvider === 'bulksmsbd') {
                $service = $this->getActiveService();
                $result = $service->sendOTP($phoneNumber, $otp, $appName);
                return $result['success'] ?? false;
            }

            // For SMSQ
            $message = "Your OTP for {$appName} is: {$otp}. Valid for 5 minutes. Do not share with anyone.";
            return $this->sendSms($phoneNumber, $message);
        } catch (\Exception $e) {
            Log::error('SMS Manager OTP send failed', [
                'provider' => $this->activeProvider,
                'phone' => $phoneNumber,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Check SMS balance
     *
     * @return array
     */
    public function checkBalance(): array
    {
        try {
            if ($this->activeProvider === 'bulksmsbd') {
                $service = $this->getActiveService();
                return $service->checkBalance();
            }

            // SMSQ doesn't have a direct balance check in the current implementation
            return [
                'success' => false,
                'error' => 'Balance check not available for SMSQ provider'
            ];
        } catch (\Exception $e) {
            Log::error('SMS Manager balance check failed', [
                'provider' => $this->activeProvider,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => 'Exception: ' . $e->getMessage()
            ];
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

    /**
     * Get the currently active provider name
     *
     * @return string
     */
    public function getActiveProvider(): string
    {
        return $this->activeProvider ?? 'smsq';
    }

    /**
     * Refresh the active provider (useful after admin changes settings)
     *
     * @return void
     */
    public function refreshProvider(): void
    {
        $this->determineActiveProvider();
        $this->smsqService = null;
        $this->bulkSMSBDService = null;
    }
}