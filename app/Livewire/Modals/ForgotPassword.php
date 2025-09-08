<?php

namespace App\Livewire\Modals;

use App\Models\Customer;
use App\Services\SmsService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class ForgotPassword extends Component
{
    public $phone_number = '';
    public $otp = '';
    public $password = '';
    public $password_confirmation = '';
    public $step = 1; // 1: Phone, 2: OTP & Password
    public $otpSent = false;
    public $countdown = 0;
    
    protected $rules = [
        'phone_number' => 'required|regex:/^01[3-9]\d{8}$/|exists:customers,phone_number',
        'otp' => 'required|digits:6',
        'password' => 'required|min:6|confirmed',
    ];

    protected $messages = [
        'phone_number.required' => 'Phone number is required',
        'phone_number.regex' => 'Please enter a valid Bangladesh phone number',
        'phone_number.exists' => 'No account found with this phone number',
        'otp.required' => 'OTP is required',
        'otp.digits' => 'OTP must be 6 digits',
        'password.required' => 'Password is required',
        'password.min' => 'Password must be at least 6 characters',
        'password.confirmed' => 'Passwords do not match',
    ];

    public function sendOtp()
    {
        $this->validate([
            'phone_number' => 'required|regex:/^01[3-9]\d{8}$/|exists:customers,phone_number',
        ]);

        // Generate 6 digit OTP
        $otp = rand(100000, 999999);
        
        // Store OTP in cache for 5 minutes
        $cacheKey = 'password_reset_otp_' . $this->phone_number;
        Cache::put($cacheKey, $otp, now()->addMinutes(5));
        
        // Send OTP via SMS
        try {
            $smsService = new SmsService();
            $fullPhoneNumber = '88' . $this->phone_number;
            $message = "Your password reset OTP is: {$otp}. Valid for 5 minutes. - " . config('app.name');
            
            $smsService->sendSms($fullPhoneNumber, $message);
            
            $this->otpSent = true;
            $this->countdown = 120; // 2 minutes countdown for resend
            $this->step = 2;
            
            session()->flash('success', 'OTP sent to your phone number');
        } catch (\Exception $e) {
            $this->addError('phone_number', 'Failed to send OTP. Please try again.');
        }
    }

    public function resendOtp()
    {
        if ($this->countdown > 0) {
            return;
        }
        
        $this->sendOtp();
    }

    public function resetPassword()
    {
        $this->validate([
            'otp' => 'required|digits:6',
            'password' => 'required|min:6|confirmed',
        ]);

        // Verify OTP
        $cacheKey = 'password_reset_otp_' . $this->phone_number;
        $storedOtp = Cache::get($cacheKey);
        
        if (!$storedOtp || $storedOtp != $this->otp) {
            $this->addError('otp', 'Invalid or expired OTP');
            return;
        }
        
        // Update password
        $customer = Customer::where('phone_number', $this->phone_number)->first();
        
        if ($customer) {
            $customer->password = Hash::make($this->password);
            $customer->save();
            
            // Clear OTP from cache
            Cache::forget($cacheKey);
            
            // Reset form
            $this->reset(['phone_number', 'otp', 'password', 'password_confirmation', 'step', 'otpSent']);
            
            // Close modal and show success
            $this->dispatch('close-modal', name: 'forgot-password-modal');
            $this->dispatch('open-modal', name: 'signin-modal');
            
            session()->flash('success', 'Password reset successfully! Please sign in with your new password.');
        } else {
            $this->addError('phone_number', 'Account not found');
        }
    }

    public function updatedCountdown()
    {
        if ($this->countdown > 0) {
            $this->dispatch('start-countdown');
        }
    }

    public function render()
    {
        return view('livewire.modals.forgot-password');
    }
}
