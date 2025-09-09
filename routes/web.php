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

Route::view('/', 'auth.login');

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

// Calendar routes
Route::get('/calendar', [App\Http\Controllers\CalendarController::class, 'index'])->name('calendar');
Route::get('/calendar/events', [App\Http\Controllers\CalendarController::class, 'getEvents'])->name('calendar.events');
Route::post('/calendar/events', [App\Http\Controllers\CalendarController::class, 'store'])->name('calendar.store');
Route::put('/calendar/events/{event}', [App\Http\Controllers\CalendarController::class, 'update'])->name('calendar.update');
Route::delete('/calendar/events/{event}', [App\Http\Controllers\CalendarController::class, 'destroy'])->name('calendar.destroy');
Route::get('/case', [CaseController::class, 'index'])->name('case.index');
Route::get('/case/create', [CaseController::class, 'create'])->name('case.create');
Route::post('/case', [CaseController::class, 'store'])->name('case.store');
Route::put('/case/{id}', [CaseController::class, 'update'])->name('case.update');
Route::post('/case/{id}/change-status', [CaseController::class, 'changeStatus'])->name('case.change-status');
Route::post('/case/{id}/timeline', [CaseController::class, 'addTimelineEvent'])->name('case.timeline.store');
Route::put('/case/{id}/timeline/{timelineId}', [CaseController::class, 'updateTimelineEvent'])->name('case.timeline.update');
Route::delete('/case/{id}/timeline/{timelineId}', [CaseController::class, 'deleteTimelineEvent'])->name('case.timeline.destroy');
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
Route::get('/pre-quotation/{id}/print', [App\Http\Controllers\PreQuotationController::class, 'print'])->name('pre-quotation.print');
Route::get('/pre-quotation/{id}/edit', [App\Http\Controllers\PreQuotationController::class, 'edit'])->name('pre-quotation.edit');
Route::put('/pre-quotation/{id}', [App\Http\Controllers\PreQuotationController::class, 'update'])->name('pre-quotation.update');
Route::patch('/pre-quotation/{id}/status', [App\Http\Controllers\PreQuotationController::class, 'updateStatus'])->name('pre-quotation.updateStatus');
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
Route::get('/tax-invoice/{id}/print', [App\Http\Controllers\TaxInvoiceController::class, 'print'])->name('tax-invoice.print');
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
Route::get('/receipt/{id}/print', [App\Http\Controllers\ReceiptController::class, 'print'])->name('receipt.print');
Route::get('/receipt/{id}/edit', [App\Http\Controllers\ReceiptController::class, 'edit'])->name('receipt.edit');
Route::put('/receipt/{id}', [App\Http\Controllers\ReceiptController::class, 'update'])->name('receipt.update');
Route::delete('/receipt/{id}', [App\Http\Controllers\ReceiptController::class, 'destroy'])->name('receipt.destroy');
// Voucher Management Routes
Route::get('/voucher', [App\Http\Controllers\VoucherController::class, 'index'])->name('voucher.index');
Route::get('/voucher/create', [App\Http\Controllers\VoucherController::class, 'create'])->name('voucher.create');
Route::post('/voucher', [App\Http\Controllers\VoucherController::class, 'store'])->name('voucher.store');
Route::get('/voucher/{id}', [App\Http\Controllers\VoucherController::class, 'show'])->name('voucher.show');
Route::get('/voucher/{id}/print', [App\Http\Controllers\VoucherController::class, 'print'])->name('voucher.print');
Route::get('/voucher/{id}/edit', [App\Http\Controllers\VoucherController::class, 'edit'])->name('voucher.edit');
Route::put('/voucher/{id}', [App\Http\Controllers\VoucherController::class, 'update'])->name('voucher.update');
Route::patch('/voucher/{id}/status', [App\Http\Controllers\VoucherController::class, 'updateStatus'])->name('voucher.updateStatus');
Route::delete('/voucher/{id}', [App\Http\Controllers\VoucherController::class, 'destroy'])->name('voucher.destroy');

// Bill Management Routes
Route::get('/bill', [App\Http\Controllers\BillController::class, 'index'])->name('bill.index');
Route::get('/bill/create', [App\Http\Controllers\BillController::class, 'create'])->name('bill.create');
Route::post('/bill', [App\Http\Controllers\BillController::class, 'store'])->name('bill.store');
Route::get('/bill/{id}', [App\Http\Controllers\BillController::class, 'show'])->name('bill.show');
Route::get('/bill/{id}/print', [App\Http\Controllers\BillController::class, 'print'])->name('bill.print');
Route::get('/bill/{id}/edit', [App\Http\Controllers\BillController::class, 'edit'])->name('bill.edit');
Route::put('/bill/{id}', [App\Http\Controllers\BillController::class, 'update'])->name('bill.update');
Route::patch('/bill/{id}/status', [App\Http\Controllers\BillController::class, 'updateStatus'])->name('bill.updateStatus');
Route::delete('/bill/{id}', [App\Http\Controllers\BillController::class, 'destroy'])->name('bill.destroy');

// Payee Management Routes
Route::get('/settings/payee', [App\Http\Controllers\PayeeController::class, 'index'])->name('payee.index');
Route::post('/settings/payee', [App\Http\Controllers\PayeeController::class, 'store'])->name('payee.store');
Route::put('/settings/payee/{id}', [App\Http\Controllers\PayeeController::class, 'update'])->name('payee.update');
Route::delete('/settings/payee/{id}', [App\Http\Controllers\PayeeController::class, 'destroy'])->name('payee.destroy');
Route::patch('/settings/payee/{id}/toggle-status', [App\Http\Controllers\PayeeController::class, 'toggleStatus'])->name('payee.toggle-status');

Route::view('/settings/global', 'settings.global')->name('settings.global');

// Opening Balance Routes
Route::get('/settings/opening-balance', [App\Http\Controllers\OpeningBalanceController::class, 'index'])->name('opening-balance.index');
Route::post('/settings/opening-balance', [App\Http\Controllers\OpeningBalanceController::class, 'store'])->name('opening-balance.store');
Route::put('/settings/opening-balance/{id}', [App\Http\Controllers\OpeningBalanceController::class, 'update'])->name('opening-balance.update');
Route::delete('/settings/opening-balance/{id}', [App\Http\Controllers\OpeningBalanceController::class, 'destroy'])->name('opening-balance.destroy');

// G. Ledger Routes
Route::get('/general-ledger', [App\Http\Controllers\GeneralLedgerController::class, 'index'])->name('general-ledger.index');
Route::get('/general-ledger/print', [App\Http\Controllers\GeneralLedgerController::class, 'print'])->name('general-ledger.print');
Route::get('/detail-transaction', [App\Http\Controllers\DetailTransactionController::class, 'index'])->name('detail-transaction.index');
Route::get('/detail-transaction/print', [App\Http\Controllers\DetailTransactionController::class, 'print'])->name('detail-transaction.print');
Route::get('/journal-report', [App\Http\Controllers\JournalReportController::class, 'index'])->name('journal-report.index');
Route::get('/journal-report/print', [App\Http\Controllers\JournalReportController::class, 'print'])->name('journal-report.print');
Route::get('/balance-sheet', [App\Http\Controllers\BalanceSheetController::class, 'index'])->name('balance-sheet.index');
Route::get('/profit-loss', [App\Http\Controllers\ProfitLossController::class, 'index'])->name('profit-loss.index');
Route::get('/profit-loss/print', [App\Http\Controllers\ProfitLossController::class, 'print'])->name('profit-loss.print');
Route::get('/trial-balance', [App\Http\Controllers\TrialBalanceController::class, 'index'])->name('trial-balance.index');
Route::get('/trial-balance/print', [App\Http\Controllers\TrialBalanceController::class, 'print'])->name('trial-balance.print');



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

    // Event Statuses
    Route::post('/settings/category/event-status', [App\Http\Controllers\CategoryController::class, 'storeEventStatus'])->name('settings.category.event-status.store');
    Route::put('/settings/category/event-status/{id}', [App\Http\Controllers\CategoryController::class, 'updateEventStatus'])->name('settings.category.event-status.update');
    Route::delete('/settings/category/event-status/{id}', [App\Http\Controllers\CategoryController::class, 'destroyEventStatus'])->name('settings.category.event-status.destroy');

    // Expense Categories
    Route::post('/settings/category/expense-category', [App\Http\Controllers\CategoryController::class, 'storeExpenseCategory'])->name('settings.category.expense-category.store');
    Route::put('/settings/category/expense-category/{expenseCategory}', [App\Http\Controllers\CategoryController::class, 'updateExpenseCategory'])->name('settings.category.expense-category.update');
    Route::delete('/settings/category/expense-category/{expenseCategory}', [App\Http\Controllers\CategoryController::class, 'destroyExpenseCategory'])->name('settings.category.expense-category.destroy');
});
Route::get('/settings/log', [App\Http\Controllers\ActivityLogController::class, 'index'])->name('settings.log');
Route::get('/settings/log/data', [App\Http\Controllers\ActivityLogController::class, 'getLogs'])->name('settings.log.data');
Route::delete('/settings/log/clear', [App\Http\Controllers\ActivityLogController::class, 'clearLogs'])->name('settings.log.clear');


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



require __DIR__.'/auth.php';
