<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/fetch-messages', [App\Http\Controllers\ChatController::class, 'fetchMessages'])->name('fetch.messages');


// Routes that require authentication
Route::middleware('auth')->group(function () {
     Route::get('/chat', [ChatController::class, 'index'])->name('chat');
     Route::post('/send-message', [ChatController::class, 'sendMessage'])->name('send.message');
     Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
