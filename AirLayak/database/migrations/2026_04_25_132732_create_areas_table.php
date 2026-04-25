<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->string('kelurahan', 100);
            $table->string('kecamatan', 100);
            $table->string('city', 100);
            $table->enum('city_type', ['kota', 'kabupaten'])->default('kota');
            $table->string('province', 100);
            $table->string('bps_code', 20)->nullable();
            $table->geometry('centroid', subtype: 'point', srid: 4326);
            $table->geometry('polygon', subtype: 'polygon', srid: 4326)->nullable();
            $table->unsignedInteger('population_density')->nullable();
            $table->json('dominant_water_sources')->nullable();
            $table->timestamps();
            
            $table->index('kelurahan');
            $table->index('kecamatan');
            $table->index('city');
            $table->index('province');
            $table->index(['province', 'city', 'kecamatan']);
            $table->index(['city', 'kecamatan']);
            $table->unique(['province', 'city', 'kecamatan', 'kelurahan'], 'areas_full_path_unique');
        });
        
        DB::statement('ALTER TABLE areas ADD SPATIAL INDEX areas_centroid_spatial (centroid)');
    }

    public function down(): void
    {
        Schema::dropIfExists('areas');
    }
};