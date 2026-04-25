<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cluster_alert_reports', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('cluster_alert_id');
            $table->unsignedBigInteger('report_id');
            
            // Standard Laravel timestamps untuk compatibility dengan withTimestamps()
            $table->timestamps();
            
            $table->unique(['cluster_alert_id', 'report_id']);
            $table->index('report_id');
            
            $table->foreign('cluster_alert_id')
                ->references('id')->on('cluster_alerts')
                ->onDelete('cascade');
            
            $table->foreign('report_id')
                ->references('id')->on('reports')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cluster_alert_reports');
    }
};