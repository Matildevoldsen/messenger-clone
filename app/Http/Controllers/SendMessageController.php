<?php

namespace App\Http\Controllers;

use App\Events\ChatUpdated;
use App\Events\MessageSent;
use App\Models\Chat;
use Illuminate\Http\Request;

class SendMessageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Chat $chat)
    {
        $attachments = [];

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');

                $attachments[] = [
                    'path' => $path,
                    'type' => $file->getMimeType()
                ];
            }
        }

        $message = $chat->messages()->create([
            'user_id' => auth()->id(),
            'content' => $request->input('content', ''),
            'is_typing' => false,
            'attachments' => $attachments
        ]);

        broadcast(new MessageSent($chat, $message));
        broadcast(new ChatUpdated($chat));

        return redirect()->back();
    }
}
