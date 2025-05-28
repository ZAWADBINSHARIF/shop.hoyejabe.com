<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'mobile_number',
        'email',
        'facebook',
        'messanger',
        'instagram',
        'whatsapp',
        'office_location'
    ];
}
