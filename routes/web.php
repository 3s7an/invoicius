<?php

use App\Http\Controllers\AutomatizationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipientController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices');
    Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/invoices/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::put('/invoices/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
    Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
    Route::patch('/invoices/{invoice}/status', [InvoiceController::class, 'updateStatus'])->name('invoices.update-status');
    Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('recipients', RecipientController::class);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('automatizations', AutomatizationController::class)
        ->only(['store', 'update', 'destroy']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/details', [ProfileController::class, 'updateDetails'])->name('profile.details.update');
    Route::patch('/profile/invoice-settings', [ProfileController::class, 'updateInvoiceSettings'])->name('profile.invoice-settings.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
