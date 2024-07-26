<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShowNewChatController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $query = $request->input('query', '');
        $user = auth()->user();
        $users = User::where('id', '!=', $user->id)->get(['id', 'name']);

        $chats = $query
            ? Chat::search($query)
                ->get()
            : Chat::whereHas('users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->with('users')->get();

        return Inertia::render('Chat/New', [
            'chats' => $chats,
            'users' => UserResource::collection($users)
        ]);
    }
}
