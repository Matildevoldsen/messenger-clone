<?php

namespace App\Http\Controllers;

use App\Events\ChatUpdated;
use App\Events\DeletedChat;
use App\Models\Chat;
use Illuminate\Http\Request;

class DeleteChatController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Chat $chat)
    {
        $chat->delete();

        broadcast(new DeletedChat($chat));

        return redirect()->route('chat.new.show');
    }
}
