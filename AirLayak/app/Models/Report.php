<?php

namespace App\Models;

use App\Traits\HasSpatialAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Report extends Model
{
    use HasFactory, HasSpatialAttributes;

    protected $fillable = [
        'area_id',
        'user_id',
        'category',
        'water_sources',
        'description',
        'photo_path',
        'status',
        'priority',
        'priority_score',
        'initial_priority_score',
        'target_role',
        'acknowledged_at',
        'acknowledged_by_user_id',
        'work_started_at',
        'eta_at',
        'eta_reason',
        'completion_claimed_at',
        'completion_notes',
        'resolved_at',
        'dismissed_at',
        'dismissal_reason',
        'handled_by_user_id',
        'handler_organization',
        'warning_count',
        'reporter_session_id',
        'reporter_confirm_token',
        'ip_address',
        'user_agent',
        // 'location' dihapus, di-set via setPoint()
    ];

    protected $casts = [
        'water_sources' => 'array',
        'priority_score' => 'decimal:2',
        'initial_priority_score' => 'decimal:2',
        'acknowledged_at' => 'datetime',
        'work_started_at' => 'datetime',
        'eta_at' => 'datetime',
        'completion_claimed_at' => 'datetime',
        'resolved_at' => 'datetime',
        'dismissed_at' => 'datetime',
        'warning_count' => 'integer',
    ];

    public const CATEGORIES = [
        'bau',
        'warna',
        'sakit_perut',
        'rasa_aneh',
        'lainnya',
    ];

    public const WATER_SOURCES = [
        'sumur',
        'pdam',
        'galon',
        'air_isi_ulang',
        'tidak_yakin',
    ];

    public const STATUSES = [
        'pending',
        'acknowledged',
        'in_progress',
        'extension_requested',
        'awaiting_confirmation',
        'resolved',
        'dismissed',
        'reopened',
    ];

    public const PRIORITIES = ['low', 'normal', 'high', 'critical'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Report $report) {
            if (empty($report->reporter_confirm_token)) {
                $report->reporter_confirm_token = Str::random(48);
            }
        });
    }

    // === RELATIONS ===

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    // Tambahkan relasi ini di bagian === RELATIONS ===
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by_user_id');
    }

    public function acknowledgedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'acknowledged_by_user_id');
    }

    public function clusterAlerts(): BelongsToMany
    {
        return $this->belongsToMany(ClusterAlert::class, 'cluster_alert_reports')
            ->withTimestamps();
    }

    public function warnings(): HasMany
    {
        return $this->hasMany(ReportWarning::class);
    }

    public function extensions(): HasMany
    {
        return $this->hasMany(ReportExtension::class);
    }

    public function activeExtension()
    {
        return $this->hasOne(ReportExtension::class)->where('status', 'pending');
    }

    // === ACCESSORS ===

    public function getLocationAttribute(): ?array
    {
        return $this->getPoint('location');
    }

    // === HELPERS ===

    public function isOverdueAcknowledgment(): bool
    {
        if ($this->status !== 'pending')
            return false;
        return $this->created_at->diffInHours(now()) >= 12;
    }

    public function isOverdueResolution(): bool
    {
        if (!in_array($this->status, ['in_progress', 'extension_requested']))
            return false;
        if (!$this->eta_at)
            return false;
        return now()->isAfter($this->eta_at);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Penanganan',
            'acknowledged' => 'Sudah Dilihat Operator',
            'in_progress' => 'Sedang Ditangani',
            'extension_requested' => 'Menunggu Persetujuan Perpanjangan',
            'awaiting_confirmation' => 'Menunggu Konfirmasi Pelapor',
            'resolved' => 'Selesai',
            'dismissed' => 'Tidak Ditindaklanjuti',
            'reopened' => 'Dibuka Kembali oleh Pelapor',
            default => $this->status,
        };
    }



    // === SCOPES ===

    public function scopeOverdueAcknowledgment($query)
    {
        return $query->where('status', 'pending')
            ->where('created_at', '<=', now()->subHours(12));
    }

    public function scopeOverdueResolution($query)
    {
        return $query->whereIn('status', ['in_progress', 'extension_requested'])
            ->whereNotNull('eta_at')
            ->where('eta_at', '<', now());
    }

    public function scopeForRole($query, string $role)
    {
        return $query->where(function ($q) use ($role) {
            $q->where('target_role', $role)->orWhere('target_role', 'both');
        });
    }

    public function scopeActive($query)
    {
        // Pastikan status yang ada di seeder masuk dalam kategori active
        return $query->whereIn('status', ['pending', 'acknowledged', 'in_progress', 'extension_requested']);
    }
}