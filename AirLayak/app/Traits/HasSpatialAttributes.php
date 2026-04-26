<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait HasSpatialAttributes
{
    /**
     * Create a record with spatial point columns.
     */
    public static function createWithPoint(array $attributes, array $spatialColumns): self
    {
        $instance = new static();
        $table = $instance->getTable();
        $primaryKey = $instance->getKeyName();
        
        // Generate UUID jika model pakai UUID
        if ($instance->getKeyType() === 'string' && empty($attributes[$primaryKey])) {
            $attributes[$primaryKey] = (string) Str::uuid();
        }
        
        // Tambahkan timestamps jika model pakai timestamps
        if ($instance->usesTimestamps()) {
            $now = now();
            $attributes[static::CREATED_AT] = $attributes[static::CREATED_AT] ?? $now;
            $attributes[static::UPDATED_AT] = $attributes[static::UPDATED_AT] ?? $now;
        }
        
        // Build column list dan values
        $columns = array_keys($attributes);
        $values = array_values($attributes);
        
        // Tambahkan spatial columns
        $spatialColumnNames = array_keys($spatialColumns);
        $allColumns = array_merge($columns, $spatialColumnNames);
        
        // Encode JSON dan datetime
        $bindings = array_map(function ($value) {
            if (is_array($value)) {
                return json_encode($value);
            }
            if ($value instanceof \DateTimeInterface) {
                return $value->format('Y-m-d H:i:s');
            }
            return $value;
        }, $values);
        
        // Build placeholders untuk regular columns
        $placeholders = array_fill(0, count($columns), '?');
        
        // Build placeholders untuk spatial columns
        // PENTING: SRID 4326 di MySQL pakai axis order (latitude, longitude)
        $spatialPlaceholders = [];
        foreach ($spatialColumns as $col => $point) {
            $lat = $point['lat'];
            $lng = $point['lng'];
            // Format: POINT(lat lng) untuk SRID 4326
            $spatialPlaceholders[] = "ST_GeomFromText('POINT({$lat} {$lng})', 4326)";
        }
        
        // Build kolom dan placeholder string
        $columnsStr = '`' . implode('`, `', $allColumns) . '`';
        $placeholdersStr = implode(', ', array_merge($placeholders, $spatialPlaceholders));
        
        DB::insert(
            "INSERT INTO `{$table}` ({$columnsStr}) VALUES ({$placeholdersStr})",
            $bindings
        );
        
        // Return model instance
        if ($instance->getKeyType() === 'string') {
            return static::find($attributes[$primaryKey]);
        } else {
            $id = DB::getPdo()->lastInsertId();
            return static::find($id);
        }
    }
    
    /**
     * Update spatial point column for existing record.
     * Note: MySQL SRID 4326 uses (lat, lng) axis order.
     */
    public function setPoint(string $column, float $lat, float $lng): void
    {
        $table = $this->getTable();
        $primaryKey = $this->getKeyName();
        
        DB::statement(
            "UPDATE `{$table}` SET `{$column}` = ST_GeomFromText(?, 4326) WHERE `{$primaryKey}` = ?",
            ["POINT({$lat} {$lng})", $this->getKey()]
        );
    }
    
    /**
     * Get a POINT column as ['lat' => x, 'lng' => y]
     * Note: For SRID 4326, ST_X returns latitude and ST_Y returns longitude.
     */
    public function getPoint(string $column): ?array
    {
        $table = $this->getTable();
        $primaryKey = $this->getKeyName();
        
        $result = DB::selectOne(
            "SELECT ST_X(`{$column}`) as lat, ST_Y(`{$column}`) as lng FROM `{$table}` WHERE `{$primaryKey}` = ?",
            [$this->getKey()]
        );
        
        if (!$result || $result->lat === null) {
            return null;
        }
        
        return [
            'lat' => (float) $result->lat,
            'lng' => (float) $result->lng,
        ];
    }
}