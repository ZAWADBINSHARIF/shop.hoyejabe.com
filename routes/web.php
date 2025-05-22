<?php

use App\Livewire\Pages\About;
use App\Livewire\Pages\ContactUs;
use Illuminate\Support\Facades\Route;
use App\Livewire\Pages\Home;
use App\Livewire\Pages\Shop;

Route::get('/', Home::class);
Route::get('/shop', Shop::class);
Route::get('/about', About::class);
Route::get('/contact-us', ContactUs::class);
