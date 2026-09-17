<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasNotifications;

/**
 * @method \Illuminate\Database\Eloquent\Relations\HasMany customNotifications()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany customUnreadNotifications()
 * @method \App\Models\Notification sendNotification(string $type, string $title, string $message, array $data = [], ?string $url = null)
 * @method static void sendNotificationToAdmins(string $type, string $title, string $message, array $data = [], ?string $url = null)
 * 
 * @method bool isAdmin()
 * @method bool isPegawai()
 * @method bool canLogin()
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
    use HasFactory, Notifiable, HasNotifications;

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

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
    ];

    // ============================================================
    // HELPER METHODS
    // ============================================================

    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->is_admin === true;
    }

    public function isPegawai(): bool
    {
        return $this->status === 'aktif';
    }

    public function canLogin(): bool
    {
        return !empty($this->username)
            && !empty($this->password)
            && $this->status === 'aktif';
    }

    // ============================================================
    // RELASI
    // ============================================================

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function absensiDetails()
    {
        return $this->hasMany(AbsensiDetail::class, 'user_id');
    }

    public function absensiSesiDibuat()
    {
        return $this->hasMany(AbsensiSesi::class, 'created_by');
    }
}