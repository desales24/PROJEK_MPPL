<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use App\Models\Payment;

/* NOTE: Do Not Remove
/ Livewire asset handling if using sub folder in domain
*/
Livewire::setUpdateRoute(function ($handle) {
    return Route::post(config('app.asset_prefix') . '/livewire/update', $handle);
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get(config('app.asset_prefix') . '/livewire/livewire.js', $handle);
});
/*
/ END
*/
// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/admin/struk/{payment}', function (Payment $payment) {
    $payment->load('order.customer', 'order.orderItems.menu');
    return view('admin.struk', compact('payment'));
})->name('cetak.struk');

Route::get('/', function () {
    return view('components.pages.home');
})->name('home');
Route::get('/about', function () {
    return view('components.pages.about');
})->name('about');
Route::get('/order', function () {
    return view('components.pages.order');
})->name('order');
