<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KpiController;
use App\Http\Controllers\EvidenceController;



Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function (){
    Route::post('/logout',[AuthController::class, 'logout']);
    Route::get('/me',[AuthController::class, 'me']);
    Route::apiResource('kpis', KpiController::class)->only(['index', 'store', 'show']);

    Route::post('kpis/{kpi}/submit',  [KpiController::class, 'submit']);
    Route::post('kpis/{kpi}/approve', [KpiController::class, 'approve']);
    Route::post('kpis/{kpi}/reject',  [KpiController::class, 'reject']);

    Route::get('kpis/{kpi}/evidence',  [EvidenceController::class, 'index']);
    Route::post('kpis/{kpi}/evidence', [EvidenceController::class, 'store']);
    Route::get('evidence/{evidence}/download', [EvidenceController::class, 'download']);

    Route::get('kpis/{kpi}/audit', [KpiController::class, 'audit']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
