<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cluster_analysis_logs', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('cluster_alert_id');
            $table->uuid('user_id')->nullable();   // UUID
            
            $table->json('retrieved_chunk_ids');
            $table->longText('prompt_text');
            $table->longText('llm_response');
            
            $table->string('llm_model', 100)->nullable();
            $table->unsignedInteger('tokens_used')->nullable();
            $table->decimal('cost_usd', 8, 6)->nullable();
            $table->unsignedInteger('latency_ms')->nullable();
            
            $table->boolean('used_fallback')->default(false);
            
            $table->timestamps();
            
            $table->index(['cluster_alert_id', 'created_at']);
            $table->index('user_id');
            
            $table->foreign('cluster_alert_id')
                ->references('id')->on('cluster_alerts')
                ->onDelete('cascade');
            
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cluster_analysis_logs');
    }
};