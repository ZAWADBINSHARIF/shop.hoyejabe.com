<?php

use App\Livewire\Pages\About;
use App\Livewire\Pages\ContactUs;
use Illuminate\Support\Facades\Route;
use App\Livewire\Pages\Home;
use App\Livewire\Pages\Shop;
use App\Livewire\Pages\SingleProduct;

Route::get('/', Home::class)->name("home");
Route::get('/shop', Shop::class)->name("shop");
Route::get('/about', About::class)->name("about");
Route::get('/contact-us', ContactUs::class)->name("contactUs");
Route::get('/product', SingleProduct::class)->name("product");
