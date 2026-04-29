<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsultantController;
use App\Http\Controllers\ContactStageController;
use App\Http\Controllers\LeadSourceController;
use App\Http\Controllers\CollegeController;
use App\Http\Controllers\CommissionRuleController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\QualificationController;
use App\Http\Controllers\IntakeController;
use App\Http\Controllers\PriorityController;
use App\Http\Controllers\DocumentSettingController;
use App\Http\Controllers\CommunicationLogController;
use App\Http\Controllers\CommissionPaymentController;
use App\Http\Controllers\ConsultantPaymentRequestController;
use App\Models\LeadSource;
use App\Http\Controllers\SlabRuleController;
use App\Http\Controllers\SecuritySettingsController;
use App\Http\Controllers\ProfileSettingsController;
use App\Http\Controllers\LocalizationSettingController;
use App\Http\Controllers\LanguageSettingController;
use App\Http\Controllers\InvoiceSettingController;
use App\Http\Controllers\EmailSettingController;
use App\Http\Controllers\GdprCookieController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\CurrencyController;

// auth pages

Route::get('/', [AuthController::class, 'login'])->name('login');

Route::get('/auth/login', [AuthController::class, 'login']);

Route::post('/login', [AuthController::class, 'authenticate'])->name('login.submit');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/forgot-password', function () {
    return view('pages.auth.forgot-password');
})->name('forgot-password');


Route::get('/dashboard', function () {
    return view('pages.dashboard');
})->middleware(middleware: 'auth')->name('dashboard');


// leads
// routes/web.php

Route::middleware('auth')->group(function () {
    // Basic CRUD
    Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
    Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');
    Route::get('/lead-details/{lead}', [LeadController::class, 'show'])->name('lead-details');
    Route::put('/leads/{lead}', [LeadController::class, 'update'])->name('leads.update');
    Route::delete('/leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');
    // Bulk Lead Import Routes
    Route::get('/leads/bulk-import', [LeadController::class, 'showBulkImport'])->name('leads.bulk.import');
    Route::post('/leads/bulk-import', [LeadController::class, 'processBulkImport'])->name('leads.bulk.process');
    Route::get('/leads/sample-csv', [LeadController::class, 'downloadSampleCsv'])->name('leads.sample.csv');
    // ✅ Export Routes
    Route::get('/leads/export/excel', [LeadController::class, 'exportExcel'])->name('leads.export.excel');
    Route::get('/leads/export/pdf', [LeadController::class, 'exportPdf'])->name('leads.export.pdf');
    // Feature Routes (All POST for simplicity)
    Route::post('/lead/{lead}/update-stage', [LeadController::class, 'updateStage'])->name('leads.update-stage');
    Route::post('/leads/{lead}/assign-consultant', [LeadController::class, 'assignCounsellor'])
        ->name('leads.assign-consultant');
    Route::post('/lead/{lead}/upload-document', [LeadController::class, 'uploadDocument'])->name('leads.upload-document');
    Route::post('/lead/{lead}/add-communication', [LeadController::class, 'addCommunication'])->name('leads.add-communication');
    Route::post('/lead/{lead}/create-admission', [LeadController::class, 'createAdmissionRequest'])->name('leads.create-admission');
    Route::post('/leads/{lead}/admission-request', [LeadController::class, 'createAdmissionRequest'])
        ->name('leads.admission-request');

    Route::put('/admission-requests/{admission}/status', [LeadController::class, 'updateAdmissionStatus'])
        ->name('admission-requests.update-status');

    Route::post('/leads/{lead}/documents/{document}/verify', [LeadController::class, 'verifyDocument'])
        ->name('leads.verify-document');

    Route::post('/leads/{lead}/documents/{document}/reject', [LeadController::class, 'rejectDocument'])
        ->name('leads.reject-document');

    Route::post('/leads/{lead}/send-message', [LeadController::class, 'sendMessage'])
        ->name('leads.send-message')
        ->middleware('auth');


    Route::delete('/leads/documents/{document}', [LeadController::class, 'deleteDocument'])
        ->name('leads.delete-document');

    Route::delete('/admission-requests/{admissionRequest}', [LeadController::class, 'deleteAdmissionRequest'])
        ->name('admission-requests.destroy');
    // Delete routes
    Route::delete('/document/{document}', [LeadController::class, 'deleteDocument'])->name('leads.delete-document');
    Route::delete('/communication/{communication}', [LeadController::class, 'deleteCommunication'])->name('leads.delete-communication');
    Route::delete('/admission/{admission}', [LeadController::class, 'deleteAdmissionRequest'])->name('leads.delete-admission');

    // API
    Route::get('/leads/cities/{state}', [LeadController::class, 'getCitiesByState'])->name('leads.cities.by-state');
});

// colleges
// Route::get('/colleges', function () {
//     return view('pages.colleges.index');
// })->name('colleges');

// Simple routes without any prefix or name grouping
Route::get('/colleges', [CollegeController::class, 'index'])->middleware(middleware: 'auth')->name('colleges.index');
Route::post('/colleges', [CollegeController::class, 'store'])->middleware(middleware: 'auth')->name('colleges.store');
Route::put('/colleges/{college}', [CollegeController::class, 'update'])->middleware(middleware: 'auth')->name('colleges.update');
Route::delete('/colleges/{college}', [CollegeController::class, 'destroy'])->middleware(middleware: 'auth')->name('colleges.destroy');
 Route::get('/export/pdf', [CollegeController::class, 'exportPdf'])->name('export.pdf');
    Route::get('/export/excel', [CollegeController::class, 'exportExcel'])->name('export.excel');
// AJAX endpoint for cities
Route::get('/get-cities/{stateId}', [CollegeController::class, 'getCities'])->middleware(middleware: 'auth')->name('colleges.cities');

// admissions
Route::get('/admissions', [LeadController::class, 'admissionRequests'])
    ->name('admissions.index')
    ->middleware('auth');

Route::put('/admissions/{admission}/update-status', [LeadController::class, 'updateAdmissionStatus'])
    ->name('admissions.update-status')  // ✅ Make sure name matches
    ->middleware('auth');

// documents
Route::get('/documents', [LeadController::class, 'documents'])
    ->name('documents.index')
    ->middleware('auth');

// commission-rules
// Route::get('/commission-rules', function () {
//     return view(view: 'pages.commission-rules.index');
// })->name('commission-rules');

Route::get('/commission-rules', [CommissionRuleController::class, 'index'])->middleware(middleware: 'auth')->name('commission-rules.index');
Route::post('/commission-rules', [CommissionRuleController::class, 'store'])->middleware(middleware: 'auth')->name('commission-rules.store');
Route::put('/commission-rules/{commissionRule}', [CommissionRuleController::class, 'update'])->middleware(middleware: 'auth')->name('commission-rules.update');
Route::delete('/commission-rules/{commissionRule}', [CommissionRuleController::class, 'destroy'])->middleware(middleware: 'auth')->name('commission-rules.destroy');


// commission-payments
// Route::get('/commission-payments', function () {
//     return view(view: 'pages.commission-payments.index');
// })->name('commission-payments');
// ============ EXISTING ROUTES (Keep as is) ============
Route::get('/commission-payments', [CommissionPaymentController::class, 'index'])->name('commission-payments');
Route::get('/commission-payments/{payment}', [CommissionPaymentController::class, 'show'])->name('commission-payments.show');
Route::post('/commission-payments/generate/{admission}', [CommissionPaymentController::class, 'generatePayment'])->name('commission-payments.generate');
Route::post('/commission-payments/{payment}/mark-paid', [CommissionPaymentController::class, 'markAsPaid'])->name('commission-payments.mark-paid');
Route::post('/commission-payments/{payment}/cancel', [CommissionPaymentController::class, 'cancel'])->name('commission-payments.cancel');
Route::post('/commission-payments/bulk-generate', [CommissionPaymentController::class, 'bulkGenerate'])->name('commission-payments.bulk-generate');

// ============ NEW: Generate for Specific Consultant ============
Route::post('/commission-payments/generate-for-consultant', [CommissionPaymentController::class, 'generateForConsultant'])->name('commission-payments.generate-for-consultant');

// ============ SIMPLE ROUTES ONLY ============

// Consultant: View requests
Route::get('/payment-requests', [ConsultantPaymentRequestController::class, 'index'])
    ->name('payment-requests.index')
    ->middleware(['auth']);

// Consultant: Create form ✅
Route::get('/payment-requests/create', [ConsultantPaymentRequestController::class, 'create'])
    ->name('payment-requests.create')
    ->middleware(['auth']);

// Consultant: Store
Route::post('/payment-requests', [ConsultantPaymentRequestController::class, 'store'])
    ->name('payment-requests.store')
    ->middleware(['auth']);

// Admin: Approve
Route::post('/payment-requests/{request}/approve', [ConsultantPaymentRequestController::class, 'approve'])
    ->name('payment-requests.approve')
    ->middleware(['auth']);

// Admin: Reject
Route::post('/payment-requests/{request}/reject', [ConsultantPaymentRequestController::class, 'reject'])
    ->name('payment-requests.reject')
    ->middleware(['auth']);

// ✅ Requested Payments - Admin (Detailed View)
Route::get('/payment-requests/requested', [ConsultantPaymentRequestController::class, 'requestedPayments'])
    ->name('payment-requests.requested')
    ->middleware(['auth']); // ← Add this if missing

    // ✅ NEW: Actually make payment for approved request
    Route::post('/payment-requests/{request}/make-payment', [ConsultantPaymentRequestController::class, 'makePayment'])
        ->name('payment-requests.make-payment');
// sources

Route::get('/sources', [LeadSourceController::class, 'index'])->name('sources.index');
Route::post('/sources', [LeadSourceController::class, 'store'])->name('sources.store');
Route::put('/sources/{id}', [LeadSourceController::class, 'update'])->name('sources.update');
Route::delete('/sources/{id}', [LeadSourceController::class, 'destroy'])->name('sources.destroy');

// qualifications

Route::get('/qualifications', [QualificationController::class, 'index'])->name('qualifications.index');
Route::post('/qualifications', [QualificationController::class, 'store'])->name('qualifications.store');
Route::put('/qualifications/{id}', [QualificationController::class, 'update'])->name('qualifications.update');
Route::delete('/qualifications/{id}', [QualificationController::class, 'destroy'])->name('qualifications.destroy');

// intakes

Route::get('/intakes', [IntakeController::class, 'index'])->name('intakes.index');
Route::post('/intakes', [IntakeController::class, 'store'])->name('intakes.store');
Route::put('/intakes/{id}', [IntakeController::class, 'update'])->name('intakes.update');
Route::delete('/intakes/{id}', [IntakeController::class, 'destroy'])->name('intakes.destroy');

// priorities

Route::get('/priorities', [PriorityController::class, 'index'])->name('priorities.index');
Route::post('/priorities', [PriorityController::class, 'store'])->name('priorities.store');
Route::put('/priorities/{id}', [PriorityController::class, 'update'])->name('priorities.update');
Route::delete('/priorities/{id}', [PriorityController::class, 'destroy'])->name('priorities.destroy');

// document-settings

Route::get('/document-settings', [DocumentSettingController::class, 'index'])->name('document-settings.index');
Route::post('/document-settings', [DocumentSettingController::class, 'store'])->name('document-settings.store');
Route::put('/document-settings/{id}', [DocumentSettingController::class, 'update'])->name('document-settings.update');
Route::delete('/document-settings/{id}', [DocumentSettingController::class, 'destroy'])->name('document-settings.destroy');

// communication-logs

Route::get('/communication-logs', [CommunicationLogController::class, 'index'])->name('communication-logs.index');
Route::post('/communication-logs', [CommunicationLogController::class, 'store'])->name('communication-logs.store');
Route::put('/communication-logs/{id}', [CommunicationLogController::class, 'update'])->name('communication-logs.update');
Route::delete('/communication-logs/{id}', [CommunicationLogController::class, 'destroy'])->name('communication-logs.destroy');

// Courses

Route::get('/courses', [CoursesController::class, 'index'])->name('courses.index');
Route::post('/courses', [CoursesController::class, 'store'])->name('courses.store');
Route::put('/courses/{id}', [CoursesController::class, 'update'])->name('courses.update');
Route::delete('/courses/{id}', [CoursesController::class, 'destroy'])->name('courses.destroy');


// lost-reasons
Route::get('/lost-reasons', function () {
    return view(view: 'pages.lost-reasons.index');
})->name('lost-reasons');

// contact-stage


Route::get('/contact-stage', [ContactStageController::class, 'index'])->name('contact-stage.index');
Route::post('/contact-stage', [ContactStageController::class, 'store'])->name('contact-stage.store');
Route::put('/contact-stage/{id}', [ContactStageController::class, 'update'])->name('contact-stage.update');
Route::delete('/contact-stage/{id}', [ContactStageController::class, 'destroy'])->name('contact-stage.destroy');



// calls
Route::get('/calls', function () {
    return view(view: 'pages.calls.index');
})->name('calls');

// consultant

Route::get('/consultants', [ConsultantController::class, 'index'])->name('consultants.index');
Route::post('/consultants', [ConsultantController::class, 'store'])->name('consultants.store');
Route::put('/consultants/{id}', [ConsultantController::class, 'update'])->name('consultants.update');
Route::delete('/consultants/{id}', [ConsultantController::class, 'destroy'])->name('consultants.destroy');
Route::post('/consultants/{id}/toggle', [ConsultantController::class, 'toggleStatus'])->name('consultants.toggle');

// routes/web.php

Route::middleware(['auth'])->group(function () {
    Route::post('/consultants/{consultant}/lock-college', [ConsultantController::class, 'lockCollege'])
        ->name('consultants.lock-college');
    Route::get('/consultants/{consultant}/unlock-college/{college}', [ConsultantController::class, 'unlockCollege'])
        ->name('consultants.unlock-college');
});
// ✅ KYC Routes - Matching your convention
Route::get('/consultants/{id}', [ConsultantController::class, 'show'])->name('consultants.show');
Route::post('/consultants/{id}/kyc/upload', [ConsultantController::class, 'uploadKyc'])->name('consultants.kyc.upload');
Route::post('/consultants/{id}/kyc/{kyc_id}/verify', [ConsultantController::class, 'verifyKyc'])->name('consultants.kyc.verify');
Route::post('/consultants/{id}/kyc/{kyc_id}/reject', [ConsultantController::class, 'rejectKyc'])->name('consultants.kyc.reject');

// API route for AJAX (must be BEFORE any conflicting routes)
Route::get('/api/cities/{stateId}', [ConsultantController::class, 'getCitiesByState'])
    ->name('api.cities.byState');

    // settings
    
// general settings
Route::middleware(['auth'])->group(function () {
    Route::get('/profile-settings', [ProfileSettingsController::class, 'index'])->name('profile-settings');
    Route::post('/profile-settings', [ProfileSettingsController::class, 'update'])->name('profile-settings.update');
});
Route::middleware(['auth'])->group(function () {
    // Main View (Your exact route)
    Route::get('/security-settings', [SecuritySettingsController::class, 'index'])
        ->name('security-settings');

    // Action Routes
    Route::put('/security/password', [SecuritySettingsController::class, 'updatePassword'])->name('security.password');
    Route::post('/security/two-factor', [SecuritySettingsController::class, 'toggleTwoFactor'])->name('security.twofactor');
    Route::put('/security/phone', [SecuritySettingsController::class, 'updatePhone'])->name('security.phone');
    Route::put('/security/email', [SecuritySettingsController::class, 'updateEmail'])->name('security.email');
    Route::post('/security/device/{id}/logout', [SecuritySettingsController::class, 'logoutDevice'])->name('security.device.logout');
    Route::post('/security/deactivate', [SecuritySettingsController::class, 'deactivateAccount'])->name('security.deactivate');
    Route::delete('/security/delete', [SecuritySettingsController::class, 'deleteAccount'])->name('security.delete');
});

Route::get('/notification-settings', function () {
    return view('pages.settings.general-settings.notification-settings');
})->name('notification-settings');


Route::get('/connected-apps-settings', function () {
    return view('pages.settings.general-settings.connected-apps-settings');
})->name('connected-apps-settings');


// website settings
Route::get('/appearance-settings', function () {
    return view('pages.settings.website-settings.appearance-settings');
})->name('appearance-settings');

Route::get('/company-settings', function () {
    return view('pages.settings.website-settings.company-settings');
})->name('company-settings');



Route::get('/language-settings', [LanguageSettingController::class, 'index'])
    ->name('language-settings');

Route::post('/language-settings', [LanguageSettingController::class, 'store'])
    ->name('language-settings.store');

Route::put('/language-settings/{id}', [LanguageSettingController::class, 'update'])
    ->name('language-settings.update');

Route::delete('/language-settings/{id}', [LanguageSettingController::class, 'destroy'])
    ->name('language-settings.destroy');

// AJAX Routes
Route::post('/language-settings/{id}/toggle-status', [LanguageSettingController::class, 'toggleStatus'])
    ->name('language-settings.toggle-status');

Route::post('/language-settings/{id}/toggle-rtl', [LanguageSettingController::class, 'toggleRTL'])
    ->name('language-settings.toggle-rtl');

Route::post('/language-settings/{id}/set-default', [LanguageSettingController::class, 'setDefault'])
    ->name('language-settings.set-default');

Route::post('/language-settings/{id}/toggle-platform/{platform}', [LanguageSettingController::class, 'togglePlatform'])
    ->name('language-settings.toggle-platform');



// Show the page
Route::get('/localization-settings', [LocalizationSettingController::class, 'index'])->name("localization-settings");

// Handle form submission
Route::post('/localization-settings', [LocalizationSettingController::class, 'update'])->name("localization-settings.update");

Route::get('/preference-settings', function () {
    return view('pages.settings.website-settings.preference-settings');
})->name('preference-settings');


Route::get('/prefix-settings', function () {
    return view('pages.settings.website-settings.prefix-settings');
})->name('prefix-settings');

// app settings

Route::get('/custom-fields', function () {
    return view('pages.settings.app-settings.custom-fields');
})->name('custom-fields');

Route::middleware(['auth'])->group(function () {
    Route::get('/invoice-settings', [InvoiceSettingController::class, 'index'])
        ->name('invoice-settings.index');
    
    Route::put('/invoice-settings', [InvoiceSettingController::class, 'update'])
        ->name('invoice-settings.update');
});
Route::get('/printers', function () {
    return view('pages.settings.app-settings.printers');
})->name('printers');

// system settings
Route::middleware(['auth'])->group(function () {
    
    // GDPR Cookies Settings Page
    Route::get('/cookies', [GdprCookieController::class, 'index'])
        ->name('cookies.index');
    
    // Save/Update settings
    Route::post('/cookies', [GdprCookieController::class, 'update'])
        ->name('cookies.update');
    
    // Toggle active status
    Route::patch('/cookies/toggle', [GdprCookieController::class, 'toggleStatus'])
        ->name('cookies.toggle');
});


Route::middleware(['auth'])->group(function () {
    
    // Email Settings Page
    Route::get('/email-settings', [EmailSettingController::class, 'index'])
        ->name('email-settings.index');
    
    // Create new email provider
    Route::post('/email-settings', [EmailSettingController::class, 'store'])
        ->name('email-settings.store');
    
    // Update existing provider
    Route::put('/email-settings/{id}', [EmailSettingController::class, 'update'])
        ->name('email-settings.update');
    
    // Toggle active status
    Route::patch('/email-settings/{id}/toggle', [EmailSettingController::class, 'toggleStatus'])
        ->name('email-settings.toggle');
    
    // Send test email
    Route::post('/email-settings/{id}/test', [EmailSettingController::class, 'sendTestEmail'])
        ->name('email-settings.test');
    
    // Delete provider
    Route::delete('/email-settings/{id}', [EmailSettingController::class, 'destroy'])
        ->name('email-settings.destroy');
});

Route::get('/sms-settings', function () {
    return view('pages.settings.system-settings.sms-settings');
})->name('sms-settings');

// financial settings

// Route::get('/bank-account-settings', function () {
//     return view('pages.settings.financial-settings.bank-account-settings');
// })->name('bank-account-settings');

Route::middleware(['auth'])->group(function () {
    
    // Bank Accounts Page
    Route::get('/bank-account-settings', [BankAccountController::class, 'index'])
        ->name('bank-account-settings.index');
    
    // Store new account
    Route::post('/bank-account-settings', [BankAccountController::class, 'store'])
        ->name('bank-account-settings.store');
    
    // Update existing account
    Route::put('/bank-account-settings/{id}', [BankAccountController::class, 'update'])
        ->name('bank-account-settings.update');
    
    // Delete account
    Route::delete('/bank-account-settings/{id}', [BankAccountController::class, 'destroy'])
        ->name('bank-account-settings.destroy');
    
    // Toggle active status (optional)
    Route::patch('/bank-account-settings/{id}/toggle', [BankAccountController::class, 'toggleStatus'])
        ->name('bank-account-settings.toggle');
});


// Route::get('/currency-settings', function () {
//     return view('pages.settings.financial-settings.currency-settings');
// })->name('currency-settings');
Route::middleware(['auth'])->group(function () {
    
    Route::get('/currency-settings', [CurrencyController::class, 'index'])
        ->name('currency-settings.index');
    
    Route::post('/currency-settings', [CurrencyController::class, 'store'])
        ->name('currency-settings.store');
    
    Route::put('/currency-settings/{id}', [CurrencyController::class, 'update'])
        ->name('currency-settings.update');
    
    Route::delete('/currency-settings/{id}', [CurrencyController::class, 'destroy'])
        ->name('currency-settings.destroy');
    
    Route::patch('/currency-settings/{id}/toggle', [CurrencyController::class, 'toggleStatus'])
        ->name('currency-settings.toggle');
    
    Route::post('/currency-settings/{id}/set-default', [CurrencyController::class, 'setDefault'])
        ->name('currency-settings.default');
});

// Route::get('/payment-gateway-settings', function () {
//     return view('pages.settings.financial-settings.payment-gateway-settings');
// })->name('payment-gateway-settings');


Route::middleware(['auth'])->group(function () {
    
    Route::get('/payment-gateway-settings', [PaymentMethodController::class, 'index'])
        ->name('payment-gateway-settings.index');
    
    Route::post('/payment-gateway-settings', [PaymentMethodController::class, 'store'])
        ->name('payment-gateway-settings.store');
    
    Route::put('/payment-gateway-settings/{id}', [PaymentMethodController::class, 'update'])
        ->name('payment-gateway-settings.update');
    
    Route::patch('/payment-gateway-settings/{id}/toggle', [PaymentMethodController::class, 'toggleStatus'])
        ->name('payment-gateway-settings.toggle');
    
    Route::delete('/payment-gateway-settings/{id}', [PaymentMethodController::class, 'destroy'])
        ->name('payment-gateway-settings.destroy');
    
    Route::post('/payment-gateway-settings/{id}/test-connection', [PaymentMethodController::class, 'testConnection'])
        ->name('payment-gateway-settings.test-connection');
});
Route::middleware(['auth'])->group(function () {
    
    // Main page
    Route::get('/tax-rate-settings', [TaxController::class, 'index'])
        ->name('tax-rate-settings.index');
    
    // Tax Rates CRUD
    Route::post('/tax-rate-settings', [TaxController::class, 'storeRate'])
        ->name('tax-rate-settings.store');
    Route::put('/tax-rate-settings/{id}', [TaxController::class, 'updateRate'])
        ->name('tax-rate-settings.update');
    Route::delete('/tax-rate-settings/{id}', [TaxController::class, 'destroyRate'])
        ->name('tax-rate-settings.destroy');
    
    // Tax Groups CRUD
    Route::post('/tax-groups', [TaxController::class, 'storeGroup'])
        ->name('tax-groups.store');
    Route::put('/tax-groups/{id}', [TaxController::class, 'updateGroup'])
        ->name('tax-groups.update');
    Route::delete('/tax-groups/{id}', [TaxController::class, 'destroyGroup'])
        ->name('tax-groups.destroy');
});

// other settings

Route::get('ban-ip-settings', function () {
    return view('pages.settings.other-settings.ban-ip-settings');
})->name('ban-ip-settings');

Route::get('clear-cache-settings', function () {
    return view('pages.settings.other-settings.clear-cache-settings');
})->name('clear-cache-settings');

Route::get('cronjob-settings', function () {
    return view('pages.settings.other-settings.cronjob-settings');
})->name('cronjob-settings');

Route::get('database-backup-settings', function () {
    return view('pages.settings.other-settings.database-backup-settings');
})->name('database-backup-settings');

Route::get('sitemap-settings', function () {
    return view('pages.settings.other-settings.sitemap-settings');
})->name('sitemap-settings');


Route::get('storage-settings', function () {
    return view('pages.settings.other-settings.storage-settings');
})->name('storage-settings');


Route::get('system-update-settings', function () {
    return view('pages.settings.other-settings.system-update-settings');
})->name('system-update-settings');

// user-management
Route::prefix('user-management')->group(function () {

    // ========================
    // Roles CRUD
    // ========================
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
    Route::post('/roles/{role}/permissions', [RoleController::class, 'assignPermissions'])
        ->name('roles.permissions.assign');

    // ========================
    // Users CRUD (UPDATED - Controller-based)
    // ========================
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // ========================
    // Permissions CRUD
    // ========================
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');



    // slab rules


    Route::resource('slab-rules', SlabRuleController::class)->except(['show']);






















});
