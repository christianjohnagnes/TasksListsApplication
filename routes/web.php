<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\HomeController;
// use App\Http\Controllers\TasksController;
// use App\Http\Controllers\Auth\LoginController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('', function () {
    return view('welcome');
});

// Route::prefix('')->group(function () {
//     Route::get('', [LoginController::class, 'index']);
//     Route::get('register', [LoginController::class, 'index']);
// })->middleware('guest');

// Auth::routes();
// Route::prefix('home')->group(function () {
//     Route::get('', [HomeController::class, 'index'])->name('home');
//     Route::prefix('tasks')->group(function () {
//         Route::prefix('show')->group(function () {
//             Route::post('', [TasksController::class, 'show']);
//             Route::get('description/{id}', [TasksController::class, 'showDescription']);
//             Route::get('update/{id}', [TasksController::class, 'showDetail']);
//         });
//         Route::post('create', [TasksController::class, 'store']);
//         Route::post('update', [TasksController::class, 'update']);
//         Route::get('progress/{id}/{outcome}', [TasksController::class, 'progress']);
//         Route::get('delete/{id}', [TasksController::class, 'destroy']);
//     });
// });

