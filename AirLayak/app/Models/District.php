<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    use HasFactory;

    protected $fillable = ['city_id', 'name', 'code'];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function province()
    {
        return $this->city->province();
    }

    public function areas(): HasMany
    {
        return $this->hasMany(Area::class);
    }
}