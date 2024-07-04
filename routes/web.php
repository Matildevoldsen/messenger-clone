<?php

use App\Http\Controllers\ChatShowController;
use App\Http\Controllers\SendMessageController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/{chat}', ChatShowController::class)->name('chat.show');
    Route::get('/', ChatShowController::class)->name('chat.new');
    Route::post('/messages/send/{chat}', SendMessageController::class)->name('message.send');
});
