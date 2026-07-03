<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
Route::get('/products/{slug}', [HomeController::class, 'product'])->name('product.show');
Route::get('/cart', [HomeController::class, 'cart'])->name('cart');
Route::post('/cart/items', [HomeController::class, 'addToCart'])->name('cart.add');
Route::patch('/cart/items/{key}', [HomeController::class, 'updateCart'])->name('cart.update');
Route::delete('/cart/items/{key}', [HomeController::class, 'removeCart'])->name('cart.remove');
Route::get('/login', [HomeController::class, 'customerLogin'])->name('customer.login');
Route::post('/login', [HomeController::class, 'customerLoginStore'])->name('customer.login.store');
Route::get('/register', [HomeController::class, 'customerRegister'])->name('customer.register');
Route::post('/register', [HomeController::class, 'customerRegisterStore'])->name('customer.register.store');
Route::get('/auth/google/redirect', [HomeController::class, 'customerGoogleRedirect'])->name('customer.google.redirect');
Route::get('/auth/google/callback', [HomeController::class, 'customerGoogleCallback'])->name('customer.google.callback');
Route::get('/forgot-password', [HomeController::class, 'forgotPassword'])->middleware('guest')->name('password.request');
Route::post('/forgot-password', [HomeController::class, 'forgotPasswordStore'])->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', [HomeController::class, 'resetPassword'])->middleware('guest')->name('password.reset');
Route::post('/reset-password', [HomeController::class, 'resetPasswordStore'])->middleware('guest')->name('password.store');
Route::get('/dashboard', [HomeController::class, 'customerDashboard'])->name('customer.dashboard');
Route::get('/email/verify', [HomeController::class, 'emailVerificationNotice'])->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [HomeController::class, 'emailVerificationVerify'])->middleware(['auth', 'signed', 'throttle:6,1'])->name('verification.verify');
Route::post('/email/verification-notification', [HomeController::class, 'emailVerificationSend'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');
Route::get('/checkout', [HomeController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [HomeController::class, 'placeOrder'])->name('checkout.place');
Route::get('/order-success', [HomeController::class, 'orderSuccess'])->name('order.success.empty');
Route::get('/order-success/{order:reference}', [HomeController::class, 'orderSuccess'])->name('order.success');
Route::get('/admin/login', [HomeController::class, 'adminLogin'])->name('admin.login');
Route::post('/admin/login', [HomeController::class, 'adminLoginStore'])->name('admin.login.store');
Route::get('/admin/register', [HomeController::class, 'adminRegister'])->name('admin.register');
Route::post('/admin/register', [HomeController::class, 'adminRegisterStore'])->name('admin.register.store');
Route::get('/admin', [HomeController::class, 'admin'])->name('admin.dashboard');
Route::patch('/admin/orders/{order}/status', [HomeController::class, 'adminUpdateOrderStatus'])->name('admin.orders.status');
Route::post('/admin/products', [HomeController::class, 'adminStoreProduct'])->name('admin.products.store');
Route::patch('/admin/products/{product}', [HomeController::class, 'adminUpdateProduct'])->name('admin.products.update');
Route::delete('/admin/products/{product}', [HomeController::class, 'adminDestroyProduct'])->name('admin.products.destroy');
Route::post('/logout', [HomeController::class, 'logout'])->name('logout');
Route::post('/newsletter', [HomeController::class, 'newsletter'])->name('newsletter');
Route::post('/generate', [HomeController::class, 'generate'])
    ->middleware('throttle:10,1')
    ->name('generate');
