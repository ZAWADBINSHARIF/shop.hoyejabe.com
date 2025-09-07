<?php

namespace App\Traits;

use App\Notifications\SendOtpNotification;
use App\Notifications\SendSmsNotification;

trait HasSmsNotifications
{
    /**
     * Route notifications for the SMSQ channel.
     *
     * @return string|null
     */
    public function routeNotificationForSmsq()
    {
        return $this->phone_number ?? $this->customer_mobile ?? $this->mobile;
    }

    /**
     * Send OTP via SMS
     *
     * @param string $purpose
     * @return string The generated OTP
     */
    public function sendOtp(string $purpose = 'verification'): string
    {
        $otp = $this->generateOtp();
        
        // Store OTP in cache with expiration
        $cacheKey = "otp_{$this->getTable()}_{$this->id}_{$purpose}";
        $expirationMinutes = $this->getOtpExpiration($purpose);
        
        cache()->put($cacheKey, [
            'otp' => $otp,
            'purpose' => $purpose,
            'created_at' => now()
        ], now()->addMinutes($expirationMinutes));
        
        // Send OTP notification
        $this->notify(new SendOtpNotification($otp, $purpose));
        
        return $otp;
    }

    /**
     * Verify OTP
     *
     * @param string $otp
     * @param string $purpose
     * @return bool
     */
    public function verifyOtp(string $otp, string $purpose = 'verification'): bool
    {
        $cacheKey = "otp_{$this->getTable()}_{$this->id}_{$purpose}";
        $cachedData = cache()->get($cacheKey);
        
        if (!$cachedData) {
            return false;
        }
        
        if ($cachedData['otp'] === $otp && $cachedData['purpose'] === $purpose) {
            // Clear OTP after successful verification
            cache()->forget($cacheKey);
            return true;
        }
        
        return false;
    }

    /**
     * Send custom SMS message
     *
     * @param string $message
     * @param string|null $senderId
     * @param bool $isFlash
     * @return void
     */
    public function sendSms(string $message, ?string $senderId = null, bool $isFlash = false): void
    {
        $this->notify(new SendSmsNotification($message, $senderId, $isFlash));
    }

    /**
     * Generate a random OTP
     *
     * @return string
     */
    protected function generateOtp(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Get OTP expiration time in minutes based on purpose
     *
     * @param string $purpose
     * @return int
     */
    protected function getOtpExpiration(string $purpose): int
    {
        $expirations = [
            'verification' => 5,
            'login' => 5,
            'password_reset' => 10,
            'order_confirmation' => 15,
        ];
        
        return $expirations[$purpose] ?? 5;
    }
}