<?php

use App\Http\Controllers\ChatShowController;
use App\Http\Controllers\DeleteChatController;
use App\Http\Controllers\NewChatController;
use App\Http\Controllers\SendMessageController;
use App\Http\Controllers\ShowNewChatController;
use App\Http\Controllers\TypingController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::put('/chats/{chat}/typing', TypingController::class)->name('chat.user.typing');
    Route::get('/{chat}', ChatShowController::class)->name('chat.show');
    Route::get('/', ShowNewChatController::class)->name('chat.new.show');
    Route::post('/create/chat', NewChatController::class)->name('chat.new');
    Route::delete('/chats/{chat}/delete', DeleteChatController::class)->name('chat.delete');
    Route::post('/messages/send/{chat}', SendMessageController::class)->name('message.send');
});
