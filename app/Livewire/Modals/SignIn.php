<?php

namespace App\Livewire\Modals;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Component;

class SignIn extends Component
{
    #[Validate('required|regex:/^01[3-9]\d{8}$/')]
    public $phone_number = '';

    #[Validate('required|string|min:6')]
    public $password = '';

    public $remember = false;

    public $loginError = '';

    protected $messages = [
        'phone_number.required' => 'Phone number is required',
        'phone_number.regex' => 'Please enter a valid Bangladesh phone number (01XXXXXXXXX)',
        'password.required' => 'Password is required',
        'password.min' => 'Password must be at least 6 characters',
    ];

    public function login()
    {
        $this->loginError = '';

        $this->validate();

        // Format phone number for database lookup
        $formattedPhone = $this->formatPhoneNumber($this->phone_number);

        // Find customer by phone number
        $customer = Customer::where('phone_number', $this->phone_number)
            ->orWhere('phone_number', $formattedPhone)
            ->first();

        if (!$customer) {
            $this->loginError = 'Invalid phone number or password';
            return;
        }

        // Check password
        if (!Hash::check($this->password, $customer->password)) {
            $this->loginError = 'Invalid phone number or password';
            return;
        }

        // Login the customer
        Auth::guard('customer')->login($customer, $this->remember);

        // Update last login time
        $customer->update(['last_login_at' => now()]);

        // Close modal and redirect
        $this->dispatch('close-modal', name: 'signin-modal');

        // Show success message
        session()->flash('success', 'Welcome back, ' . $customer->full_name . '!');

        // Refresh the page
        return $this->redirect(request()->header('Referer'));
    }

    private function formatPhoneNumber($phone)
    {
        // Remove any non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // If starts with 0, replace with 880
        if (str_starts_with($phone, '0')) {
            return '880' . substr($phone, 1);
        }

        return $phone;
    }

    public function render()
    {
        return view('livewire.modals.sign-in');
    }
}
