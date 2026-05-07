<?php

use App\Http\Controllers\user as AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login.form'));

// Questionnaire — publicly accessible
Route::get('/questionnaire',          fn () => view('questionnaire'))->name('questionnaire');
Route::get('/questionnaire/download', function () {
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('questionnaire_pdf')
        ->setPaper('a4', 'portrait');
    return $pdf->download('ACLC-Clinic-Questionnaire.pdf');
})->name('questionnaire.download');

Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('login')->middleware('throttle:5,1');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin & Staff shared routes (module-gated)
    Route::middleware(['role:admin,staff'])->group(function () {

        Route::middleware('module:categories')->group(function () {
            Route::resource('categories', CategoryController::class)->except(['show']);
        });

        Route::middleware('module:medications')->group(function () {
            Route::resource('medications', MedicationController::class)->except(['show']);
            Route::get('/medications/archive',           [MedicationController::class, 'archive'])->name('medications.archive');
            Route::patch('/medications/{id}/restore',    [MedicationController::class, 'restore'])->name('medications.restore');
            Route::delete('/medications/{id}/force-delete', [MedicationController::class, 'forceDelete'])->name('medications.force-delete');
        });

        Route::middleware('module:requests')->group(function () {
            Route::get('/requests',                                [RequestController::class, 'index'])->name('requests.index');
            Route::get('/requests/create',                         [RequestController::class, 'create'])->name('requests.create');
            Route::post('/requests',                               [RequestController::class, 'store'])->name('requests.store');
            Route::patch('/requests/{medicationRequest}/approve',  [RequestController::class, 'approve'])->name('requests.approve');
            Route::patch('/requests/{medicationRequest}/reject',   [RequestController::class, 'reject'])->name('requests.reject');
            Route::patch('/requests/{medicationRequest}/disburse', [RequestController::class, 'disburse'])->name('requests.disburse');
        });

        Route::middleware('module:reports')->group(function () {
            Route::get('/reports/restock',          [ReportController::class, 'restockReport'])->name('reports.restock');
            Route::get('/reports/restock/pdf',      [ReportController::class, 'restockPdf'])->name('reports.restock.pdf');
            Route::get('/reports/visits',           [ReportController::class, 'visitsReport'])->name('reports.visits');
            Route::get('/reports/visits/pdf',       [ReportController::class, 'visitsPdf'])->name('reports.visits.pdf');
        });
    });

    // Admin-only routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/staff',                           [StaffController::class, 'index'])->name('staff.index');
        Route::post('/staff',                          [StaffController::class, 'store'])->name('staff.store');
        Route::patch('/staff/{staff}/toggle-active',   [StaffController::class, 'toggleActive'])->name('staff.toggle-active');
        Route::delete('/staff/{staff}',                [StaffController::class, 'destroy'])->name('staff.destroy');
        Route::patch('/staff/modules/{module}/toggle', [StaffController::class, 'toggleModule'])->name('staff.modules.toggle');
    });

    // Password change (admin & staff only)
    Route::get('/profile/change-password',  [ProfileController::class, 'changePasswordForm'])->name('profile.change-password');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password.update');

});
