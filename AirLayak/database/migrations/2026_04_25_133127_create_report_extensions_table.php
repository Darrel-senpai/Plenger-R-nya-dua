<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_extensions', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('report_id');
            $table->uuid('requested_by_user_id');   // UUID
            
            $table->timestamp('previous_eta_at');
            $table->timestamp('proposed_eta_at');
            $table->text('reason');
            
            $table->enum('status', [
                'pending', 'approved', 'rejected', 'expired',
            ])->default('pending');
            
            $table->string('respond_token', 64)->unique();
            
            $table->timestamp('responded_at')->nullable();
            $table->text('user_response_notes')->nullable();
            $table->timestamp('expires_at');
            
            $table->timestamps();
            
            $table->index(['report_id', 'status']);
            $table->index('expires_at');
            $table->index('requested_by_user_id');
            
            $table->foreign('report_id')
                ->references('id')->on('reports')
                ->onDelete('cascade');
            
            $table->foreign('requested_by_user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_extensions');
    }
};