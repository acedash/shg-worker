<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WorkerDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
})->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', function () {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('worker.dashboard');
    })->name('dashboard');

    Route::get('/worker/dashboard', [WorkerDashboardController::class, 'index'])->name('worker.dashboard');
    Route::get('/worker/daily-activity', [WorkerDashboardController::class, 'showForm'])->name('worker.daily-activity.form');
    Route::get('/worker/submissions', [WorkerDashboardController::class, 'submissions'])->name('worker.submissions');
    Route::get('/daily-activity-photos/{activity}/{photoIndex}', [WorkerDashboardController::class, 'showPhoto'])->name('daily-activity.photos.show');
    Route::get('/daily-activity-documents/{activity}/{documentIndex}', [WorkerDashboardController::class, 'showDocument'])->name('daily-activity.documents.show');
    Route::post('/worker/daily-activity', [WorkerDashboardController::class, 'store'])->name('worker.daily-activity.store');
    Route::post('/worker/reports/monthly/final-remark', [WorkerDashboardController::class, 'saveMonthlyFinalRemark'])->name('worker.reports.monthly.final-remark');
    Route::get('/worker/reports/daily/{activity}', [WorkerDashboardController::class, 'showDailyReport'])->name('worker.reports.daily');
    Route::get('/worker/reports/monthly', [WorkerDashboardController::class, 'downloadMonthlyReport'])->name('worker.reports.monthly');
    Route::get('/worker/reports/monthly/pdf', [WorkerDashboardController::class, 'downloadMonthlyPdfReport'])->name('worker.reports.monthly.pdf');
    Route::get('/worker/reports/monthly/share/whatsapp', [WorkerDashboardController::class, 'shareMonthlyReportOnWhatsApp'])->name('worker.reports.monthly.whatsapp');

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function (): void {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/workers/{user}/reports/daily/{activity}', [AdminDashboardController::class, 'showWorkerDailyReport'])->name('workers.reports.daily');
        Route::get('/workers/{user}/reports/monthly', [AdminDashboardController::class, 'downloadWorkerMonthlyReport'])->name('workers.reports.monthly');
        Route::get('/workers/{user}/reports/monthly/pdf', [AdminDashboardController::class, 'downloadWorkerMonthlyPdfReport'])->name('workers.reports.monthly.pdf');
        Route::get('/workers/{user}/reports/monthly/share/whatsapp', [AdminDashboardController::class, 'shareWorkerMonthlyReportOnWhatsApp'])->name('workers.reports.monthly.whatsapp');
    });
});
