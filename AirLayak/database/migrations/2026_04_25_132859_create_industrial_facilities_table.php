<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('industrial_facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->geometry('location', subtype: 'point', srid: 4326);
            
            $table->enum('industry_type', [
                'textile', 'food', 'chemical', 'electronics',
                'metal', 'plastic', 'pharmaceutical', 'other',
            ]);
            
            $table->string('kecamatan', 100)->nullable();
            $table->string('city', 100);
            $table->string('province', 100);
            $table->text('address')->nullable();
            $table->enum('source', ['osm', 'manual', 'disperindag'])->default('manual');
            $table->timestamps();
            
            $table->index('city');
            $table->index('industry_type');
            $table->index(['city', 'kecamatan']);
        });
        
        DB::statement('ALTER TABLE industrial_facilities ADD SPATIAL INDEX facilities_location_spatial (location)');
    }

    public function down(): void
    {
        Schema::dropIfExists('industrial_facilities');
    }
};