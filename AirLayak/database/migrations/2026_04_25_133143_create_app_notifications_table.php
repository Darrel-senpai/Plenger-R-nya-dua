<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_notifications', function (Blueprint $table) {
            $table->id();
            
            $table->enum('type', [
                'new_report',
                'report_overdue_ack',
                'report_overdue_resolution',
                'extension_rejected',
                'extension_no_response',
                'cluster_detected',
                'report_resolved_by_citizen',
                'report_reopened_by_citizen',
            ]);
            
            $table->uuid('target_user_id')->nullable();   // UUID
            
            $table->enum('target_role', ['pdam', 'dinkes', 'admin', 'all'])->nullable();
            $table->string('target_city', 100)->nullable();
            
            $table->string('title', 200);
            $table->text('message');
            
            $table->string('related_type', 50)->nullable();
            $table->unsignedBigInteger('related_id')->nullable();
            
            $table->string('action_url', 500)->nullable();
            $table->json('metadata')->nullable();
            
            $table->enum('severity', ['info', 'warning', 'urgent'])->default('info');
            
            $table->timestamp('read_at')->nullable();
            
            $table->timestamps();
            
            $table->index(['target_user_id', 'read_at']);
            $table->index(['target_role', 'target_city', 'read_at']);
            $table->index(['related_type', 'related_id']);
            $table->index('created_at');
            
            $table->foreign('target_user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_notifications');
    }
};