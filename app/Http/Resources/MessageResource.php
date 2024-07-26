<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'content' => $this->content,
            'is_typing' => $this->is_typing,
            'user' => UserResource::make($this->whenLoaded('user')),
            'attachments' => $this->attachments,
            'created_at' => DateResource::make($this->created_at)
        ];
    }
}
