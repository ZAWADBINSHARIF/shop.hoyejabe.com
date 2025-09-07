<?php

namespace App\Livewire\Components;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ProfileSlider extends Component
{
    // Personal Information
    #[Validate('required|string|min:3|max:255')]
    public $full_name = '';
    
    #[Validate('required|regex:/^01[3-9]\d{8}$/')]
    public $phone_number = '';
    
    #[Validate('nullable|email|max:255')]
    public $email = '';
    
    // Address Information
    #[Validate('nullable|string|max:255')]
    public $city = '';
    
    #[Validate('nullable|string|max:255')]
    public $upazila = '';
    
    #[Validate('nullable|string|max:255')]
    public $thana = '';
    
    #[Validate('nullable|string|max:20')]
    public $post_code = '';
    
    #[Validate('nullable|string|max:500')]
    public $address = '';
    
    // Password Change
    public $current_password = '';
    public $new_password = '';
    public $new_password_confirmation = '';
    
    // Customer model
    public $customer;
    
    // Success/Error messages
    public $personalInfoMessage = '';
    public $addressMessage = '';
    public $passwordMessage = '';
    public $passwordError = '';
    
    protected $messages = [
        'full_name.required' => 'Full name is required',
        'full_name.min' => 'Full name must be at least 3 characters',
        'phone_number.required' => 'Phone number is required',
        'phone_number.regex' => 'Please enter a valid Bangladesh phone number',
        'email.email' => 'Please enter a valid email address',
    ];
    
    public function mount()
    {
        $this->loadCustomerData();
    }
    
    public function loadCustomerData()
    {
        $this->customer = Auth::guard('customer')->user();
        
        if ($this->customer) {
            // Personal Information
            $this->full_name = $this->customer->full_name ?? '';
            $this->phone_number = $this->customer->phone_number ?? '';
            $this->email = $this->customer->email ?? '';
            
            // Address Information
            $this->city = $this->customer->city ?? '';
            $this->upazila = $this->customer->upazila ?? '';
            $this->thana = $this->customer->thana ?? '';
            $this->post_code = $this->customer->post_code ?? '';
            $this->address = $this->customer->address ?? '';
        }
    }
    
    public function updatePersonalInfo()
    {
        $this->personalInfoMessage = '';
        
        $this->validate([
            'full_name' => 'required|string|min:3|max:255',
            'phone_number' => 'required|regex:/^01[3-9]\d{8}$/',
            'email' => 'nullable|email|max:255',
        ]);
        
        if (!$this->customer) {
            $this->personalInfoMessage = 'Please login to update your profile';
            return;
        }
        
        // Check if phone number is already taken by another customer
        $phoneExists = Customer::where('phone_number', $this->phone_number)
                               ->where('id', '!=', $this->customer->id)
                               ->exists();
        
        if ($phoneExists) {
            $this->addError('phone_number', 'This phone number is already registered');
            return;
        }
        
        // Check if email is already taken
        if ($this->email) {
            $emailExists = Customer::where('email', $this->email)
                                  ->where('id', '!=', $this->customer->id)
                                  ->exists();
            
            if ($emailExists) {
                $this->addError('email', 'This email is already registered');
                return;
            }
        }
        
        $this->customer->update([
            'full_name' => $this->full_name,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
        ]);
        
        $this->personalInfoMessage = 'Personal information updated successfully!';
        $this->dispatch('profile-updated');
    }
    
    public function updateAddress()
    {
        $this->addressMessage = '';
        
        $this->validate([
            'city' => 'nullable|string|max:255',
            'upazila' => 'nullable|string|max:255',
            'thana' => 'nullable|string|max:255',
            'post_code' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);
        
        if (!$this->customer) {
            $this->addressMessage = 'Please login to update your address';
            return;
        }
        
        $this->customer->update([
            'city' => $this->city,
            'upazila' => $this->upazila,
            'thana' => $this->thana,
            'post_code' => $this->post_code,
            'address' => $this->address,
        ]);
        
        $this->addressMessage = 'Address information updated successfully!';
        $this->dispatch('address-updated');
    }
    
    public function updatePassword()
    {
        $this->passwordMessage = '';
        $this->passwordError = '';
        
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Current password is required',
            'new_password.required' => 'New password is required',
            'new_password.min' => 'New password must be at least 6 characters',
            'new_password.confirmed' => 'New password confirmation does not match',
        ]);
        
        if (!$this->customer) {
            $this->passwordError = 'Please login to change your password';
            return;
        }
        
        // Verify current password
        if (!Hash::check($this->current_password, $this->customer->password)) {
            $this->passwordError = 'Current password is incorrect';
            return;
        }
        
        // Update password
        $this->customer->update([
            'password' => Hash::make($this->new_password),
        ]);
        
        // Clear password fields
        $this->current_password = '';
        $this->new_password = '';
        $this->new_password_confirmation = '';
        
        $this->passwordMessage = 'Password changed successfully!';
        $this->dispatch('password-updated');
    }
    
    public function render()
    {
        return view('livewire.components.profile-slider');
    }
}
