<?php

namespace App\Livewire\Pages;

use App\Models\Contact;
use App\Models\CompanyDetails;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class ContactUs extends Component
{
    public $name;
    public $email;
    public $phone;
    public $subject;
    public $message;
    
    public $contact;
    public $companyDetails;
    
    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email',
        'phone' => 'nullable|min:10',
        'subject' => 'nullable|min:3',
        'message' => 'required|min:10',
    ];
    
    public function mount()
    {
        $this->contact = Contact::first();
        $this->companyDetails = CompanyDetails::first();
    }
    
    public function sendMessage()
    {
        $this->validate();
        
        // Here you can implement email sending logic
        // For example, using Laravel's Mail facade
        // Mail::to($this->contact->email)->send(new ContactFormMail($this->name, $this->email, $this->message));
        
        // Show success message
        session()->flash('success', 'Thank you for contacting us! We will get back to you soon.');
        
        // Reset form
        $this->reset(['name', 'email', 'phone', 'subject', 'message']);
    }
    
    public function render()
    {
        return view('livewire.pages.contact-us');
    }
}
