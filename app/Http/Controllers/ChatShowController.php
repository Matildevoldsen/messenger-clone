<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Models\Chat;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChatShowController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Chat $chat): \Inertia\Response
    {
        $query = $request->input('query', '');
        $encodedCursor = $request->input('cursor');
        $cursor = $encodedCursor ? \Illuminate\Pagination\Cursor::fromEncoded($encodedCursor) : null;
        $messages = $chat->messages()
            ->with('user')
            ->latest()
            ->cursorPaginate(10, ['*'], 'cursor', $cursor);
        $user = auth()->user();

        $chats = $query ? Chat::search($query)
            ->get() : Chat::whereHas('users', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('users')->get();

        return Inertia::render('Chat/Show', [
            'chat' => ChatResource::make($chat),
            'chats' => ChatResource::collection($chats),
            'messages' => MessageResource::collection($messages),
            'pagination' => [
                'next_cursor' => $messages->nextCursor()?->encode(),
            ],
        ]);
    }
}
