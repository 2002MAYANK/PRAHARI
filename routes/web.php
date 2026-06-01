<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PrahariController;
use App\Http\Controllers\Admin\CaseController;
use App\Http\Controllers\Admin\ChallanController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SubadminController;
use App\Models\SubAdmin;


// Route::get('/admin/subadmins', function () {
//     $subadmins = SubAdmin::all();

//         return view('admin/subadmin', compact('subadmins'));

Route::view('/', 'admin.login');
Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/logout', [AuthController::class, 'logout']);

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::get('/reports', [ReportController::class, 'index']);
        Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
        Route::get('/settings', [SettingsController::class, 'index']);
        Route::post('/settings', [SettingsController::class, 'update']);
        // Route::resource('/subadmins', SubadminController::class, );
         Route::resource('/subadmins', SubadminController::class);


        Route::resource('praharis', PrahariController::class);
        Route::resource('cases', CaseController::class);
        Route::resource('challans', ChallanController::class);
        Route::post('payments/{id}/approve', [PaymentController::class, 'approve'])->name('payments.approve');
        Route::post('payments/{id}/reject', [PaymentController::class, 'reject'])->name('payments.reject');
        Route::resource('payments', PaymentController::class);
    });
});
