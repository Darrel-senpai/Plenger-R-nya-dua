<?php

namespace App\Models;

use App\Traits\HasSpatialAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClusterAlert extends Model
{
    use HasFactory, HasSpatialAttributes;

    protected $fillable = [
        'area_id',
        'radius_meters',
        'dominant_category',
        'water_sources_distribution',
        'source_pattern',
        'severity_score',
        'report_count',
        'status',
        'ai_analysis',
        'ai_analyzed_at',
        'assigned_to_user_id',
        'triggered_at',
        'resolved_at',
        'resolution_notes',
        // 'centroid' dihapus, di-set via setPoint()
    ];

    protected $casts = [
        'water_sources_distribution' => 'array',
        'ai_analysis' => 'array',
        'severity_score' => 'decimal:2',
        'ai_analyzed_at' => 'datetime',
        'triggered_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function reports(): BelongsToMany
    {
        return $this->belongsToMany(Report::class, 'cluster_alert_reports')
            ->withTimestamps();
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function analysisLogs(): HasMany
    {
        return $this->hasMany(ClusterAnalysisLog::class);
    }

    public function getCentroidAttribute(): ?array
    {
        return $this->getPoint('centroid');
    }
}