<?php

namespace App\Livewire\Components;

use App\Models\CompanyDetails;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Navbar extends Component
{

    public $companyDetails;
    public $contact;
    public $logo = [
        'width' => 48,
        'height' => 48
    ];
    public $customer;
    public $showLogoutConfirmation = false;


    public function mount()
    {
        $this->companyDetails = CompanyDetails::first();
        $this->contact = Contact::first();
        $this->customer = Auth::guard('customer')->user();

        if ($this->companyDetails?->width) {
            $this->logo['width'] = $this->companyDetails->width;
        }
        if ($this->companyDetails?->height) {
            $this->logo['height'] = $this->companyDetails->height;
        }
    }

    public function logout()
    {
        $this->showLogoutConfirmation = false;

        Auth::guard('customer')->logout();
        session()->invalidate();
        session()->regenerateToken();

        session()->flash('success', 'You have been logged out successfully.');

        // Refresh the page
        return $this->redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('livewire.components.navbar');
    }
}
