<?php

namespace App\Events;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BookingCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Booking $booking)
    {
        // ⭐ OPTIMASI: Load relasi sekali di awal untuk menghindari N+1 di dalam loop
        $this->booking->loadMissing(['user', 'aula']);

        // Simpan notif ke database SEMUA ADMIN
        try {
            // ⭐ OPTIMASI: Cache daftar ID admin selama 1 jam agar tidak query user terus-menerus
            $adminIds = Cache::remember('admin_user_ids', 3600, function () {
                return User::where('role', 'admin')
                    ->orWhere('is_admin', true)
                    ->pluck('id')
                    ->toArray();
            });

            foreach ($adminIds as $adminId) {
                // Gunakan findOrFail atau first untuk mendapatkan instance user jika method sendNotification butuh object
                // Atau jika sendNotification bisa menerima ID, itu lebih cepat. 
                // Asumsi sendNotification adalah method di model User:
                $admin = User::find($adminId);
                if ($admin) {
                    $admin->sendNotification(
                        type: 'booking',
                        title: 'Booking Baru',
                        message: "{$this->booking->nama_penanggung_jawab} booking {$this->booking->aula?->nama}",
                        data: [
                            'booking_id' => $this->booking->id,
                            'aula'       => $this->booking->aula?->nama,
                            'tanggal'    => $this->booking->tanggal_booking,
                            'sesi'       => $this->booking->sesi_waktu,
                        ],
                        url: route('admin.kelolabooking.index'),
                    );
                }
            }
        } catch (\Exception $e) {
            // Kalau gagal simpan notif, jangan bikin booking gagal
            Log::warning('Gagal simpan notif booking: ' . $e->getMessage());
        }
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('admin.notifications')];
    }

    public function broadcastAs(): string
    {
        return 'booking.created';
    }

    public function broadcastWith(): array
    {
        return [
            'id'         => $this->booking->id,
            'nama'       => $this->booking->nama_penanggung_jawab,
            'keperluan'  => $this->booking->keperluan,
            'tanggal'    => $this->booking->tanggal_booking,
            'sesi'       => $this->booking->sesi_waktu,
            'peserta'    => $this->booking->jumlah_peserta,
            'status'     => $this->booking->status,
            'catatan'    => $this->booking->catatan,
            'pengaju'    => $this->booking->user?->name,
            'aula'       => $this->booking->aula?->nama,
            'lokasi'     => $this->booking->aula?->lokasi,
            'created_at' => $this->booking->created_at?->diffForHumans(),
        ];
    }
}