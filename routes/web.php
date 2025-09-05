<?php

use App\Livewire\Pages\About;
use App\Livewire\Pages\ContactUs;
use Illuminate\Support\Facades\Route;
use App\Livewire\Pages\Home;
use App\Livewire\Pages\MyOrder;
use App\Livewire\Pages\Shop;
use App\Livewire\Pages\SingleProduct;
use App\Livewire\Pages\TrackOrder;

Route::get('/', Home::class)->name("home");
Route::get('/shop', Shop::class)->name("shop");
Route::get('/shop/{product_slug}', SingleProduct::class)->name("shop.product");
Route::get('/about', About::class)->name("about");
Route::get('/contact-us', ContactUs::class)->name("contact-us");
Route::get('/product', SingleProduct::class)->name("product");
Route::get('/track-order', TrackOrder::class)->name("track-order");
Route::get('/my-order', MyOrder::class)->name("my-order");
