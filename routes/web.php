<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FileManagementController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\FirmSettingsController;
use App\Http\Controllers\SystemSettingsController;
use App\Http\Controllers\EmailSettingsController;
use App\Http\Controllers\SecuritySettingsController;
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

Route::view('/', 'welcome');

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
Route::view('/case/view', 'case-view')->name('case.view');
Route::view('/client', 'client')->name('client.index');
Route::view('/client/create', 'client-create')->name('client.create');
Route::view('/partner', 'partner')->name('partner.index');
Route::view('/partner/create', 'partner-create')->name('partner.create');

// Accounting routes
Route::view('/quotation', 'quotation')->name('quotation.index');
Route::view('/quotation/create', 'quotation-create')->name('quotation.create');
Route::view('/tax-invoice', 'tax-invoice')->name('tax-invoice.index');
Route::view('/tax-invoice/create', 'tax-invoice-create')->name('tax-invoice.create');
Route::view('/resit', 'resit')->name('resit.index');
Route::view('/resit/create', 'resit-create')->name('resit.create');
Route::view('/voucher', 'voucher')->name('voucher.index');
Route::view('/voucher/create', 'voucher-create')->name('voucher.create');
Route::view('/bill', 'bill')->name('bill.index');
Route::view('/bill/create', 'bill-create')->name('bill.create');

Route::view('/settings/global', 'settings.global')->name('settings.global');

// Settings Routes
Route::middleware(['auth'])->group(function () {
    // Firm Settings
    Route::get('/settings/firm/get', [FirmSettingsController::class, 'get'])->name('settings.firm.get');
    Route::post('/settings/firm', [FirmSettingsController::class, 'store'])->name('settings.firm.store');
    
    // System Settings
    Route::get('/settings/system/get', [SystemSettingsController::class, 'get'])->name('settings.system.get');
    Route::post('/settings/system', [SystemSettingsController::class, 'store'])->name('settings.system.store');
    
    // Email Settings
    Route::get('/settings/email/get', [EmailSettingsController::class, 'get'])->name('settings.email.get');
    Route::post('/settings/email', [EmailSettingsController::class, 'store'])->name('settings.email.store');
    
    // Security Settings
    Route::get('/settings/security/get', [SecuritySettingsController::class, 'get'])->name('settings.security.get');
    Route::post('/settings/security', [SecuritySettingsController::class, 'store'])->name('settings.security.store');
    
    // Weather Settings Routes
    Route::get('/settings/weather', [App\Http\Controllers\WeatherSettingsController::class, 'index'])->name('settings.weather');
    Route::post('/settings/weather', [App\Http\Controllers\WeatherSettingsController::class, 'store'])->name('settings.weather.store')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class]);
    Route::post('/settings/weather/test', [App\Http\Controllers\WeatherSettingsController::class, 'testApi'])->name('settings.weather.test')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class]);
    Route::get('/settings/weather/get', [App\Http\Controllers\WeatherSettingsController::class, 'getSettings'])->name('settings.weather.get');
});

// Webhook Routes (CSRF excluded)
Route::post('/webhook/weather', [App\Http\Controllers\WebhookController::class, 'weather'])->name('webhook.weather')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class]);
Route::get('/webhook/test', [App\Http\Controllers\WebhookController::class, 'test'])->name('webhook.test');
Route::get('/webhook/weather/cached', [App\Http\Controllers\WebhookController::class, 'getCachedWeather'])->name('webhook.weather.cached');
Route::view('/settings/role', 'settings.role')->name('settings.role');
Route::view('/settings/user', 'settings.user')->name('settings.user');
Route::view('/settings/category', 'settings.category')->name('settings.category');
Route::view('/settings/log', 'settings.log')->name('settings.log');
Route::view('/settings/case-management', 'settings.case-management')->name('settings.case-management');

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

// Weather API Route
Route::get('/api/weather', [WeatherController::class, 'getWeather'])->name('api.weather');

require __DIR__.'/auth.php';
