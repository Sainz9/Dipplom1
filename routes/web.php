<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GamesController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;     
use App\Http\Controllers\CheckoutController;  
use App\Http\Controllers\PaymentController;   
use App\Http\Controllers\AdminOrderController;
use App\Models\Order;
use App\Http\Controllers\Auth\PasswordResetLinkController; 

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

Route::get('/about',[GamesController::class,'about'])->name('about');


Route::get('/games/{id}', [GameController::class, 'show'])->name('game.show');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

// Төлбөр боловсруулах
Route::post('/payment/process', [PaymentController::class, 'process'])->name('payment.process');
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
Route::get('/game/{id}/download', [GameController::class, 'download'])->name('game.download');
Route::get('/dashboard', function () {
    $orders = App\Models\Order::where('user_id', auth()->id())
                ->where('status', 'paid')
                ->with('game')
                ->latest()
                ->get()
                ->unique('game_id'); // <--- ЭНЭ МӨР ДАВХАРДЛЫГ #
    return view('dashboard', compact('orders'));
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/payment/transfer/{order}', [App\Http\Controllers\PaymentController::class, 'showTransfer'])->name('payment.transfer');
Route::post('/payment/confirm/{order}', [App\Http\Controllers\PaymentController::class, 'confirm'])->name('payment.confirm');
Route::get('/payment/wait/{order}', [App\Http\Controllers\PaymentController::class, 'wait'])->name('payment.wait');
Route::post('/admin/orders/{id}/approve', [App\Http\Controllers\AdminOrderController::class, 'approve'])->name('admin.order.approve');
Route::delete('/admin/orders/{id}', [App\Http\Controllers\AdminOrderController::class, 'destroy'])->name('admin.order.destroy');
Route::post('/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');
use App\Http\Controllers\ReviewController;

Route::middleware(['auth'])->group(function () {
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    // ЭНЭ 2 МӨР ЗААВАЛ БАЙХ ЁСТОЙ
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

Route::get('/checkout/{id}', [GameController::class, 'checkout'])->name('checkout');
Route::get('/order-success', function () {
    return view('order_success'); // resources/views/order_success.blade.php файл байх ёстой
})->name('order.success');

Route::delete('/payment/cancel/{orderId}', [PaymentController::class, 'cancel'])->name('payment.cancel');
Route::post('/admin/categories', [GameController::class, 'storeCategory'])->name('categories.store');
Route::get('/about', [GameController::class, 'about'])->name('about');

