<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KnowledgeBaseChunk extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_title',
        'document_source',
        'chunk_text',
        'embedding',
        'metadata',
        'chunk_index',
        'token_count',
    ];

    protected $casts = [
        'embedding' => 'array',
        'metadata' => 'array',
    ];
}