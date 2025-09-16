<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RezervacijaController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\SobaController;
use App\Http\Controllers\ProfileController;

Route::get('/dashboard', fn () => redirect()->route('home'))
    ->middleware(['auth','verified'])
    ->name('dashboard');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::resource('sobas', SobaController::class)->only(['index','show']);

Route::middleware('auth')->group(function () {
    Route::resource('rezervacijas', RezervacijaController::class);

    Route::get('/moje-rezervacije', [RezervacijaController::class, 'myReservations'])
        ->name('rezervacijas.my');
    Route::post('/rezervacije/{rezervacija}/otkazi', [RezervacijaController::class, 'cancel'])
        ->name('rezervacijas.cancel');

    Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',[ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',[ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth','role:recepcioner,menadzer'])
    ->prefix('recepcija')
    ->name('recepcija.')
    ->group(function () {
        Route::get('/', [RezervacijaController::class, 'index'])->name('index');

        Route::get('/rezervacije/kreiraj', [RezervacijaController::class, 'createByStaff'])
            ->name('rezervacije.create');

        Route::post('/rezervacije', [RezervacijaController::class, 'storeByStaff'])
            ->name('rezervacije.store');

        Route::get('/rezervacije/{rezervacija}/dodeli-sobu', [RezervacijaController::class, 'assignRoomForm'])
            ->name('rezervacije.assign_form');
        Route::post('/rezervacije/{rezervacija}/dodeli-sobu', [RezervacijaController::class, 'assignRoom'])
            ->name('rezervacije.assign');
        Route::post('/rezervacije/pick', [RezervacijaController::class, 'pickRoom'])
            ->name('rezervacije.pick');



    });

Route::middleware(['auth','role:recepcioner'])->group(function () {
    Route::post('/rezervacije/{rezervacija}/status',   [RezervacijaController::class, 'changeStatus'])->name('rezervacijas.status');
    Route::post('/rezervacije/{rezervacija}/checkin',  [RezervacijaController::class, 'checkin'])->name('rezervacijas.checkin');
    Route::post('/rezervacije/{rezervacija}/checkout', [RezervacijaController::class, 'checkout'])->name('rezervacijas.checkout');
});

Route::middleware(['auth','role:menadzer,admin'])->group(function () {
    Route::get('/menadzer/sobe',          [SobaController::class,'indexManager'])->name('menadzer.sobe.index');
    Route::get('/menadzer/sobe/izmeni',   [SobaController::class,'editManager'])->name('menadzer.sobe.edit');
    Route::post('/menadzer/sobe/sacuvaj', [SobaController::class,'updateManager'])->name('menadzer.sobe.update');
});

Route::resource('roles',    RoleController::class)->middleware(['auth','role:admin']);
Route::resource('statuses', StatusController::class)->middleware(['auth','role:admin']);

require __DIR__.'/auth.php';
