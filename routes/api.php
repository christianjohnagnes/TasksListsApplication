<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TasksController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\RegisterController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware(['api', 'guest'])->group(function () {
    Route::post('login', [LoginController::class, 'store']);
    Route::post('register', [RegisterController::class, 'store']);
});

Route::middleware(['api', 'auth:sanctum'])->group(function () {
    Route::prefix('tasks')->group(function () {
        Route::post('create', [TasksController::class, 'store']); // Create a new task
        Route::get('{category}', [TasksController::class, 'show']); // Show a specific task
        Route::put('{task_id}', [TasksController::class, 'update']); // Update a specific task
        Route::delete('{task_id}', [TasksController::class, 'destroy']); // Delete a specific task

        Route::put('update/status', [StatusController::class, 'update']); // Update Tasks status
    });
});