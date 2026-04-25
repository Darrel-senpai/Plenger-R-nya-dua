<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['pdam', 'dinkes', 'admin'])->default('pdam')->after('password');
            $table->string('city', 100)->nullable()->after('role');
            $table->string('organization', 100)->nullable()->after('city');
            $table->boolean('is_active')->default(true)->after('organization');
            
            $table->index('role');
            $table->index(['city', 'role']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['city', 'role']);
            $table->dropColumn(['role', 'city', 'organization', 'is_active']);
        });
    }
};