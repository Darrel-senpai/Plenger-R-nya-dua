<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['province_id', 'name', 'code', 'type'];

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function districts(): HasMany
    {
        return $this->hasMany(District::class);
    }

    public function areas(): HasManyThrough
    {
        return $this->hasManyThrough(Area::class, District::class);
    }

    public function industrialFacilities(): HasMany
    {
        return $this->hasMany(IndustrialFacility::class);
    }

    public function operators(): HasMany
    {
        return $this->hasMany(User::class);
    }
}