<?php

use App\Http\Controllers\Api\UnitReportController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/reports', [UnitReportController::class, 'store']);
    Route::get('/reports', [UnitReportController::class, 'index']);
});


// add admin can update report status
Route::middleware(['auth:sanctum', 'can:manage-reports'])->group(function () {
    Route::patch('/reports/{report}/status', [UnitReportController::class, 'updateStatus']);
});
