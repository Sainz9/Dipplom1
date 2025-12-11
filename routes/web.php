<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GamesController;
use App\Http\Controllers\CategoryController;



// 1. НҮҮР ХУУДАС (Тоглоомууд харагдах)
Route::get('/', [GameController::class, 'index'])->name('home');
Route::get('/games/{id}', [GameController::class, 'show'])->name('game.show');

// 2. ЭНГИЙН ХЭРЭГЛЭГЧИЙН AUTH (login, register)
require __DIR__.'/auth.php';

// 3. АДМИН AUTH (admin/login, admin/dashboard)
// Та admin-auth.php дотор 'prefix' => 'admin' гэж бичсэн байгаа тул
// энд дахиад prefix бичих хэрэггүй. Шууд дуудна.
require __DIR__.'/admin-auth.php';
    

// 4. ЭНГИЙН ХЭРЭГЛЭГЧИЙН DASHBOARD
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 5. PROFILE
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
   Route::delete('/admin/game/{id}', [GamesController::class, 'destroyGame'])->name('admin.game.destroy');

   Route::delete('/admin/game/{id}', [GamesController::class, 'destroyGame'])
    ->name('admin.game.destroy');
Route::get('/admin/game/{id}/edit', [GamesController::class, 'editGame'])
    ->name('admin.game.edit');
  
Route::prefix('admin/game')->group(function () {
    
    // EDIT
    Route::get('/{id}/edit', [GamesController::class, 'edit'])->name('admin.game.edit');
    
    // UPDATE (PUT)
    Route::put('/{id}/update', [GamesController::class, 'update'])->name('admin.game.update');
  });  

    Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('admin.category.destroy');


