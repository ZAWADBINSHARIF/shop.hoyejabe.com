<?php

namespace App\Livewire\Pages;

use App\Models\CompanyDetails;
use App\Models\Contact;
use Livewire\Component;

class About extends Component
{

    public $companyDetails;
    public $contact;

    public function mount()
    {
        $this->companyDetails = CompanyDetails::first();
        $this->contact = Contact::first();
    }

    public function render()
    {
        return view('livewire.pages.about');
    }
}
