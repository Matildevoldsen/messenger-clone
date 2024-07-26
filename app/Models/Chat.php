<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use HasFactory;
    use SoftDeletes;

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

    public function usersWithTimestamps(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'chat_user')
            ->withPivot('last_read_at');
    }

    public function unreadCount(): int
    {
        $user = auth()->user();

        // Get the last_read_at timestamp for the current user
        $lastReadAt = $this->usersWithTimestamps()
            ->where('user_id', $user->id)
            ->first()
            ->pivot
            ->last_read_at;

        // Count unread messages based on the last_read_at timestamp
        return $this->messages()
            ->where('created_at', '>', Carbon::parse($lastReadAt))
            ->where('user_id', '!=', $user->id)
            ->count();
    }

    public function markAsRead(): void
    {
        $this->users()->updateExistingPivot(auth()->id(), [
            'last_read_at' => now(),
        ]);
    }
}
