<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('welcome'); });

Route::group(['middleware' => ['auth']], function() {

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::resource('/topic', TopicController::class);
    Route::resource('/topic/{topic}/document', DocumentController::class);
    Route::get('/topic/{topic}/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/topic/{topic}/chat', [ChatController::class, 'store'])->name('chat.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::group(['middleware' => ['admin']], function() {
        
        Route::resource('/admin/user', UserController::class);

    });

});

require __DIR__.'/auth.php';
