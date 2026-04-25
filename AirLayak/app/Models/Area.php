<?php

namespace App\Models;

use App\Traits\HasSpatialAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    use HasFactory, HasSpatialAttributes;

    protected $fillable = [
        'kelurahan',
        'kecamatan',
        'city',
        'city_type',
        'province',
        'bps_code',
        'population_density',
        'dominant_water_sources',
        // 'centroid' dan 'polygon' dihapus dari fillable, di-set via setPoint()
    ];

    protected $casts = [
        'dominant_water_sources' => 'array',
        'population_density' => 'integer',
    ];

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function clusterAlerts(): HasMany
    {
        return $this->hasMany(ClusterAlert::class);
    }

    public function industrialFacilities()
    {
        return IndustrialFacility::query()
            ->where('city', $this->city)
            ->where('kecamatan', $this->kecamatan);
    }

    public function getCentroidAttribute(): ?array
    {
        return $this->getPoint('centroid');
    }

    public function getFullAddressAttribute(): string
    {
        return implode(', ', array_filter([
            $this->kelurahan,
            $this->kecamatan,
            $this->city,
            $this->province,
        ]));
    }

    public function getShortLabelAttribute(): string
    {
        return "{$this->kelurahan}, {$this->city}";
    }

    public function getDisplayNameAttribute(): string
    {
        return "Kel. {$this->kelurahan} (Kec. {$this->kecamatan})";
    }

    public function scopeInProvince($query, string $province)
    {
        return $query->where('province', $province);
    }

    public function scopeInCity($query, string $city)
    {
        return $query->where('city', $city);
    }

    public function scopeInKecamatan($query, string $kecamatan)
    {
        return $query->where('kecamatan', $kecamatan);
    }
}