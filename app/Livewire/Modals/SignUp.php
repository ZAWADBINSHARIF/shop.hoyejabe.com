<?php

namespace App\Livewire\Modals;

use App\Models\Customer;
use App\Services\SmsService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Component;

class SignUp extends Component
{
    #[Validate('required|string|min:3|max:255')]
    public $full_name = '';

    #[Validate('required|regex:/^01[3-9]\d{8}$/')]
    public $phone_number = '';

    #[Validate('required|string|size:6')]
    public $otp = '';

    #[Validate('required|string|min:6')]
    public $password = '';

    #[Validate('required|string|same:password')]
    public $password_confirmation = '';

    public $otpSent = false;
    public $otpMessage = '';
    public $otpError = '';
    public $signupError = '';
    public $signupSuccess = false;

    protected $messages = [
        'phone_number.regex' => 'Please enter a valid Bangladesh phone number (01XXXXXXXXX)',
        'otp.size' => 'OTP must be exactly 6 digits',
        'password_confirmation.same' => 'Password confirmation does not match',
    ];

    public function sendOtp()
    {
        // Reset messages
        $this->otpMessage = '';
        $this->otpError = '';
        
        // Validate name and phone before sending OTP
        $this->validate([
            'full_name' => 'required|string|min:3|max:255',
            'phone_number' => 'required|regex:/^01[3-9]\d{8}$/',
        ]);

        try {
            // Check if customer already exists with this phone
            $existingCustomer = Customer::where('phone_number', $this->phone_number)->first();
            if ($existingCustomer) {
                $this->otpError = 'An account already exists with this phone number.';
                return;
            }

            // Generate OTP
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // Store OTP in cache for 5 minutes
            $cacheKey = 'signup_otp_' . $this->phone_number;
            cache()->put($cacheKey, [
                'otp' => $otp,
                'full_name' => $this->full_name,
                'phone_number' => $this->phone_number,
                'attempts' => 0,
                'created_at' => now()
            ], now()->addMinutes(5));

            // Send OTP via SMS
            $smsService = new SmsService();
            $formattedPhone = $this->formatPhoneNumber($this->phone_number);
            
            $message = "Welcome to " . config('app.name') . "! Your signup OTP is: {$otp}. Valid for 5 minutes. Do not share with anyone.";
            
            $sent = $smsService->sendSms($formattedPhone, $message);
            
            if ($sent) {
                $this->otpSent = true;
                $this->otpMessage = 'OTP has been sent to your phone number. Please check your SMS.';
                
                Log::info('Signup OTP sent', [
                    'phone' => $this->phone_number,
                    'name' => $this->full_name
                ]);
            } else {
                $this->otpError = 'Failed to send OTP. Please try again.';
            }
            
        } catch (\Exception $e) {
            Log::error('Failed to send signup OTP', [
                'phone' => $this->phone_number,
                'error' => $e->getMessage()
            ]);
            $this->otpError = 'Failed to send OTP. Please try again later.';
        }
    }

    public function resendOtp()
    {
        // Add cooldown check
        $cacheKey = 'signup_otp_cooldown_' . $this->phone_number;
        if (cache()->has($cacheKey)) {
            $this->otpError = 'Please wait 60 seconds before requesting a new OTP.';
            return;
        }
        
        // Set cooldown
        cache()->put($cacheKey, true, now()->addSeconds(60));
        
        // Send new OTP
        $this->sendOtp();
    }

    public function signup()
    {
        // Reset messages
        $this->signupError = '';
        
        // Validate all fields
        $this->validate();

        try {
            // Verify OTP
            $cacheKey = 'signup_otp_' . $this->phone_number;
            $cachedData = cache()->get($cacheKey);
            
            if (!$cachedData) {
                $this->signupError = 'OTP has expired. Please request a new one.';
                return;
            }
            
            // Check OTP attempts
            if ($cachedData['attempts'] >= 3) {
                $this->signupError = 'Too many failed attempts. Please request a new OTP.';
                cache()->forget($cacheKey);
                return;
            }
            
            // Verify OTP
            if ($cachedData['otp'] !== $this->otp) {
                // Increment attempts
                $cachedData['attempts']++;
                cache()->put($cacheKey, $cachedData, now()->addMinutes(5));
                
                $this->signupError = 'Invalid OTP. Please check and try again.';
                return;
            }
            
            // Check if data matches
            if ($cachedData['full_name'] !== $this->full_name || 
                $cachedData['phone_number'] !== $this->phone_number) {
                $this->signupError = 'Data mismatch. Please request a new OTP.';
                return;
            }
            
            // Create customer account
            $customer = Customer::create([
                'full_name' => $this->full_name,
                'phone_number' => $this->phone_number,
                'password' => Hash::make($this->password),
            ]);
            
            // Clear OTP cache
            cache()->forget($cacheKey);
            cache()->forget('signup_otp_cooldown_' . $this->phone_number);
            
            // Log the customer in
            auth('customer')->login($customer);
            
            $this->signupSuccess = true;
            
            // Send welcome SMS
            try {
                $smsService = new SmsService();
                $welcomeMessage = "Welcome to " . config('app.name') . ", {$this->full_name}! Your account has been created successfully. Happy shopping!";
                $smsService->sendSms($this->formatPhoneNumber($this->phone_number), $welcomeMessage);
            } catch (\Exception $e) {
                Log::error('Failed to send welcome SMS', ['error' => $e->getMessage()]);
            }
            
            // Dispatch success event and close modal
            $this->dispatch('customer-signed-up');
            $this->dispatch('close-modal', 'signup-modal');
            
            // Reset form
            $this->reset();
            
            // Redirect or refresh
            return redirect()->route('home')->with('success', 'Account created successfully!');
            
        } catch (\Exception $e) {
            Log::error('Signup failed', [
                'phone' => $this->phone_number,
                'error' => $e->getMessage()
            ]);
            $this->signupError = 'Failed to create account. Please try again.';
        }
    }

    protected function formatPhoneNumber($phone)
    {
        // Remove any non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // If starts with 01, replace with 8801
        if (str_starts_with($phone, '01')) {
            return '880' . substr($phone, 1);
        }
        
        return $phone;
    }

    public function render()
    {
        return view('livewire.modals.sign-up');
    }
}