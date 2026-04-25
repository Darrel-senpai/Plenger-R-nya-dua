<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_warnings', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('report_id');
            
            $table->enum('warning_type', [
                'overdue_acknowledgment',
                'overdue_resolution',
                'extension_rejected',
                'extension_no_response',
            ]);
            
            $table->enum('warned_role', ['pdam', 'dinkes']);
            $table->string('warned_organization', 100)->nullable();
            
            $table->uuid('warned_user_id')->nullable();   // UUID
            
            $table->text('details')->nullable();
            $table->decimal('priority_impact', 5, 2)->default(15.00);
            
            $table->timestamps();
            
            $table->index(['report_id', 'warning_type']);
            $table->index(['warned_role', 'created_at']);
            $table->index('warned_user_id');
            
            $table->foreign('report_id')
                ->references('id')->on('reports')
                ->onDelete('cascade');
            
            $table->foreign('warned_user_id')
                ->references('id')->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_warnings');
    }
};