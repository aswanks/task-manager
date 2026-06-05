<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiTaskController;

    Route::get('/tasks', [ApiTaskController::class, 'index']);
    Route::post('/tasks', [ApiTaskController::class, 'store']);
    Route::patch('/tasks/{id}/status', [ApiTaskController::class, 'updateStatus']);
    Route::get('/tasks/{id}/ai-summary', [ApiTaskController::class, 'aiSummary']);