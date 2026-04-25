<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ReportExtension extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'requested_by_user_id',
        'previous_eta_at',
        'proposed_eta_at',
        'reason',
        'status',
        'respond_token',
        'responded_at',
        'user_response_notes',
        'expires_at',
    ];

    protected $casts = [
        'previous_eta_at' => 'datetime',
        'proposed_eta_at' => 'datetime',
        'responded_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public const STATUSES = ['pending', 'approved', 'rejected', 'expired'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (ReportExtension $ext) {
            if (empty($ext->respond_token)) {
                $ext->respond_token = Str::random(48);
            }
            if (empty($ext->expires_at)) {
                // Default: user punya 24 jam untuk respond
                $ext->expires_at = now()->addHours(24);
            }
        });
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by_user_id');
    }

    public function isExpired(): bool
    {
        return $this->status === 'pending' && now()->isAfter($this->expires_at);
    }
}