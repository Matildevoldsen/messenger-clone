<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'users' => UserResource::collection($this->usersInChat()),
            'messages' => $this->messages->count(),
            'last_message' => MessageResource::make($this->messages->last()),
            'unread_count' => 0
        ];
    }
}
