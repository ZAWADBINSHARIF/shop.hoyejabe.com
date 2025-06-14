<?php

namespace App\Livewire\Components;

use App\Models\Contact;
use Livewire\Component;

class Footer extends Component
{

    public ?Contact $contact;

    public function mount()
    {
        $this->contact = Contact::first();
    }

    public function render()
    {
        return view('livewire.components.footer');
    }
}
