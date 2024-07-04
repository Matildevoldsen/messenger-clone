<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = ['type'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'chat_user');
    }

    public function usersInChat(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->users()->where('user_id', '!=', auth()->id())->get();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
