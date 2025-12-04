<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\RegisteredUserController;
use App\Http\Controllers\AdminController; // <--- Энийг нэмэх ёстой!
use Illuminate\Support\Facades\Route;

// ЗОЧИН (Нэвтрээгүй) АДМИНУУД
Route::prefix('admin')->middleware('guest:admin')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('admin.register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [LoginController::class, 'create'])->name('admin.login');
    Route::post('login', [LoginController::class, 'store']);
});

// НЭВТЭРСЭН АДМИНУУД
Route::prefix('admin')->middleware('auth:admin')->group(function () {

    // ✅ ЗАСВАР: AdminController-ийн index функцийг дуудаж байна.
    // Ингэснээр $categories болон $games хувьсагч Dashboard руу очно.
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // CRUD Үйлдлүүд (Тоглоом нэмэх, устгах)
    Route::post('/category', [AdminController::class, 'storeCategory'])->name('admin.category.store');
    Route::post('/game', [AdminController::class, 'storeGame'])->name('admin.game.store');
    Route::delete('/game/{id}', [AdminController::class, 'destroyGame'])->name('admin.game.destroy');

    Route::post('logout', [LoginController::class, 'destroy'])->name('admin.logout');
});