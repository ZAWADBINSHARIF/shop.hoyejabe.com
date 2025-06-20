<?php

namespace App\Livewire\Components;

use App\Models\CompanyDetails;
use App\Models\Contact;
use Livewire\Component;

class Navbar extends Component
{

    public $companyDetails;
    public $contact;
    public $logo = [
        'width' => 48,
        'height' => 48
    ];


    public function mount()
    {
        $this->companyDetails = CompanyDetails::first();
        $this->contact = Contact::first();

        if ($this->companyDetails->width) {
            $this->logo['width'] = $this->companyDetails->width;
        }
        if ($this->companyDetails->height) {
            $this->logo['height'] = $this->companyDetails->height;
        }
    }

    public function render()
    {
        return view('livewire.components.navbar');
    }
}
