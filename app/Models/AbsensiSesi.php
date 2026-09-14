<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiSesi extends Model
{
    use HasFactory;

    protected $table = 'absensi_sesi';

    protected $fillable = [
        'nama_sesi',
        'tanggal',
        'lokasi',
        'catatan',
        'is_default',
        'is_locked',
        'created_by',
    ];

    protected $casts = [
        'tanggal'    => 'date',
        'is_default' => 'boolean',
        'is_locked'  => 'boolean',
    ];

    // Relasi: 1 sesi punya banyak detail absensi
    public function details()
    {
        return $this->hasMany(AbsensiDetail::class, 'absensi_sesi_id');
    }

    // Siapa yang buat sesi ini
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Accessor: jumlah hadir
    public function getJumlahHadirAttribute()
    {
        return $this->details()->where('status_kehadiran', 'hadir')->count();
    }

    // Accessor: jumlah tidak hadir
    public function getJumlahTidakAttribute()
    {
        return $this->details()->where('status_kehadiran', 'tidak')->count();
    }
}