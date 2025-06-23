<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;

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

Route::get('/struk/{payment}', function (Payment $payment) {
    return view('admin.struk', compact('payment'));
})->name('cetak.struk');

Route::get('/struk/{payment}/download', function (Payment $payment) {
    $pdf = Pdf::loadView('admin.struk', compact('payment'));
    return $pdf->download('struk_order_' . $payment->order->id . '.pdf');
})->name('cetak.struk.download');

Route::get('/', function () {
    return view('components.pages.home');
})->name('home');
Route::get('/about', function () {
    return view('components.pages.about');
})->name('about');
Route::get('/order', function () {
    return view('components.pages.order');
})->name('order');