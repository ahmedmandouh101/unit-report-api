<?php

use App\Http\Controllers\Api\UnitReportController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/reports', [UnitReportController::class, 'store']);
    Route::get('/reports', [UnitReportController::class, 'index']);
});


