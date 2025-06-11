<?php

namespace App\Livewire\Components;

use App\Models\CompanyDetails;
use App\Models\Contact;
use Livewire\Component;

class Navbar extends Component
{

    public $companyDetails;
    public $contact;

    public function mount()
    {
        $this->companyDetails = CompanyDetails::firstOrFail();
        $this->contact = Contact::firstOrFail();
    }

    public function render()
    {
        return view('livewire.components.navbar');
    }
}
