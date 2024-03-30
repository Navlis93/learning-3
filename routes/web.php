<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdminMiddleware;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [TodoController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::post('/create', [TodoController::class, 'create'])->middleware(['auth', 'verified'])->name('todo.store');

Route::get('/view/{id}', [TodoController::class, 'view'])->middleware(['auth', 'verified'])->name('todo.view');
Route::get('/view/image/{id}', [TodoController::class, 'viewImage'])->middleware(['auth', 'verified'])->name('todo.viewImage');
Route::post('/view/image/{id}', [TodoController::class, 'deleteImage'])->middleware(['auth', 'verified'])->name('todo.deleteImage');
Route::post('/view/{id}', [TodoController::class, 'update'])->middleware(['auth', 'verified'])->name('todo.update');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', IsAdminMiddleware::class])->group(function() {
    Route::get('/admin', function () {
        return view('admin');
    });
});

require __DIR__.'/auth.php';
