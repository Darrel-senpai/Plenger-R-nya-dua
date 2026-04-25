<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClusterAnalysisLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'cluster_alert_id',
        'user_id',
        'retrieved_chunk_ids',
        'prompt_text',
        'llm_response',
        'llm_model',
        'tokens_used',
        'cost_usd',
        'latency_ms',
        'used_fallback',
    ];

    protected $casts = [
        'retrieved_chunk_ids' => 'array',
        'used_fallback' => 'boolean',
        'cost_usd' => 'decimal:6',
    ];

    public function clusterAlert(): BelongsTo
    {
        return $this->belongsTo(ClusterAlert::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}