<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StaffController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Staff Management API (Admin only)
Route::middleware(['web', 'auth', 'admin'])->prefix('staff')->group(function () {
    Route::get('/', [StaffController::class, 'index']);
    Route::post('/', [StaffController::class, 'store']);
    Route::put('/{staff}', [StaffController::class, 'update']);
    Route::delete('/{staff}', [StaffController::class, 'destroy']);
});
