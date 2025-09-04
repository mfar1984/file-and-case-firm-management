<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FileManagementController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\FirmSettingsController;
use App\Http\Controllers\SystemSettingsController;
use App\Http\Controllers\EmailSettingsController;
use App\Http\Controllers\SecuritySettingsController;
use App\Http\Controllers\CaseController;
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

Route::view('/', 'auth.login')->middleware('ddos.protection');

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
Route::get('/case', [CaseController::class, 'index'])->name('case.index');
Route::get('/case/create', [CaseController::class, 'create'])->name('case.create');
Route::post('/case', [CaseController::class, 'store'])->name('case.store');
Route::put('/case/{id}', [CaseController::class, 'update'])->name('case.update');
Route::post('/case/{id}/change-status', [CaseController::class, 'changeStatus'])->name('case.change-status');
Route::post('/case/{id}/timeline', [CaseController::class, 'addTimelineEvent'])->name('case.timeline.store');
Route::put('/case/{id}/timeline/{timelineId}', [CaseController::class, 'updateTimelineEvent'])->name('case.timeline.update');
Route::get('/case/{id}', [CaseController::class, 'show'])->name('case.show');
Route::delete('/case/{id}', [CaseController::class, 'destroy'])->name('case.destroy');
Route::get('/case/{id}/edit', [CaseController::class, 'edit'])->name('case.edit');
Route::get('/client', [App\Http\Controllers\ClientController::class, 'index'])->name('client.index');
Route::get('/client/create', [App\Http\Controllers\ClientController::class, 'create'])->name('client.create');
Route::post('/client', [App\Http\Controllers\ClientController::class, 'store'])->name('client.store');
Route::get('/client/{id}/edit', [App\Http\Controllers\ClientController::class, 'edit'])->name('client.edit');
Route::put('/client/{id}', [App\Http\Controllers\ClientController::class, 'update'])->name('client.update');
Route::get('/client/{id}', [App\Http\Controllers\ClientController::class, 'show'])->name('client.show');
Route::delete('/client/{id}', [App\Http\Controllers\ClientController::class, 'destroy'])->name('client.destroy');
Route::post('/client/{id}/toggle-ban', [App\Http\Controllers\ClientController::class, 'toggleBan'])->name('client.toggle-ban');
Route::get('/partner', [App\Http\Controllers\PartnerController::class, 'index'])->name('partner.index');
Route::get('/partner/create', [App\Http\Controllers\PartnerController::class, 'create'])->name('partner.create');
Route::post('/partner', [App\Http\Controllers\PartnerController::class, 'store'])->name('partner.store');
Route::get('/partner/{id}', [App\Http\Controllers\PartnerController::class, 'show'])->name('partner.show');
Route::get('/partner/{id}/edit', [App\Http\Controllers\PartnerController::class, 'edit'])->name('partner.edit');
Route::put('/partner/{id}', [App\Http\Controllers\PartnerController::class, 'update'])->name('partner.update');
Route::delete('/partner/{id}', [App\Http\Controllers\PartnerController::class, 'destroy'])->name('partner.destroy');
Route::post('/partner/{id}/toggle-ban', [App\Http\Controllers\PartnerController::class, 'toggleBan'])->name('partner.toggle-ban');

// Accounting routes
// Pre-Quotation routes
Route::get('/pre-quotation', [App\Http\Controllers\PreQuotationController::class, 'index'])->name('pre-quotation.index');
Route::get('/pre-quotation/create', [App\Http\Controllers\PreQuotationController::class, 'create'])->name('pre-quotation.create');
Route::post('/pre-quotation', [App\Http\Controllers\PreQuotationController::class, 'store'])->name('pre-quotation.store');
Route::get('/pre-quotation/{id}', [App\Http\Controllers\PreQuotationController::class, 'show'])->name('pre-quotation.show');
Route::get('/pre-quotation/{id}/edit', [App\Http\Controllers\PreQuotationController::class, 'edit'])->name('pre-quotation.edit');
Route::put('/pre-quotation/{id}', [App\Http\Controllers\PreQuotationController::class, 'update'])->name('pre-quotation.update');
Route::delete('/pre-quotation/{id}', [App\Http\Controllers\PreQuotationController::class, 'destroy'])->name('pre-quotation.destroy');

// Quotation routes
Route::get('/quotation', [App\Http\Controllers\QuotationController::class, 'index'])->name('quotation.index');
Route::get('/quotation/create', [App\Http\Controllers\QuotationController::class, 'create'])->name('quotation.create');
Route::post('/quotation', [App\Http\Controllers\QuotationController::class, 'store'])->name('quotation.store');
Route::get('/quotation/{id}', [App\Http\Controllers\QuotationController::class, 'show'])->name('quotation.show');
Route::get('/quotation/{id}/print', [App\Http\Controllers\QuotationController::class, 'print'])->name('quotation.print');
Route::delete('/quotation/{id}', [App\Http\Controllers\QuotationController::class, 'destroy'])->name('quotation.destroy');
Route::patch('/quotation/{id}/accept', [App\Http\Controllers\QuotationController::class, 'accept'])->name('quotation.accept');
Route::patch('/quotation/{id}/reject', [App\Http\Controllers\QuotationController::class, 'reject'])->name('quotation.reject');
Route::patch('/quotation/{id}/cancel', [App\Http\Controllers\QuotationController::class, 'cancel'])->name('quotation.cancel');
Route::patch('/quotation/{id}/reactivate', [App\Http\Controllers\QuotationController::class, 'reactivate'])->name('quotation.reactivate');
Route::get('/tax-invoice', [App\Http\Controllers\TaxInvoiceController::class, 'index'])->name('tax-invoice.index');
Route::get('/tax-invoice/create', [App\Http\Controllers\TaxInvoiceController::class, 'create'])->name('tax-invoice.create');
Route::post('/tax-invoice', [App\Http\Controllers\TaxInvoiceController::class, 'store'])->name('tax-invoice.store');
Route::get('/tax-invoice/{id}', [App\Http\Controllers\TaxInvoiceController::class, 'show'])->name('tax-invoice.show');
Route::get('/tax-invoice/{id}/edit', [App\Http\Controllers\TaxInvoiceController::class, 'edit'])->name('tax-invoice.edit');
Route::put('/tax-invoice/{id}', [App\Http\Controllers\TaxInvoiceController::class, 'update'])->name('tax-invoice.update');
Route::delete('/tax-invoice/{id}', [App\Http\Controllers\TaxInvoiceController::class, 'destroy'])->name('tax-invoice.destroy');
Route::patch('/tax-invoice/{id}/send', [App\Http\Controllers\TaxInvoiceController::class, 'send'])->name('tax-invoice.send');
Route::patch('/tax-invoice/{id}/mark-as-paid', [App\Http\Controllers\TaxInvoiceController::class, 'markAsPaid'])->name('tax-invoice.mark-as-paid');
Route::patch('/tax-invoice/{id}/mark-as-partially-paid', [App\Http\Controllers\TaxInvoiceController::class, 'markAsPartiallyPaid'])->name('tax-invoice.mark-as-partially-paid');
Route::patch('/tax-invoice/{id}/mark-as-overdue', [App\Http\Controllers\TaxInvoiceController::class, 'markAsOverdue'])->name('tax-invoice.mark-as-overdue');
Route::patch('/tax-invoice/{id}/cancel', [App\Http\Controllers\TaxInvoiceController::class, 'cancel'])->name('tax-invoice.cancel');
Route::get('/receipt', [App\Http\Controllers\ReceiptController::class, 'index'])->name('receipt.index');
Route::get('/receipt/create', [App\Http\Controllers\ReceiptController::class, 'create'])->name('receipt.create');
Route::post('/receipt', [App\Http\Controllers\ReceiptController::class, 'store'])->name('receipt.store');
Route::get('/receipt/{id}', [App\Http\Controllers\ReceiptController::class, 'show'])->name('receipt.show');
Route::get('/receipt/{id}/edit', [App\Http\Controllers\ReceiptController::class, 'edit'])->name('receipt.edit');
Route::put('/receipt/{id}', [App\Http\Controllers\ReceiptController::class, 'update'])->name('receipt.update');
Route::delete('/receipt/{id}', [App\Http\Controllers\ReceiptController::class, 'destroy'])->name('receipt.destroy');
Route::view('/voucher', 'voucher')->name('voucher.index');
Route::get('/voucher/create', function() {
    return view('voucher-create');
})->name('voucher.create');
Route::post('/voucher', [App\Http\Controllers\VoucherController::class, 'store'])->name('voucher.store');
Route::view('/bill', 'bill')->name('bill.index');
Route::view('/bill/create', 'bill-create')->name('bill.create');

// Payee Management Routes
Route::get('/settings/payee', [App\Http\Controllers\PayeeController::class, 'index'])->name('payee.index');
Route::post('/settings/payee', [App\Http\Controllers\PayeeController::class, 'store'])->name('payee.store');
Route::put('/settings/payee/{id}', [App\Http\Controllers\PayeeController::class, 'update'])->name('payee.update');
Route::delete('/settings/payee/{id}', [App\Http\Controllers\PayeeController::class, 'destroy'])->name('payee.destroy');
Route::patch('/settings/payee/{id}/toggle-status', [App\Http\Controllers\PayeeController::class, 'toggleStatus'])->name('payee.toggle-status');

Route::view('/settings/global', 'settings.global')->name('settings.global');

// DDoS Protection Routes (Public - No Authentication Required)
Route::get('/ddos/stats', [App\Http\Controllers\DdosConfigController::class, 'getStats'])->name('ddos.stats.public');
Route::get('/ddos/logs', [App\Http\Controllers\DdosConfigController::class, 'getLogs'])->name('ddos.logs.public');
Route::get('/ddos/monitoring', [App\Http\Controllers\DdosConfigController::class, 'getMonitoringData'])->name('ddos.monitoring.public');

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
// Role Management Routes
Route::middleware(['auth', 'permission:manage-roles'])->group(function () {
    Route::get('/settings/role', [App\Http\Controllers\RoleController::class, 'index'])->name('settings.role');
    Route::get('/settings/role/create', [App\Http\Controllers\RoleController::class, 'create'])->name('settings.role.create');
    Route::post('/settings/role', [App\Http\Controllers\RoleController::class, 'store'])->name('settings.role.store');
            Route::get('/settings/role/{id}', [App\Http\Controllers\RoleController::class, 'show'])->name('settings.role.show');
        Route::get('/settings/role/{id}/edit', [App\Http\Controllers\RoleController::class, 'edit'])->name('settings.role.edit');
    Route::put('/settings/role/{id}', [App\Http\Controllers\RoleController::class, 'update'])->name('settings.role.update');
    Route::delete('/settings/role/{id}', [App\Http\Controllers\RoleController::class, 'destroy'])->name('settings.role.destroy');
    Route::get('/settings/role/{id}/users', [App\Http\Controllers\RoleController::class, 'getRoleUsers'])->name('settings.role.users');
    Route::get('/settings/permissions', [App\Http\Controllers\RoleController::class, 'getPermissions'])->name('settings.permissions');
});

// User Management Routes
Route::middleware(['auth', 'permission:manage-users'])->group(function () {
    Route::get('/settings/user', [App\Http\Controllers\Settings\UserController::class, 'index'])->name('settings.user');
    Route::get('/settings/user/create', [App\Http\Controllers\Settings\UserController::class, 'create'])->name('settings.user.create');
    Route::post('/settings/user', [App\Http\Controllers\Settings\UserController::class, 'store'])->name('settings.user.store');
    Route::get('/settings/user/{id}', [App\Http\Controllers\Settings\UserController::class, 'show'])->name('settings.user.show');
    Route::get('/settings/user/{id}/edit', [App\Http\Controllers\Settings\UserController::class, 'edit'])->name('settings.user.edit');
    Route::put('/settings/user/{id}', [App\Http\Controllers\Settings\UserController::class, 'update'])->name('settings.user.update');
    Route::delete('/settings/user/{id}', [App\Http\Controllers\Settings\UserController::class, 'destroy'])->name('settings.user.destroy');
    Route::post('/settings/user/{id}/reset-password', [App\Http\Controllers\Settings\UserController::class, 'resetPassword'])->name('settings.user.reset-password');
    Route::post('/settings/user/{id}/verify-email', [App\Http\Controllers\Settings\UserController::class, 'verifyEmail'])->name('settings.user.verify-email');
});
// Category Management Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/settings/category', [App\Http\Controllers\CategoryController::class, 'index'])->name('settings.category');
    Route::post('/settings/category/type', [App\Http\Controllers\CategoryController::class, 'storeType'])->name('settings.category.type.store');
    Route::put('/settings/category/type/{id}', [App\Http\Controllers\CategoryController::class, 'updateType'])->name('settings.category.type.update');
    Route::delete('/settings/category/type/{id}', [App\Http\Controllers\CategoryController::class, 'destroyType'])->name('settings.category.type.destroy');
    Route::post('/settings/category/status', [App\Http\Controllers\CategoryController::class, 'storeStatus'])->name('settings.category.status.store');
    Route::put('/settings/category/status/{id}', [App\Http\Controllers\CategoryController::class, 'updateStatus'])->name('settings.category.status.update');
    Route::delete('/settings/category/status/{id}', [App\Http\Controllers\CategoryController::class, 'destroyStatus'])->name('settings.category.status.destroy');

    // File Types
    Route::post('/settings/category/file-type', [App\Http\Controllers\CategoryController::class, 'storeFileType'])->name('settings.category.file-type.store');
    Route::put('/settings/category/file-type/{id}', [App\Http\Controllers\CategoryController::class, 'updateFileType'])->name('settings.category.file-type.update');
    Route::delete('/settings/category/file-type/{id}', [App\Http\Controllers\CategoryController::class, 'destroyFileType'])->name('settings.category.file-type.destroy');
    
    // Specializations
    Route::post('/settings/category/specialization', [App\Http\Controllers\CategoryController::class, 'storeSpecialization'])->name('settings.specialization.store');
    Route::put('/settings/category/specialization/{id}', [App\Http\Controllers\CategoryController::class, 'updateSpecialization'])->name('settings.specialization.update');
    Route::delete('/settings/category/specialization/{id}', [App\Http\Controllers\CategoryController::class, 'destroySpecialization'])->name('settings.specialization.destroy');
});
Route::view('/settings/log', 'settings.log')->name('settings.log');
Route::view('/settings/case-management', 'settings.case-management')->name('settings.case-management');

// File Management Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/file-management', [FileManagementController::class, 'index'])->name('file-management.index');
    Route::post('/file-management', [FileManagementController::class, 'store'])->name('file-management.store');
    Route::get('/file-management/download/{id}', [FileManagementController::class, 'download'])->name('file-management.download');
    Route::get('/file-management/view/{hash}', [FileManagementController::class, 'view'])->name('file-management.view');
    Route::patch('/file-management/{id}/status', [FileManagementController::class, 'updateStatus'])->name('file-management.update-status');
    Route::delete('/file-management/{id}', [FileManagementController::class, 'destroy'])->name('file-management.destroy');
    Route::get('/file-management/cases', [FileManagementController::class, 'getCases'])->name('file-management.cases');
    Route::get('/file-management/stats', [FileManagementController::class, 'getStats'])->name('file-management.stats');
});

// Weather API Route
Route::get('/api/weather', [WeatherController::class, 'getWeather'])->name('api.weather');

// Agency Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/settings/agency', [App\Http\Controllers\AgencyController::class, 'index'])->name('settings.agency.index');
    Route::post('/settings/agency', [App\Http\Controllers\AgencyController::class, 'store'])->name('settings.agency.store');
    Route::post('/settings/agency/bulk', [App\Http\Controllers\AgencyController::class, 'bulkStore'])->name('settings.agency.bulk');
    Route::put('/settings/agency/{agency}', [App\Http\Controllers\AgencyController::class, 'update'])->name('settings.agency.update');
    Route::delete('/settings/agency/{agency}', [App\Http\Controllers\AgencyController::class, 'destroy'])->name('settings.agency.destroy');
});

// DDoS Configuration Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/settings/ddos', [App\Http\Controllers\DdosConfigController::class, 'index'])->name('settings.ddos.index');
    Route::post('/settings/ddos', [App\Http\Controllers\DdosConfigController::class, 'store'])->name('settings.ddos.store');
    Route::get('/settings/ddos/stats', [App\Http\Controllers\DdosConfigController::class, 'getStats'])->name('settings.ddos.stats');
    Route::get('/settings/ddos/logs', [App\Http\Controllers\DdosConfigController::class, 'getLogs'])->name('settings.ddos.logs');
    Route::delete('/settings/ddos/logs', [App\Http\Controllers\DdosConfigController::class, 'clearLogs'])->name('settings.ddos.logs.clear');
    Route::get('/settings/system/logs', [App\Http\Controllers\DdosConfigController::class, 'getSystemLogs'])->name('settings.system.logs');
    Route::delete('/settings/system/logs', [App\Http\Controllers\DdosConfigController::class, 'clearSystemLogs'])->name('settings.system.logs.clear');
    Route::get('/settings/monitoring/data', [App\Http\Controllers\DdosConfigController::class, 'getMonitoringData'])->name('settings.monitoring.data');
    Route::post('/settings/ddos/whitelist', [App\Http\Controllers\DdosConfigController::class, 'addWhitelist'])->name('settings.ddos.whitelist.add');
    Route::delete('/settings/ddos/whitelist/{ip}', [App\Http\Controllers\DdosConfigController::class, 'removeWhitelist'])->name('settings.ddos.whitelist.remove');
    Route::post('/settings/ddos/blacklist', [App\Http\Controllers\DdosConfigController::class, 'addBlacklist'])->name('settings.ddos.blacklist.add');
    Route::delete('/settings/ddos/blacklist/{ip}', [App\Http\Controllers\DdosConfigController::class, 'removeBlacklist'])->name('settings.ddos.blacklist.remove');
});

require __DIR__.'/auth.php';
