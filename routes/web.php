<?php

use App\Http\Controllers\dashboardController;
use App\Models\IntecSdrApplication;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocTypeController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\CalendarController;
// Route::get('/', function () {
//     return view('applicant');
// });

Auth::routes();

Route::get('/admin/document', [DocTypeController::class, 'index'])->name('docType.index');
Route::post('/admin/document/submitted', [DocTypeController::class, 'store'])->name('docType.submitForm');
Route::put('admin/document/{type_id}', [DocTypeController::class, 'update'])->name('document.update');
Route::delete('admin/document/{type_id}', [DocTypeController::class, 'destroy'])->name('document.destroy');
Route::get('/admin/status', [StatusController::class, 'index'])->name('status.index');
Route::post('/admin/status/submitted', [StatusController::class, 'store'])->name('status.submitForm');
Route::put('/admin/status/update/{status_id}', [StatusController::class, 'update'])->name('status.update');
Route::delete('/admin/status/delete/{status_id}', [StatusController::class, 'destroy'])->name('status.destroy');
Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
Route::post('/admin/submitted', [AdminController::class, 'store'])->name('admin.submitForm');
Route::put('/admin/update/{admin_id}', [AdminController::class, 'update'])->name('admin.update');
Route::delete('/admin/delete/{admin_id}', [AdminController::class, 'destroy'])->name('admin.destroy');
Route::get('/developer', [DeveloperController::class, 'index'])->name('developer.index');
Route::post('/developer/submitted', [DeveloperController::class, 'store'])->name('developer.submitForm');
Route::put('/developer/update/{dev_id}', [DeveloperController::class, 'update'])->name('developer.update');
Route::delete('/developer/delete/{dev_id}', [DeveloperController::class, 'destroy'])->name('developer.destroy');
Route::get('/application/developer', [DeveloperController::class, 'developerView'])->name('developer.developerView');
// Route::get('/applicant/applications/{applications_id}', [ApplicationController::class, 'newapplicationC'])->name('newapplicationC');
Route::get('/applicant/applications/{applications_id}/pending', [ApplicationController::class, 'pendingC'])->name('pendingC');
Route::get('/applicant/applications/{applications_id}/completed', [ApplicationController::class,'completedC'])->name('completedC');
Route::get('/applicant/applications/{applications_id}/rejected', [ApplicationController::class,'rejectedC'])->name('rejectedC');
Route::delete('/applications/{applications_id}/delete', [ApplicationController::class, 'delete'])->name('applications.delete');
Route::get('/applications/{applications_id}', [ApplicationController::class, 'new'])->name('newapplication');
Route::put('/applications/{applications_id}/confirm', [ApplicationController::class, 'confirmMeeting'])->name('applications.confirmMeeting');
Route::get('/applications/{applications_id}/pending', [ApplicationController::class, 'pending'])->name('pending');
Route::put('/applications/{applications_id}/approve', [ApplicationController::class, 'approve'])->name('approve');
Route::get('/applications/{applications_id}/approve-display', [ApplicationController::class, 'approveDisplay'])->name('approveDisplay');
Route::get('/applications/{applications_id}/reject-display', [ApplicationController::class, 'rejectDisplay'])->name('rejectDisplay');
Route::put('/applications/{applications_id}/reject', [ApplicationController::class, 'reject'])->name('reject');
Route::get('/developer/applications/{applications_id}/inprogress', [ApplicationController::class, 'inprogress'])->name('inprogress');
Route::get('/admin/applications/{applications_id}/inprogress', [ApplicationController::class, 'inprogressA'])->name('inprogressA');
Route::get('/applicant/applications/{applications_id}', [ApplicationController::class, 'inprogressC'])->name('inprogressC');
Route::post('/applications/{applications_id}/completed', [ApplicationController::class, 'displayCompletedD'])->name('applications.completed');
Route::get('/admin/applications/{applications_id}/completed', [ApplicationController::class, 'displayCompletedA'])->name('displayCompletedA');
Route::get('/applications/{applications_id}/completed', [ApplicationController::class, 'displayCompletedD'])->name('completedDisplayD');
Route::get('/applicant/applications', [ApplicationController::class, 'applicationClientView'])->name('application.applicationClientView');
Route::get('/developer/applications', [ApplicationController::class, 'applicationDevView'])->name('application.applicationDevView');
Route::get('/applications', [ApplicationController::class, 'applicationAdminView'])->name('application.applicationAdminView');
Route::get('/get-file/{id}', [ApplicationController::class, 'file']);
Route::get('/get-file/{type}/{id}', [ApplicationController::class, 'fileFinal'])->name('get-file');
Route::get('/application/{applications_id}', [ApplicationController::class, 'comletedDfinal']);
Route::get('/applications/count', [ApplicationController::class, 'countStatus'])->name('status.count');
Route::get('admin/dashboard', [dashboardController::class, 'dashboardA']);
Route::get('/calendar-data', function() {
    $events = IntecSdrApplication::whereNotNull('date_confirm')->get(['date_confirm', 'applications_system_name' , 'applicant_id' , 'applications_id']);
    return response()->json($events);
});
Route::get('/calendar-data', [CalendarController::class, 'getCalendarData']);
Route::get('/developer/dashboard', [dashboardController::class, 'dashboardD']);
Route::get('/generate-report/{applications_id}', [ReportController::class, 'generateReport'])->name('generate.report');

require __DIR__.'/auth.php';
Route::get('register', [RegisteredUserController::class, 'create'])
    ->name('register');
Route::post('register', [RegisteredUserController::class, 'store']);
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::middleware(['auth'])->group(function () {
    Route::get('admin/dashboard', [dashboardController::class, 'dashboardA'])->name('admin.dashboard');
    Route::get('applicant/dashboard', [dashboardController::class, 'dashboardC'])->name('staff.dashboard');
    Route::get('developer/dashboard', [dashboardController::class, 'dashboardD'])->name('developer.dashboard');
});
Route::middleware(['auth', RoleMiddleware::class . ':admin'])->group(function () {
    Route::get('admin/dashboard', [dashboardController::class, 'dashboardA'])->name('admin.dashboard');
});
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [dashboardController::class, 'dashboardA'])->name('admin.dashboard');
});
Route::middleware(['auth', RoleMiddleware::class . ':developer'])->group(function () {
    Route::get('developer/dashboard', [dashboardController::class, 'dashboardD'])->name('developer.dashboard');
});
Route::post('/applicant/update', [DashboardController::class, 'updateApplicantDetails'])->name('applicantStore');
Route::get('/applicant/form', [ApplicantController::class, 'index'])->name('applicant.index');
Route::post('/applicant/submitted', [ApplicantController::class, 'applicantStore'])->name('applicantStore');
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
Route::get('/calendar-data-user', [CalendarController::class, 'getCalendarDataUser']);
Route::get('/admin/staff', [ApplicantController::class, 'staff'])->name('staff');
Route::delete('/admin/staff/delete/{applicant_id}', [ApplicantController::class, 'destroy'])->name('destroy');