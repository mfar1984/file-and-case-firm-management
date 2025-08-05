<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FileManagementController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
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
Route::view('/case/create', 'case-create')->name('case.create');
Route::view('/client', 'client')->name('client.index');
Route::view('/client/create', 'client-create')->name('client.create');
Route::view('/partner', 'partner')->name('partner.index');

// Accounting routes
Route::view('/quotation', 'quotation')->name('quotation.index');
Route::view('/tax-invoice', 'tax-invoice')->name('tax-invoice.index');
Route::view('/resit', 'resit')->name('resit.index');
Route::view('/voucher', 'voucher')->name('voucher.index');
Route::view('/bill', 'bill')->name('bill.index');

Route::view('/settings/global', 'settings.global')->name('settings.global');
Route::view('/settings/role', 'settings.role')->name('settings.role');
Route::view('/settings/user', 'settings.user')->name('settings.user');
Route::view('/settings/category', 'settings.category')->name('settings.category');
Route::view('/settings/log', 'settings.log')->name('settings.log');

// File Management Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/file-management', [FileManagementController::class, 'index'])->name('file-management.index');
    Route::post('/file-management', [FileManagementController::class, 'store'])->name('file-management.store');
    Route::get('/file-management/download/{id}', [FileManagementController::class, 'download'])->name('file-management.download');
    Route::patch('/file-management/{id}/status', [FileManagementController::class, 'updateStatus'])->name('file-management.update-status');
    Route::delete('/file-management/{id}', [FileManagementController::class, 'destroy'])->name('file-management.destroy');
    Route::get('/file-management/cases', [FileManagementController::class, 'getCases'])->name('file-management.cases');
    Route::get('/file-management/stats', [FileManagementController::class, 'getStats'])->name('file-management.stats');
});

require __DIR__.'/auth.php';
