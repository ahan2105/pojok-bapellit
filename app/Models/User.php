<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @method bool isAdmin()
 * 
 * @property int $id
 * @property string $name
 * @property string|null $username
 * @property string|null $email
 * @property string|null $nip
 * @property string|null $whatsapp
 * @property string|null $password
 * @property string|null $role
 * @property string|null $bidang
 * @property string|null $jabatan
 * @property string|null $golongan
 * @property bool $is_admin
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'nip',
        'whatsapp',
        'password',
        'role',
        'bidang',
        'jabatan',
        'golongan',
        'is_admin',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
    ];

    /**
     * Cek apakah user adalah admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->is_admin === true;
    }

    /**
     * Relasi ke booking
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Relasi ke absensi detail (kehadiran user ini di semua sesi)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function absensiDetails()
    {
        return $this->hasMany(AbsensiDetail::class, 'user_id');
    }

    /**
     * Relasi ke sesi absensi yang dibuat oleh user ini (khusus admin)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function absensiSesiDibuat()
    {
        return $this->hasMany(AbsensiSesi::class, 'created_by');
    }
}