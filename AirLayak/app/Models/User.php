<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    // === KEY TYPE: UUID ===
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'auth_type',
        'phone',
        'region_id',
        'default_lat',
        'default_lng',
        'profile_completed',
        'role',
        'city',
        'organization',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'profile_completed' => 'boolean',
        'is_active' => 'boolean',
        'default_lat' => 'float',
        'default_lng' => 'float',
    ];

    

    // Auto-generate UUID untuk new records
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    // === RELATIONS ===
    
    public function reportsHandled(): HasMany
    {
        return $this->hasMany(Report::class, 'handled_by_user_id');
    }

    public function reportsAcknowledged(): HasMany
    {
        return $this->hasMany(Report::class, 'acknowledged_by_user_id');
    }

    public function warningsReceived(): HasMany
    {
        return $this->hasMany(ReportWarning::class, 'warned_user_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(AppNotification::class, 'target_user_id');
    }

    // === HELPERS ===

     public const INSTANSI_ROLES = ['admin', 'pdam', 'dinkes'];

    public function isInstansi(): bool
    {
        return in_array($this->role, self::INSTANSI_ROLES, true);
    }

    public function reports()
    {
        // Beri tahu Laravel untuk mencocokkan 'id' dari tabel users 
        // dengan kolom 'reporter_session_id' di tabel reports
        return $this->hasMany(Report::class, 'reporter_session_id', 'id');
    }

    public function isWarga(): bool
    {
        // Warga biasa: tidak punya role instansi
        return !$this->isInstansi();
    }

    public function getDashboardRoute(): string
    {
        return $this->isInstansi() ? 'admin.dashboard' : 'home';
    }
    
    public function isPdam(): bool
    {
        return $this->role === 'pdam';
    }

    public function isDinkes(): bool
    {
        return $this->role === 'dinkes';
    }

    // Tambahkan relasi ini di bagian === RELATIONS ===
    public function myReports(): HasMany
    {
        return $this->hasMany(Report::class, 'user_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function unreadNotificationsCount(): int
    {
        return AppNotification::forUser($this)->unread()->count();
    }

}