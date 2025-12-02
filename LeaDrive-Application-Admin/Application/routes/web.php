<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\Auth\AdminAuthController;

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard Kursus (Protected)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Registration Routes
Route::get('/register', [RegistrationController::class, 'showStep1'])->name('register.step1');
Route::post('/register', [RegistrationController::class, 'step1'])->name('register.step1.post');
Route::get('/register/step2', [RegistrationController::class, 'showStep2'])->name('register.step2');
Route::post('/register/step2', [RegistrationController::class, 'step2'])->name('register.step2.post');
Route::get('/register/step3', [RegistrationController::class, 'showStep3'])->name('register.step3');
Route::post('/register/step3', [RegistrationController::class, 'step3'])->name('register.step3.post');
Route::get('/register/back/{step}', [RegistrationController::class, 'back'])->name('register.back');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest routes (belum login)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    });

    // Protected routes (harus login sebagai admin)
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->name('dashboard');
        
        // User Management
        Route::resource('users', \App\Http\Controllers\Admin\AdminUserController::class);

        // Transaction Monitoring
        Route::get('/transactions', [\App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{id}', [\App\Http\Controllers\Admin\TransactionController::class, 'show'])->name('transactions.show');
        Route::post('/transactions/{id}/status', [\App\Http\Controllers\Admin\TransactionController::class, 'updateStatus'])->name('transactions.updateStatus');

        // Reports
        Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');

        // Profile
        Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'show'])->name('profile.show');
        Route::put('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');



        Route::get('/verifikasi', [\App\Http\Controllers\Admin\VerificationController::class, 'index'])->name('verifikasi');
        Route::get('/verifikasi/{id}', [\App\Http\Controllers\Admin\VerificationController::class, 'show'])->name('verifikasi.show');
        Route::post('/verifikasi/{id}/approve', [\App\Http\Controllers\Admin\VerificationController::class, 'approve'])->name('verifikasi.approve');
        Route::post('/verifikasi/{id}/reject', [\App\Http\Controllers\Admin\VerificationController::class, 'reject'])->name('verifikasi.reject');
        Route::post('/verifikasi/{id}/pending', [\App\Http\Controllers\Admin\VerificationController::class, 'setPending'])->name('verifikasi.pending');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    });
});

// User Profile routes (outside admin group, protected by general 'auth' middleware if needed)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// API untuk testing kredensial (opsional, bisa dihapus di production)
Route::post('/api/admin/check-credentials', [AdminAuthController::class, 'checkCredentials'])->name('api.admin.check');
