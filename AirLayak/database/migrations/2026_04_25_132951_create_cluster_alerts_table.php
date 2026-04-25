<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cluster_alerts', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('area_id');
            
            $table->geometry('centroid', subtype: 'point', srid: 4326);
            $table->unsignedInteger('radius_meters')->default(500);
            
            $table->enum('dominant_category', [
                'bau', 'warna', 'sakit_perut', 'rasa_aneh', 'lainnya',
            ]);
            
            $table->json('water_sources_distribution');
            
            $table->enum('source_pattern', [
                'pdam_dominant',
                'well_dominant',
                'refill_dominant',
                'galon_dominant',
                'mixed_well_pdam',
                'uncertain',
                'diverse',
            ]);
            
            $table->decimal('severity_score', 5, 2);
            $table->unsignedInteger('report_count');
            
            $table->enum('status', [
                'active', 'investigating', 'resolved', 'expired',
            ])->default('active');
            
            $table->json('ai_analysis')->nullable();
            $table->timestamp('ai_analyzed_at')->nullable();
            
            // UUID untuk FK ke users
            $table->uuid('assigned_to_user_id')->nullable();
            
            $table->timestamp('triggered_at');
            $table->timestamp('resolved_at')->nullable();
            $table->text('resolution_notes')->nullable();
            
            $table->timestamps();
            
            $table->index(['status', 'severity_score']);
            $table->index(['area_id', 'status']);
            $table->index('triggered_at');
            $table->index('assigned_to_user_id');
            
            // FK
            $table->foreign('area_id')
                ->references('id')->on('areas')
                ->onDelete('cascade');
            
            $table->foreign('assigned_to_user_id')
                ->references('id')->on('users')
                ->onDelete('set null');
        });
        
        DB::statement('ALTER TABLE cluster_alerts ADD SPATIAL INDEX clusters_centroid_spatial (centroid)');
    }

    public function down(): void
    {
        Schema::dropIfExists('cluster_alerts');
    }
};