<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RbacController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 1. Route Publik (Tanpa Autentikasi)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| 2. Route Terautentikasi (Auth Middleware Group)
|--------------------------------------------------------------------------
| Seluruh endpoint di dalam grup ini mewajibkan pengguna login terlebih dahulu.
*/
Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard & RBAC Overview
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/rbac', [RbacController::class, 'index'])
        ->name('rbac.index');

    /*
    |--------------------------------------------------------------------------
    | Profil Pengguna
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Endpoint Pengujian Permission (RBAC Testing)
    |--------------------------------------------------------------------------
    | Digunakan untuk memvalidasi pembatasan akses hak otorisasi Spatie.
    */
    Route::get('/test-inputer', function () {
        return 'Berhasil! Kamu memiliki izin membuat transaksi.';
    })->middleware('permission:create transactions')->name('test.inputer');

    Route::get('/test-approver', function () {
        return 'Berhasil! Kamu memiliki izin menyetujui transaksi.';
    })->middleware('permission:approve transactions')->name('test.approver');

    /*
    |--------------------------------------------------------------------------
    | Alur Transaksi: Inputer (Maker)
    |--------------------------------------------------------------------------
    */
    Route::get('/transactions', [TransactionController::class, 'index'])
        ->middleware('permission:view transactions')
        ->name('transactions.index');

    Route::get('/transactions/create', [TransactionController::class, 'create'])
        ->middleware('permission:create transactions')
        ->name('transactions.create');

    Route::post('/transactions', [TransactionController::class, 'store'])
        ->middleware('permission:create transactions')
        ->name('transactions.store');

    Route::post('/transactions/{transaction}/submit', [TransactionController::class, 'submit'])
        ->middleware('permission:submit transactions')
        ->name('transactions.submit');

    /*
    |--------------------------------------------------------------------------
    | Alur Transaksi: Checker (Pemeriksa)
    |--------------------------------------------------------------------------
    */
    Route::get('/transactions/checker', [TransactionController::class, 'checkerIndex'])
        ->middleware('permission:check transactions')
        ->name('transactions.checker');

    Route::post('/transactions/{transaction}/check', [TransactionController::class, 'check'])
        ->middleware('permission:check transactions')
        ->name('transactions.check');

    Route::post('/transactions/{transaction}/reject-checker', [TransactionController::class, 'rejectByChecker'])
        ->middleware('permission:check transactions')
        ->name('transactions.reject-checker');

    /*
    |--------------------------------------------------------------------------
    | Alur Transaksi: Approver (Penyetuju)
    |--------------------------------------------------------------------------
    */
    Route::get('/transactions/approver', [TransactionController::class, 'approverIndex'])
        ->middleware('permission:approve transactions')
        ->name('transactions.approver');

    Route::post('/transactions/{transaction}/approve', [TransactionController::class, 'approve'])
        ->middleware('permission:approve transactions')
        ->name('transactions.approve');

    Route::post('/transactions/{transaction}/reject-approver', [TransactionController::class, 'rejectByApprover'])
        ->middleware('permission:approve transactions')
        ->name('transactions.reject-approver');

    /*
    |--------------------------------------------------------------------------
    | Pengelolaan & Koreksi Transaksi (Edit, Update, Destroy)
    |--------------------------------------------------------------------------
    | Otorisasi peran:
    | - Admin & Superadmin: Mengoreksi & menghapus transaksi sesuai kebijakan sistem.
    | - Inputer: Terbatas hanya pada draft transaksi miliknya sendiri.
    */
    Route::get('/transactions/{transaction}/edit', [TransactionController::class, 'edit'])
        ->name('transactions.edit');

    Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])
        ->name('transactions.update');

    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])
        ->name('transactions.destroy');

    /*
    |--------------------------------------------------------------------------
    | Manajemen Pengguna & Akun (Superadmin / Admin)
    |--------------------------------------------------------------------------
    */
    Route::middleware('permission:manage users')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Log Audit Aktivitas Sistem
    |--------------------------------------------------------------------------
    */
    Route::get('/audit-logs', [AuditLogController::class, 'index'])
        ->middleware('permission:view audit logs')
        ->name('audit-logs.index');
});

/*
|--------------------------------------------------------------------------
| 3. Autentikasi (Laravel Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
