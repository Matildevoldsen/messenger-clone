<?php

namespace App\Http\Controllers;

use App\Events\UserTyping;
use App\Models\Chat;
use Illuminate\Http\Request;

class TypingController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Chat $chat)
    {
        $message = $chat->messages()->where('user_id', auth()->id())->latest()->first();

        if ($message) {
            $message->update([
                'is_typing' => $request->input('is_typing', false),
            ]);
        } else {
            $chat->messages()->create([
                'user_id' => auth()->id(),
                'content' => '',
                'is_typing' => $request->input('is_typing', true),
            ]);
        }

        broadcast(new UserTyping($chat, auth()->user()));
    }
}
