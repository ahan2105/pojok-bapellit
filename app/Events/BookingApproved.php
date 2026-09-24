<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BookingApproved
{
    use Dispatchable, SerializesModels;

    public function __construct(public Booking $booking)
    {
        // ⭐ OPTIMASI: Pastikan relasi sudah dimuat untuk menghindari query tambahan
        $this->booking->loadMissing(['user', 'aula']);

        // Kirim notif ke USER pemilik booking
        try {
            if ($this->booking->user) {
                $this->booking->user->sendNotification(
                    'booking',
                    'Booking Disetujui!',
                    "Booking aula {$this->booking->aula?->nama} tanggal {$this->booking->tanggal_booking} telah disetujui.",
                    [
                        'booking_id' => $this->booking->id,
                        'status'     => 'approved',
                        'tanggal'    => $this->booking->tanggal_booking,
                        'sesi'       => $this->booking->sesi_waktu,
                        'aula'       => $this->booking->aula?->nama,
                    ],
                    route('riwayat.show', $this->booking->id)
                );
            }
        } catch (\Exception $e) {
            Log::error('Gagal kirim notif approved: ' . $e->getMessage());
        }
    }
}