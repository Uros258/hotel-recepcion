<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RezervacijaController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\SobaController;

Route::get('/dashboard', fn () => redirect()->route('home'))
    ->middleware(['auth','verified'])
    ->name('dashboard');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::resource('sobas', SobaController::class)->only(['index','show']);

Route::middleware('auth')->group(function () {
    Route::resource('rezervacijas', RezervacijaController::class); 
    Route::get('/moje-rezervacije', [RezervacijaController::class, 'myReservations'])->name('rezervacije.my');
    Route::post('/rezervacije/{rezervacija}/otkazi', [RezervacijaController::class, 'cancel'])->name('rezervacije.cancel');
});

Route::middleware(['auth','role:recepcioner'])->group(function () {
    Route::post('/rezervacije/{rezervacija}/status',   [RezervacijaController::class, 'changeStatus'])->name('rezervacije.status');
    Route::post('/rezervacije/{rezervacija}/checkin',  [RezervacijaController::class, 'checkin'])->name('rezervacije.checkin');
    Route::post('/rezervacije/{rezervacija}/checkout', [RezervacijaController::class, 'checkout'])->name('rezervacije.checkout');
});

Route::resource('roles', RoleController::class)->middleware(['auth','role:admin']);
Route::resource('statuses', StatusController::class)->middleware(['auth','role:admin']);

require __DIR__.'/auth.php';
