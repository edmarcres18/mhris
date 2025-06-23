<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GenderController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\BarangayController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AccountabilityController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ContributionController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\OverTimePayController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ItInventoryController;
use App\Http\Controllers\CredentialController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\HiringController;
use App\Http\Controllers\SssController;
use App\Http\Controllers\PagibigController;
use App\Http\Controllers\PhilhealthController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\SssLoanController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CashAdvanceController;
use App\Http\Controllers\PagibigLoanController;
use App\Http\Controllers\EmployeeBirthdayController;
use App\Http\Controllers\ControllerAnalysisController;
use App\Http\Controllers\UserActivityController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AiController;
use App\Http\Controllers\LoginHistoryController;
use App\Http\Controllers\SystemUpdateController;
use App\Http\Controllers\MedicalProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\RouteManagementController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\NightPremiumController;
use App\Http\Controllers\DatabaseBackupController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentReactionController;
use App\Http\Controllers\GetAppController;
use App\Http\Controllers\CompanyEmailController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MessagingController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\AiAuthController;
use App\Http\Controllers\AiChatController;
use App\Http\Controllers\Admin\AiUserController;
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

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Careers routes
Route::post('/save-job', [CareerController::class, 'saveJob'])->name('save.job');
Route::post('/unsave-job', [CareerController::class, 'unsaveJob'])->name('unsave.job');
Route::get('/saved-jobs', [CareerController::class, 'getSavedJobs'])->name('saved.jobs');
Route::get('/careers', [CareerController::class, 'index'])->name('careers');
Route::get('/applicants/{id}', [CareerController::class, 'showApplicant'])->name('showApplicant');
Route::post('/careers/apply', [CareerController::class, 'apply'])->name('careers.apply');
Route::get('/all-careers', [CareerController::class, 'getAllCareers'])->name('careers.all');
Route::get('/careers/{slug}', [CareerController::class, 'show'])->name('careers.show');
Route::post('/careers/{id}/schedule-interview', [CareerController::class, 'scheduleInterview'])->name('careers.schedule-interview');
Route::get('/saved-jobs', [CareerController::class, 'savedJobs'])->name('saved.jobs');
Route::post('/toggle-save-job', [CareerController::class, 'toggleSaveJob'])->name('toggle.save.job');
Route::get('/careers/unread-count', [CareerController::class, 'getUnreadCount'])->name('careers.unread-count')->middleware(['auth', 'can:hrhiring']);

//GoogleAuth routes
Route::get('auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);
Route::post('auth/google/logout', [GoogleAuthController::class, 'logout'])->name('google.logout');

// Hiring routes
Route::get('/related-jobs/{hiring}', [HiringController::class, 'relatedJobs'])->name('related.jobs');

// Welcome routes
Route::get('/subsidiaries', [WelcomeController::class, 'allSubsidiaries'])->name('all_subsidiaries');
Route::get('/mhrpropertyconglomeratesinc', [WelcomeController::class, 'showMhrpci'])->name('mhrpci');
Route::get('/baygaspetroleumdistributioninc', [WelcomeController::class, 'showBgpdi'])->name('bgpdi');
Route::get('/mhrhealthcareinc', [WelcomeController::class, 'showMhrhci'])->name('mhrhci');
Route::get('/medical_equipment', [WelcomeController::class, 'showMedicalEquipment'])->name('medical_equipment');
Route::get('/medical_products', [WelcomeController::class, 'showMedicalProducts'])->name('medical_products');
Route::get('/cebicindustries', [WelcomeController::class, 'showCio'])->name('cio');
Route::get('/verbenahotelinc', [WelcomeController::class, 'showVhi'])->name('vhi');
Route::get('/maximumhandlingresources', [WelcomeController::class, 'showMax'])->name('max');
Route::get('/lusciousco', [WelcomeController::class, 'showLus'])->name('lus');
Route::get('/mhrconstruction', [WelcomeController::class, 'showMhrcons'])->name('mhrcons');
Route::get('/rcgpharmaceutical', [WelcomeController::class, 'showRcg'])->name('rcg');

Route::post('/contactmhrhci', [ContactController::class, 'sendEmailMhrhci'])->name('contact.sendmhrhci');
Route::post('/contactbgpdi', [ContactController::class, 'sendEmailBgpdi'])->name('contact.sendbgpdi');

// Quotation routes
Route::post('/api/quotation-request', [QuotationController::class, 'sendRequest'])->name('quotation.request');
   // Terms and Privacy routes
   Route::get('/terms', function () {
    return view('terms');
})->name('terms');

Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

Route::get('/csr', function () {
    return view('csr');
})->name('csr');

Route::get('/test-mail', function () {
    Mail::raw('This is a test email from Laravel.', function ($message) {
        $message->to('mhrpciofficial@gmail.com')
                ->subject('SMTP Test');
    });

    return 'Test email sent!';
});

// Public AI Assistant route that shows the login/signup form if not authenticated
Route::get('/mhrpci-ai', function () {
    return view('mhrpci-ai');
})->name('mhrpci-ai');


// Public access to shared company emails
Route::get('/shared-emails/{token}', [CompanyEmailController::class, 'accessSharedEmails'])->name('public.shared-emails');

// Public access to shared credentials - specific routes must come before generic ones
Route::get('/shared-credentials/verify-otp/{token}', [CredentialController::class, 'showOtpVerification'])->name('credentials.verify-otp');
Route::post('/shared-credentials/verify-otp/{token}', [CredentialController::class, 'verifyOtp'])->name('credentials.verify-otp-submit');
Route::get('/shared-credentials/resend-otp/{token}', [CredentialController::class, 'resendOtp'])->name('credentials.resend-otp');
Route::get('/shared-credentials/auth/{token}', [CredentialController::class, 'showEmailAuthForm'])->name('credentials.email-auth');
Route::post('/shared-credentials/auth/{token}', [CredentialController::class, 'processEmailAuth'])->name('credentials.process-email-auth');
Route::get('/shared-credentials/{token}', [CredentialController::class, 'accessSharedCredentials'])->name('credentials.access-shared');

// Public Profile routes
Route::get('/employees-public/{slug}', [EmployeeController::class, 'publicProfile'])->name('employees.public');
Route::get('/employees/{slug}/secure-download', [EmployeeController::class, 'downloadSecureIdCard'])->name('employees.secure-download');
Route::post('/employees/process-secure-download', [EmployeeController::class, 'processSecureDownload'])->name('employees.process-secure-download');
Route::post('/validate-user', [App\Http\Controllers\AuthController::class, 'validateUser']);

// Auth routes
Route::middleware('auth')->group(function () {
    // Our resource routes
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('genders', GenderController::class);
    Route::resource('positions', PositionController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('provinces', ProvinceController::class);
    Route::resource('city', CityController::class);
    Route::resource('barangay', BarangayController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('attendances', AttendanceController::class);
    Route::resource('leaves', LeaveController::class);
    Route::resource('contributions', ContributionController::class);
    Route::resource('loans', LoanController::class);
    Route::resource('types', TypeController::class);
    Route::resource('inventory', ItInventoryController::class);
    Route::resource('overtime', OverTimePayController::class);
    Route::resource('posts', PostController::class);
    Route::resource('company-emails', CompanyEmailController::class);
    Route::post('/company-emails/store-and-create-another', [CompanyEmailController::class, 'storeAndCreateAnother'])->name('company-emails.store-and-create-another');
    
 
    // Company Email Sharing routes
    Route::get('/company-emails-share', [CompanyEmailController::class, 'showShareForm'])->name('company-emails.share-form');
    Route::post('/company-emails-share', [CompanyEmailController::class, 'generateShareableLink'])->name('company-emails.generate-share');
    Route::get('/company-emails-shareable-links', [CompanyEmailController::class, 'listShareableLinks'])->name('company-emails.shareable-links');
    Route::get('/company-emails-share/{token}', [CompanyEmailController::class, 'showShareableLink'])->name('company-emails.share-link');
    Route::delete('/company-emails-share/{shareableLink}', [CompanyEmailController::class, 'deleteShareableLink'])->name('company-emails.delete-share');
    
    // Post reactions and comments routes
    Route::post('/posts/{post}/reactions', [ReactionController::class, 'store'])->name('posts.reactions.store');
    Route::delete('/posts/{post}/reactions', [ReactionController::class, 'destroy'])->name('posts.reactions.destroy');
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('posts.comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::get('/posts/{post}/comments', [CommentController::class, 'loadMore'])->name('posts.comments.more');
    Route::get('/posts/{post}/reactions', [PostController::class, 'getReactionDetails'])->name('posts.reactions.details');
    Route::get('/posts/{post}/reactions/{type}', [PostController::class, 'getReactionDetails'])->name('posts.reactions.type.details');
    
    // Comment reactions routes
    Route::post('/comments/{comment}/reactions', [CommentReactionController::class, 'store'])->name('comments.reactions.store');
    Route::delete('/comments/{comment}/reactions', [CommentReactionController::class, 'destroy'])->name('comments.reactions.destroy');
    
    Route::resource('holidays', HolidayController::class);
    Route::resource('tasks', TaskController::class);
    Route::resource('credentials', CredentialController::class);
    Route::get('/credentials-get-email-password', [CredentialController::class, 'getEmailPassword'])->name('credentials.get-email-password');
    
    // Credential Sharing routes
    Route::get('/credentials-share', [CredentialController::class, 'showShareForm'])->name('credentials.share-form');
    Route::post('/credentials-share', [CredentialController::class, 'generateShareableLink'])->name('credentials.generate-share');
    Route::get('/credentials-shareable-links', [CredentialController::class, 'listShareableLinks'])->name('credentials.shareable-links');
    Route::get('/credentials-share/{id}', [CredentialController::class, 'showShareableLink'])->name('credentials.show-link');
    Route::get('/credentials-share/{id}/tracking', [CredentialController::class, 'showLinkTracking'])->name('credentials.tracking');
    Route::delete('/credentials-share/{shareableLink}', [CredentialController::class, 'deleteShareableLink'])->name('credentials.delete-share');
    
    Route::resource('hirings', HiringController::class);
    Route::resource('pagibig', PagibigController::class);
    Route::resource('accountabilities', AccountabilityController::class);
    Route::resource('loan_sss', SssLoanController::class);
    Route::resource('cash_advances', CashAdvanceController::class);
    Route::resource('loan_pagibig', PagibigLoanController::class);
    Route::resource('sss', SssController::class)->except(['edit', 'update']);
    Route::resource('pagibig', PagibigController::class)->except(['edit', 'update']);
    Route::resource('philhealth', PhilhealthController::class)->except(['edit', 'update']);
    Route::resource('system-updates', SystemUpdateController::class);
    Route::resource('night-premium', NightPremiumController::class);
    
    // Night Premium routes
    Route::put('night-premium/{nightPremium}/approvedBySupervisor', [NightPremiumController::class, 'approvedBySupervisor'])->name('night-premium.approvedBySupervisor');
    Route::put('night-premium/{nightPremium}/rejectedBySupervisor', [NightPremiumController::class, 'rejectedBySupervisor'])->name('night-premium.rejectedBySupervisor');
    Route::put('night-premium/{nightPremium}/approvedByFinance', [NightPremiumController::class, 'approvedByFinance'])->name('night-premium.approvedByFinance');
    Route::put('night-premium/{nightPremium}/rejectedByFinance', [NightPremiumController::class, 'rejectedByFinance'])->name('night-premium.rejectedByFinance');
    Route::put('night-premium/{nightPremium}/approvedByVPFinance', [NightPremiumController::class, 'approvedByVPFinance'])->name('night-premium.approvedByVPFinance');
    Route::put('night-premium/{nightPremium}/rejectedByVPFinance', [NightPremiumController::class, 'rejectedByVPFinance'])->name('night-premium.rejectedByVPFinance');

    // Employee Night Premium Application routes
    Route::match(['get', 'post'], '/employee-night-premium/apply', [NightPremiumController::class, 'applyForNightPremium'])->name('night-premium.apply');
    Route::get('/employee-night-premium/history', [NightPremiumController::class, 'employeeNightPremiumHistory'])->name('night-premium.history');

    // Overtime routes
    Route::put('overtime/{overtime}/approvedBySupervisor', [OverTimePayController::class, 'approvedBySupervisor'])->name('overtime.approvedBySupervisor');
    Route::put('overtime/{overtime}/rejectedBySupervisor', [OverTimePayController::class, 'rejectedBySupervisor'])->name('overtime.rejectedBySupervisor');
    Route::put('overtime/{overtime}/approvedByFinance', [OverTimePayController::class, 'approvedByFinance'])->name('overtime.approvedByFinance');
    Route::put('overtime/{overtime}/rejectedByFinance', [OverTimePayController::class, 'rejectedByFinance'])->name('overtime.rejectedByFinance');
    Route::put('overtime/{overtime}/approvedByVPFinance', [OverTimePayController::class, 'approvedByVPFinance'])->name('overtime.approvedByVPFinance');
    Route::put('overtime/{overtime}/rejectedByVPFinance', [OverTimePayController::class, 'rejectedByVPFinance'])->name('overtime.rejectedByVPFinance');
    Route::get('/overtime-hours/{employeeId}', [OverTimePayController::class, 'getOvertimeHours'])->name('overtime.hours');
    
    // Employee Overtime Application routes
    Route::match(['get', 'post'], '/employee-overtime/apply', [OverTimePayController::class, 'applyForOvertime'])->name('overtime.apply');
    Route::get('/employee-overtime/history', [OverTimePayController::class, 'employeeOvertimeHistory'])->name('overtime.history');

    // Contribution Notify routes
    Route::post('/sss/notify', [SssController::class, 'notifyEmployees'])->name('sss.notify');
    Route::post('/pagibig/notify', [PagibigController::class, 'notifyEmployees'])->name('pagibig.notify');
    Route::post('/philhealth/notify', [PhilhealthController::class, 'notifyEmployees'])->name('philhealth.notify');
    Route::post('/contributions/notify-all', [ContributionController::class, 'sendAllNotifications'])->name('contributions.notify-all');
    // Employees routes
    Route::post('/employees/import', [EmployeeController::class, 'import'])->name('employees.import');
    Route::post('/employees/export', [EmployeeController::class, 'export'])->name('employees.export');
    Route::get('/employees/filter', [EmployeeController::class, 'filter'])->name('employees.filter');
    Route::post('employees/{employee}/update-status', [EmployeeController::class, 'updateStatus'])->name('employees.updateStatus');
    Route::patch('employees/{employee}/disable', [EmployeeController::class, 'disable'])->name('employees.disable');
    Route::get('/employee/attendance/{employee_id}', [AttendanceController::class, 'showEmployeeAttendance'])->name('employee.attendance');
    Route::post('/employees/{employee}/create-user', [EmployeeController::class, 'createUser'])->name('employees.createUser');
    Route::get('/employees/{id}/status', [EmployeeController::class, 'getStatus']);
    Route::get('/employees/{slug}', [EmployeeController::class, 'show']);
    Route::get('employees/{slug}/edit', [EmployeeController::class, 'edit']);
    Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::get('/my-profile', [EmployeeController::class, 'viewOwnEmployeeProfile'])
    ->name('employee.own-profile');

    // Attendance routes
    Route::get('attendances/auto-mark-absent', [AttendanceController::class, 'autoMarkAbsent'])->name('attendances.autoMarkAbsent');
    Route::get('/timesheets', [AttendanceController::class, 'generateTimesheets'])->name('attendances.timesheets');
    Route::get('/my-timesheet', [AttendanceController::class, 'checkUserAndShowTimesheet'])->name('attendances.my-timesheet');
    Route::get('/check-attendance', [AttendanceController::class, 'checkAttendance']);
    Route::get('/attendances/print', [AttendanceController::class, 'printAttendance'])->name('attendances.print');
    Route::post('/attendances/store-and-create', [AttendanceController::class, 'storeAndCreateAnother'])->name('attendances.storeAndCreateAnother');
    Route::get('attendances/import', [AttendanceController::class, 'showImportForm'])->name('attendances.import.form');
    Route::post('attendances/import', [AttendanceController::class, 'import'])->name('attendances.import');
    Route::get('attendances/export', [AttendanceController::class, 'export'])->name('attendances.export');
    Route::get('/attendance', [AttendanceController::class, 'attendance'])->name('attendances.attendance');
    Route::get('/attendance/status', [AttendanceController::class, 'getAttendanceStatus'])->name('attendance.status');
    Route::get('/attendance/preview', [AttendanceController::class, 'capturePreview'])->name('attendance.preview');

    // Attendance store command route
    Route::post('/attendance/store-command', [AttendanceController::class, 'executeStoreCommand'])
        ->name('attendance.store-command')
        ->middleware(['auth', 'can:hrcomben,admin,super-admin']);

    // User Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/details', [ProfileController::class, 'details'])->name('profile.details');

    // New signature route
    Route::post('/profile/signature', [ProfileController::class, 'updateSignature'])
    ->name('profile.signature.update');

    // Leave routes
    Route::put('/leaves/{id}/status', [LeaveController::class, 'updateStatus'])->name('leaves.updateStatus');
    Route::get('/leaves/detail/{id}', [LeaveController::class, 'detail'])->name('leaves.detail');
    Route::get('/leaves/print', [LeaveController::class, 'print'])->name('leaves.print');
    Route::get('/leaves-employees', [LeaveController::class, 'allEmployees'])->name('leaves.all_employees');
    Route::get('/leaves-report', [LeaveController::class, 'report'])->name('leaves.report');
    Route::get('leaves-employees/{employee_id}/leaves', [LeaveController::class, 'employeeLeaves'])->name('leaves.employee_leaves');
    Route::get('/leave-balance/{employeeId}', [LeaveController::class, 'showLeaveBalance'])->name('leaves.balance');
    Route::get('/my-leave-sheet', [LeaveController::class, 'myLeaveSheet'])->name('leaves.my_leave_sheet');
    Route::get('/my-leave-detail/{id}', [LeaveController::class, 'myLeaveDetail'])->name('leaves.myLeaveDetail');

    // Payroll routes
    Route::get('/payroll/{id}/download-pdf', [PayrollController::class, 'downloadPdf'])->name('payroll.download-pdf');
    Route::delete('/payroll/{id}', [PayrollController::class, 'destroy'])->name('payroll.destroy');
    Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');
    Route::get('/payroll/create', [PayrollController::class, 'create'])->name('payroll.create');
    Route::post('/payroll', [PayrollController::class, 'store'])->name('payroll.store');
    Route::get('/payroll/{id}', [PayrollController::class, 'show'])->name('payroll.show');
    Route::get('/payroll-employees-with-payroll', [PayrollController::class, 'employeesWithPayroll'])->name('payroll.employeesWithPayroll');
    Route::get('/payroll/{payroll}/payslip', [PayrollController::class, 'generatePayslip'])->name('payroll.payslip');
    Route::get('/my-payrolls', [PayrollController::class, 'myPayrolls'])->name('payroll.myPayrolls');
    Route::get('payroll/download-pdf/{id}', [PayrollController::class, 'downloadPdf'])->name('payroll.downloadPdf');

    // New payroll adjustment routes
    Route::get('/payroll/adjustments/get', [PayrollController::class, 'getAdjustments'])->name('payroll.getAdjustments');
    Route::post('/payroll/adjustments/save', [PayrollController::class, 'saveAdjustments'])->name('payroll.saveAdjustments');

    // New printable payroll routes
    Route::get('/payroll/printable-payroll/get', [PayrollController::class, 'getPrintablePayroll'])->name('payroll.getPrintablePayroll');
    Route::get('/payroll/print-preview/get', [PayrollController::class, 'getPrintPreview'])->name('payroll.getPrintPreview');

    // Add route for sending payroll notifications
    Route::post('/payroll/send-notification', [PayrollController::class, 'sendNotification'])->name('payroll.sendNotification');

    // Contributions routes
    Route::get('/contributions-employee/{employee_id}', [ContributionController::class, 'employeeContributions'])->name('contributions.employee');
    Route::get('/contributions-employees-list', [ContributionController::class, 'allEmployeesContribution'])->name('contributions.employees-list');
    Route::get('/my-contributions', [ContributionController::class, 'myContributions'])->name('contributions.my');

    // Loans routes
    Route::get('/loans-employee/{employee_id}', [LoanController::class, 'employeeLoans'])->name('loans.employee');
    Route::get('/loans-employees-list', [LoanController::class, 'allEmployeesLoan'])->name('loans.employees-list');
    Route::get('/my-loans', [LoanController::class, 'myLoans'])->name('loans.my-loans');

    // Tasks routes
    Route::get('/tasks', [TaskController::class, 'checkUserAndShowTasks'])->name('checkUserAndShowTasks');
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/my-tasks', [TaskController::class, 'myTasks'])->name('tasks.myTasks');
    Route::post('/my-tasks', [TaskController::class, 'myTasks'])->name('myTasks');

    // Sss routes
    Route::get('/loan_sss/{id}/ledger', [SssLoanController::class, 'showLedger'])->name('loan_sss.ledger');
    Route::post('/loan_sss/generate-payments', [SssLoanController::class, 'generatePayments'])->name('loan_sss.generate_payments');
    Route::post('/loan_sss/{id}/update_status', [SssLoanController::class, 'updateStatus'])->name('loan_sss.update_status');
    Route::post('/sss/destroy-multiple', [SssController::class, 'destroyMultiple'])->name('sss.destroy.multiple');
    Route::post('/sss/store-all-active', [SssController::class, 'storeAllActive'])->name('sss.store-all-active');

    //Pagibig routes
    Route::get('/loan_pagibig/{id}/ledger', [PagibigLoanController::class, 'showLedger'])->name('loan_pagibig.ledger');
    Route::post('/loan_pagibig/generate-payments', [PagibigLoanController::class, 'generatePayments'])->name('loan_pagibig.generate_payments');
    Route::post('/loan_pagibig/{id}/update_status', [PagibigLoanController::class, 'updateStatus'])->name('loan_pagibig.update_status');
    Route::post('/pagibig/store-all-active', [PagibigController::class, 'storeAllActive'])->name('pagibig.store-all-active');

    //Philhealth routes
    Route::post('/philhealth/store-all-active', [PhilhealthController::class, 'storeAllActive'])->name('philhealth.store-all-active');

    // Cash Advances routes
    Route::get('/cash_advances/{id}/ledger', [CashAdvanceController::class, 'ledger'])->name('cash_advances.ledger');
    Route::post('/cash_advances/generate-payments', [CashAdvanceController::class, 'generatePayments'])->name('cash_advances.generate_payments');
    Route::post('/cash-advances/generate-payment-for-employee', [CashAdvanceController::class, 'generatePaymentForEmployee'])->name('cash_advances.generate_payment_for_employee');

    // Post routes
    Route::get('/posts/show/{id}', [PostController::class, 'showPostById'])->name('posts.showById');

    // Calendar routes
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/holidays', [CalendarController::class, 'getHolidays'])->name('calendar.holidays');

    // Home routes
    Route::get('/fetch-leaves', [HomeController::class, 'fetchLeavesByAuthUserFirstName']);
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    //Logout routes
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Inventory routes
    Route::post('inventory/import', [ItInventoryController::class, 'import'])->name('inventory.import');
    Route::post('inventory/export', [ItInventoryController::class, 'export'])->name('inventory.export');


    // Notifications routes
    Route::get('/notifications/data', [NotificationsController::class, 'getNotificationsData'])->name('notifications.data');
    // Route::get('/notifications', [NotificationsController::class, 'showAllNotifications'])->name('notifications.all');
    Route::post('/notifications/{id}/read', [NotificationsController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/clear-all', [NotificationsController::class, 'clearAll'])->name('notifications.clear');
    Route::post('/notifications/mark-all-as-read', [NotificationsController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/get', [NotificationsController::class, 'getNotificationsData']);
    Route::post('/notifications/mark-as-read', [NotificationsController::class, 'markAsRead']);
    Route::delete('/notifications/clear', [NotificationsController::class, 'clearAll']);
    Route::get('/notifications/all', [NotificationsController::class, 'showAllNotifications'])->name('notifications.all');

    // Server Time routes
    Route::get('/server-time', function() {
        return response()->json(['server_time' => now()->toIso8601String()]);
    });
    Route::post('/push-subscription', [NotificationsController::class, 'storePushSubscription'])
    ->name('push-subscription.store');
    Route::get('/birthdays', [EmployeeBirthdayController::class, 'index'])->name('birthdays');
    Route::put('/leaves/update-status/{id}', [LeaveController::class, 'updateStatus'])->name('leaves.update-status');
    Route::put('/leaves/{id}/update-validation', [LeaveController::class, 'updateValidation'])->name('leaves.update-validation');
    Route::post('/employee/signature', [EmployeeController::class, 'updateSignature'])
        ->name('employee.signature.update')
        ->middleware(['auth', 'verified']);
        Route::get('/controller-analysis', [ControllerAnalysisController::class, 'index'])->name('controller.analysis');
        Route::get('/controller-analysis/pdf', [ControllerAnalysisController::class, 'downloadPdf'])->name('controller.analysis.pdf');
        Route::get('/controller-analysis/excel', [ControllerAnalysisController::class, 'downloadExcel'])->name('controller.analysis.excel');
        Route::get('/controller-analysis/word', [ControllerAnalysisController::class, 'downloadWord'])->name('controller.analysis.word');

    // Notification Routes
    Route::get('/notifications/get', [App\Http\Controllers\NotificationsController::class, 'getNotificationsData'])->name('notifications.get');
    Route::post('/notifications/mark-read', [App\Http\Controllers\NotificationsController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationsController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/check-updates', [App\Http\Controllers\NotificationsController::class, 'checkForUpdates'])->name('notifications.check-updates');
    Route::get('/notifications/all', [App\Http\Controllers\NotificationsController::class, 'showAllNotifications'])->name('notifications.all');

     // Report routes
     Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
     Route::post('/reports/loans', [ReportController::class, 'generateLoanReport'])->name('reports.loans');
     Route::post('/reports/contributions', [ReportController::class, 'generateContributionReport'])->name('reports.contributions');
     Route::post('/reports/attendances', [ReportController::class, 'generateAttendanceReport'])->name('reports.attendances');
     Route::post('/reports/leaves', [ReportController::class, 'generateLeaveReport'])->name('reports.leaves');
     Route::post('/reports/hirings', [ReportController::class, 'generateHiringReport'])->name('reports.hirings');
     Route::post('/reports/careers', [ReportController::class, 'generateCareerReport'])->name('reports.careers');
     Route::get('/reports/detailed-loan', [ReportController::class, 'generateDetailedLoanReport'])->name('reports.detailed-loan');

    // Web Push Notification Routes
    Route::get('/notifications/vapid-public-key', [App\Http\Controllers\NotificationsController::class, 'getVapidPublicKey'])->name('notifications.vapid-public-key');
    Route::get('/notifications/status', [App\Http\Controllers\NotificationsController::class, 'checkNotificationStatus'])->name('notifications.status');
    Route::post('/notifications/test', [App\Http\Controllers\NotificationsController::class, 'testPushNotification'])->name('notifications.test');
    Route::post('/notifications/mark-as-read/{id}', [NotificationsController::class, 'markAsRead']);
    Route::post('/notifications/mark-all-read', [NotificationsController::class, 'markAllAsRead']);

    Route::post('/employees/create-bulk-users', [EmployeeController::class, 'createBulkUsers'])
        ->name('employees.createBulkUsers');

    Route::get('resigned-employees', [EmployeeController::class, 'resigned'])
        ->name('employees.resigned');
    
    Route::get('terminated-employees', [EmployeeController::class, 'terminated'])
        ->name('employees.terminated');


    Route::get('/accountabilities/{accountability}/transfer', [AccountabilityController::class, 'transfer'])->name('accountabilities.transfer');
    Route::post('/accountabilities/{accountability}/process-transfer', [AccountabilityController::class, 'processTransfer'])->name('accountabilities.process-transfer');

    Route::post('/employees/update-profile', [EmployeeController::class, 'updateProfile'])->name('employees.update-profile');

    Route::post('/employee/profile', [EmployeeController::class, 'updateProfile'])
        ->name('employee.profile.update')
        ->middleware(['auth', 'verified']);

    Route::get('/user-departmental-activity', [UserActivityController::class, 'index'])->name('user-activity.index');

    // Account Management Routes
    Route::post('/account/link', [AccountController::class, 'link'])->name('account.link');
    Route::post('/account/switch/{linkedAccount}', [AccountController::class, 'switch'])->name('account.switch');
    Route::delete('/account/unlink/{linkedAccount}', [AccountController::class, 'unlink'])->name('account.unlink');

    // Activity Logs routes
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

    // New holiday calendar route
    Route::get('/holidays-calendar', [HolidayController::class, 'holidayCalendar'])->name('holidays.calendar');

    // New holiday events route
    Route::get('/holidays/events', [HolidayController::class, 'getEvents'])->name('holidays.events');

    // Login History route
    Route::get('/login-history', [LoginHistoryController::class, 'index'])->name('login.history');


    // Medical Products routes
    Route::resource('medical-products', MedicalProductController::class)->parameters([
        'medical-products' => 'product'
    ]);

    // Category routes
    Route::resource('categories', CategoryController::class);

    // Quotation routes
    Route::post('/send-quotation-request', [QuotationController::class, 'sendRequest'])->name('quotation.send-request');
    Route::get('/quotations', [QuotationController::class, 'index'])->name('quotations.index')->middleware(['auth', 'verified']);
    Route::put('/quotations/{id}', [QuotationController::class, 'update'])->name('quotations.update')->middleware(['auth', 'verified']);

    // Analytics routes
    Route::get('/analytics', [AnalyticsController::class, 'dashboard'])->name('analytics.dashboard');
    Route::get('/analytics/product/{productId}', [AnalyticsController::class, 'getProductAnalytics'])->name('analytics.product');

    // Holiday import and export routes
    Route::post('holidays/import', [App\Http\Controllers\HolidayController::class, 'import'])->name('holidays.import');
    Route::match(['get', 'post'], 'holidays/export', [App\Http\Controllers\HolidayController::class, 'export'])->name('holidays.export');

    // User online status routes
    Route::post('/user/update-last-seen', [UserController::class, 'updateLastSeen'])->name('user.update-last-seen');
    Route::get('/user/online-status', [UserController::class, 'getOnlineStatus'])->name('user.online-status');

    // Messaging routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/public/messenger/register/{token}', 'App\\Http\\Controllers\\MessagingController@publicRegister')->name('messaging.public-register');
        Route::post('/public/messenger/register/{token}', 'App\\Http\\Controllers\\MessagingController@storePublicUser')->name('messaging.store-public-user');
        Route::post('/public/messenger/login', 'App\\Http\\Controllers\\MessagingController@publicLogin')->name('messaging.public-login');
        Route::get('/messaging', 'App\\Http\\Controllers\\MessagingController@index')->name('messaging.index');
        Route::get('/messaging/{id}', 'App\\Http\\Controllers\\MessagingController@show')->name('messaging.show');
        Route::post('/messaging/{id}/message', 'App\\Http\\Controllers\\MessagingController@storeMessage')->name('messaging.store-message');
        Route::post('/messaging/{id}/mark-all-read', 'App\\Http\\Controllers\\MessagingController@markAllAsRead')->name('messaging.mark-all-read');
        Route::get('/messaging/{id}/files', 'App\\Http\\Controllers\\MessagingController@getSharedFiles')->name('messaging.files');
        Route::post('/messaging/create-conversation', 'App\\Http\\Controllers\\MessagingController@createConversation')->name('messaging.create-conversation');
        Route::get('/messaging/users/search', 'App\\Http\\Controllers\\MessagingController@searchUsers')->name('messaging.search-users');
        Route::get('/messaging/{id}/search', 'App\\Http\\Controllers\\MessagingController@searchMessages')->name('messaging.search-messages');
        
        // Attachment routes
        Route::get('/messaging/attachment/download/{id}', 'App\\Http\\Controllers\\AttachmentController@downloadMessageAttachment')->name('messaging.attachment.download');
        Route::get('/messaging/attachment/preview/{id}', 'App\\Http\\Controllers\\AttachmentController@previewMessageAttachment')->name('messaging.attachment.preview');
        Route::get('/messaging/attachment/{id}', 'App\\Http\\Controllers\\AttachmentController@getMessageAttachment')->name('messaging.attachment');
        
        // Media routes
        Route::post('/media/upload', 'App\\Http\\Controllers\\MediaController@upload')->name('media.upload');
        Route::get('/media/{id}', 'App\\Http\\Controllers\\MediaController@show')->name('media.show');
        Route::delete('/media/{id}', 'App\\Http\\Controllers\\MediaController@destroy')->name('media.destroy');
    });
});

// Route Management routes
Route::middleware(['auth', 'role:Super Admin'])->prefix('route-management')->name('route-management.')->group(function () {
    Route::get('/', [RouteManagementController::class, 'index'])->name('index');
    Route::get('/sync', [RouteManagementController::class, 'sync'])->name('sync');
    Route::post('/{route}/toggle', [RouteManagementController::class, 'toggleStatus'])->name('toggle');
    Route::put('/{route}', [RouteManagementController::class, 'update'])->name('update');
    Route::post('/bulk-toggle', [RouteManagementController::class, 'bulkToggle'])->name('bulk-toggle');
});

// Database Backup routes
Route::middleware(['auth', 'role:Super Admin'])->group(function () {
    Route::get('/database-backups', [DatabaseBackupController::class, 'index'])->name('database.backups');
    Route::get('/database-backups/create', [DatabaseBackupController::class, 'create'])->name('database.backup.create');
    Route::get('/database-backups/download/{filename}', [DatabaseBackupController::class, 'download'])->name('database.backup.download');
    Route::delete('/database-backups/delete/{filename}', [DatabaseBackupController::class, 'delete'])->name('database.backup.delete');
});

// Search routes
Route::get('/api/search', [SearchController::class, 'globalSearch'])->name('global.search');

// Get the App routes
Route::get('/get-the-app', [GetAppController::class, 'index'])->name('get-the-app');

// Message routes
Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');

// Media routes - for securely serving ringtones and other media
Route::get('/media/{type}/{filename}', [MediaController::class, 'getMediaFile']);

Auth::routes();
