<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('area_id');
            $table->geometry('location', subtype: 'point', srid: 4326);
            
            $table->enum('category', [
                'bau', 'warna', 'sakit_perut', 'rasa_aneh', 'lainnya',
            ]);
            
            $table->json('water_sources');
            $table->text('description')->nullable();
            $table->string('photo_path', 255)->nullable();
            
            $table->enum('status', [
                'pending', 'acknowledged', 'in_progress', 'extension_requested',
                'awaiting_confirmation', 'resolved', 'dismissed', 'reopened',
            ])->default('pending');
            
            $table->enum('priority', ['low', 'normal', 'high', 'critical'])->default('normal');
            $table->decimal('priority_score', 5, 2)->default(50.00);
            $table->decimal('initial_priority_score', 5, 2)->default(50.00);
            
            $table->enum('target_role', ['pdam', 'dinkes', 'both'])->default('pdam');
            
            $table->timestamp('acknowledged_at')->nullable();
            // UUID untuk FK ke users
            $table->uuid('acknowledged_by_user_id')->nullable();
            
            $table->timestamp('work_started_at')->nullable();
            $table->timestamp('eta_at')->nullable();
            $table->text('eta_reason')->nullable();
            
            $table->timestamp('completion_claimed_at')->nullable();
            $table->text('completion_notes')->nullable();
            
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('dismissed_at')->nullable();
            $table->text('dismissal_reason')->nullable();
            
            // UUID untuk FK ke users
            $table->uuid('handled_by_user_id')->nullable();
            $table->string('handler_organization', 100)->nullable();
            
            $table->unsignedTinyInteger('warning_count')->default(0);
            
            $table->string('reporter_session_id', 64)->nullable();
            $table->string('reporter_confirm_token', 64)->nullable()->unique();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            
            $table->timestamps();
            
            $table->index(['status', 'priority']);
            $table->index(['status', 'created_at']);
            $table->index(['target_role', 'status']);
            $table->index(['priority', 'created_at']);
            $table->index(['category', 'created_at']);
            $table->index(['area_id', 'created_at']);
            $table->index('reporter_session_id');
            $table->index('eta_at');
            $table->index('acknowledged_by_user_id');
            $table->index('handled_by_user_id');
            
            // FK ke areas (BIGINT)
            $table->foreign('area_id')
                ->references('id')->on('areas')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            
            // FK ke users (UUID)
            $table->foreign('acknowledged_by_user_id')
                ->references('id')->on('users')
                ->onDelete('set null');
            
            $table->foreign('handled_by_user_id')
                ->references('id')->on('users')
                ->onDelete('set null');
        });
        
        DB::statement('ALTER TABLE reports ADD SPATIAL INDEX reports_location_spatial (location)');
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};