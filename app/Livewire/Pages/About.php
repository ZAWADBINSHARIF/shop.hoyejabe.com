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
        $this->companyDetails = CompanyDetails::firstOrFail();
        $this->contact = Contact::firstOrFail();
    }

    public function render()
    {
        return view('livewire.pages.about');
    }
}
