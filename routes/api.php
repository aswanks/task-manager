<?php
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tasks',              [ApiTaskController::class, 'index']);
    Route::post('/tasks',             [ApiTaskController::class, 'store']);
    Route::patch('/tasks/{id}/status',[ApiTaskController::class, 'updateStatus']);
    Route::get('/tasks/{id}/ai-summary', [ApiTaskController::class, 'aiSummary']);
});

?>