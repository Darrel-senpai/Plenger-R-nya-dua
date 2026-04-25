<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportWarning extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'warning_type',
        'warned_role',
        'warned_organization',
        'warned_user_id',
        'details',
        'priority_impact',
    ];

    protected $casts = [
        'priority_impact' => 'decimal:2',
    ];

    public const TYPES = [
        'overdue_acknowledgment',
        'overdue_resolution',
        'extension_rejected',
        'extension_no_response',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function warnedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'warned_user_id');
    }

    public function typeLabel(): string
    {
        return match ($this->warning_type) {
            'overdue_acknowledgment' => 'Terlambat Acknowledge',
            'overdue_resolution' => 'Terlambat dari ETA',
            'extension_rejected' => 'Perpanjangan Ditolak',
            'extension_no_response' => 'Perpanjangan Tidak Direspon',
            default => $this->warning_type,
        };
    }
}