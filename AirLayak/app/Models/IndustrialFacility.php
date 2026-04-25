<?php

namespace App\Models;

use App\Traits\HasSpatialAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndustrialFacility extends Model
{
    use HasFactory, HasSpatialAttributes;

    protected $fillable = [
        'name',
        'industry_type',
        'kecamatan',
        'city',
        'province',
        'address',
        'source',
        // 'location' dihapus, di-set via setPoint()
    ];

    public const INDUSTRY_TYPES = [
        'textile', 'food', 'chemical', 'electronics',
        'metal', 'plastic', 'pharmaceutical', 'other',
    ];

    public function getLocationAttribute(): ?array
    {
        return $this->getPoint('location');
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