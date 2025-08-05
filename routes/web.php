<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('overview');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Dummy routes for sidebar
Route::view('/overview', 'overview')->name('overview');
Route::view('/calendar', 'calendar')->name('calendar');
Route::view('/case', 'case')->name('case.index');
Route::view('/client', 'client')->name('client.index');
Route::view('/partner', 'partner')->name('partner.index');
Route::view('/file-management', 'file-management')->name('file-management.index');
Route::view('/settings/global', 'settings.global')->name('settings.global');
Route::view('/settings/role', 'settings.role')->name('settings.role');
Route::view('/settings/user', 'settings.user')->name('settings.user');
Route::view('/settings/log', 'settings.log')->name('settings.log');

require __DIR__.'/auth.php';
