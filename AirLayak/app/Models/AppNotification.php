<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppNotification extends Model
{
    use HasFactory;

    protected $table = 'app_notifications';

    protected $fillable = [
        'type',
        'target_user_id',
        'target_role',
        'target_city',
        'title',
        'message',
        'related_type',
        'related_id',
        'action_url',
        'metadata',
        'severity',
        'read_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'read_at' => 'datetime',
    ];

    public function targetUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    public function isUnread(): bool
    {
        return $this->read_at === null;
    }

    public function markAsRead(): void
    {
        if ($this->read_at === null) {
            $this->update(['read_at' => now()]);
        }
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeForUser($query, User $user)
    {
        return $query->where(function ($q) use ($user) {
            $q->where('target_user_id', $user->id)
              ->orWhere(function ($qq) use ($user) {
                  $qq->where('target_role', $user->role)
                     ->where(function ($qqq) use ($user) {
                         $qqq->whereNull('target_city')
                             ->orWhere('target_city', $user->city);
                     });
              })
              ->orWhere('target_role', 'all');
        });
    }
}