<?php

namespace App\Http\Controllers;

use App\Events\ChatUpdated;
use App\Events\NewChat;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;

class NewChatController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'users' => 'required|array',
            'users.*' => 'exists:users,id'
        ]);

        $userIds = array_merge($validated['users'], [auth()->id()]);

        sort($userIds);

        if (count($userIds) === 2) {
            $existingStandaloneChat = Chat::where('type', 'standalone')
                ->whereHas('users', function ($query) use($userIds) {
                    $query->whereIn('user_id', $userIds)
                        ->havingRaw('COUNT(DISTINCT user_id) = ?', [count($userIds)]);
                }, '=', count($userIds))->first();

            if ($existingStandaloneChat) {
                return redirect()->route('chat.show', $existingStandaloneChat);
            }
        }

        if (count($userIds) > 2) {
            $existingGroupChat = Chat::where('type', 'group')
                ->whereHas('users', function ($query) use ($userIds) {
                    $query->whereIn('user_id', $userIds)
                        ->havingRaw('COUNT(DISTINCT user_id) = ?', [count($userIds)]);
                }, '=', count($userIds))
                ->first();

            if ($existingGroupChat) {
                return redirect()->route('chat.show', $existingGroupChat);
            }
        }

        $chatType = count($validated['users']) > 1 ? 'group' : 'standalone';

        $chat = Chat::create([
            'type' => $chatType,
        ]);

        // Attach users to the chat
        $chat->users()->attach($userIds);

        foreach ($userIds as $userId) {
            broadcast(new NewChat($chat, User::find($userId)));
        }

        return redirect()->route('chat.show', $chat);
    }
}
