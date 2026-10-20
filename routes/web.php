
<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RbacController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman Utama
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Halaman RBAC
|--------------------------------------------------------------------------
*/

Route::get('/rbac', [RbacController::class, 'index'])
    ->middleware('auth')
    ->name('rbac.index');

/*
|--------------------------------------------------------------------------
| Profil Pengguna
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Pengujian Permission
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/test-inputer', function () {
        return 'Berhasil! Kamu memiliki izin membuat transaksi.';
    })->middleware('permission:create transactions');

    Route::get('/test-approver', function () {
        return 'Berhasil! Kamu memiliki izin menyetujui transaksi.';
    })->middleware('permission:approve transactions');
});

/*
|--------------------------------------------------------------------------
| Semua Fitur Transaksi
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    | Inputer
    */

    // Daftar transaksi
    Route::get('/transactions', [
        TransactionController::class,
        'index',
    ])
        ->middleware('permission:view transactions')
        ->name('transactions.index');

    // Form input transaksi
    Route::get('/transactions/create', [
        TransactionController::class,
        'create',
    ])
        ->middleware('permission:create transactions')
        ->name('transactions.create');

    // Simpan transaksi
    Route::post('/transactions', [
        TransactionController::class,
        'store',
    ])
        ->middleware('permission:create transactions')
        ->name('transactions.store');

    // Ajukan transaksi ke Checker
    Route::post('/transactions/{transaction}/submit', [
        TransactionController::class,
        'submit',
    ])
        ->middleware('permission:submit transactions')
        ->name('transactions.submit');


    /*
    | Checker
    */

    // Daftar transaksi yang menunggu pemeriksaan
    Route::get('/transactions/checker', [
        TransactionController::class,
        'checkerIndex',
    ])
        ->middleware('permission:check transactions')
        ->name('transactions.checker');

    // Lolos pemeriksaan dan teruskan ke Approver
    Route::post('/transactions/{transaction}/check', [
        TransactionController::class,
        'check',
    ])
        ->middleware('permission:check transactions')
        ->name('transactions.check');

    // Tolak transaksi pada tahap pemeriksaan
    Route::post('/transactions/{transaction}/reject-checker', [
        TransactionController::class,
        'rejectByChecker',
    ])
        ->middleware('permission:check transactions')
        ->name('transactions.reject-checker');


    /*
    | Approver
    */

    // Daftar transaksi yang menunggu persetujuan
    Route::get('/transactions/approver', [
        TransactionController::class,
        'approverIndex',
    ])
        ->middleware('permission:approve transactions')
        ->name('transactions.approver');

    // Setujui transaksi
    Route::post('/transactions/{transaction}/approve', [
        TransactionController::class,
        'approve',
    ])
        ->middleware('permission:approve transactions')
        ->name('transactions.approve');

    // Tolak transaksi pada tahap persetujuan
    Route::post('/transactions/{transaction}/reject-approver', [
        TransactionController::class,
        'rejectByApprover',
    ])
        ->middleware('permission:approve transactions')
        ->name('transactions.reject-approver');


    /*
    | Edit & Hapus Transaksi (Admin/Superadmin: Semua status, Inputer: Draft miliknya)
    */
    Route::get('/transactions/{transaction}/edit', [
        TransactionController::class,
        'edit',
    ])->name('transactions.edit');

    Route::put('/transactions/{transaction}', [
        TransactionController::class,
        'update',
    ])->name('transactions.update');

    Route::delete('/transactions/{transaction}', [
        TransactionController::class,
        'destroy',
    ])->name('transactions.destroy');


    /*
    | Superadmin: Manajemen Pengguna & Akun
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
    | Superadmin & Admin: Log Audit Sistem
    */
    Route::get('/audit-logs', [AuditLogController::class, 'index'])
        ->middleware('permission:view audit logs')
        ->name('audit-logs.index');
});

/*
|--------------------------------------------------------------------------
| Authentication Laravel Breeze
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
