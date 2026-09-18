<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingApproved
{
    use Dispatchable, SerializesModels;

    public function __construct(public Booking $booking)
    {
        // Kirim notif ke USER pemilik booking
        try {
            $booking->user?->sendNotification(
                'booking',
                'Booking Disetujui!',
                "Booking aula {$booking->aula?->nama} tanggal {$booking->tanggal_booking} telah disetujui.",
                [
                    'booking_id' => $booking->id,
                    'status'     => 'approved',
                    'tanggal'    => $booking->tanggal_booking,
                    'sesi'       => $booking->sesi_waktu,
                    'aula'       => $booking->aula?->nama,
                ],
                route('riwayat.show', $booking->id)
            );
        } catch (\Exception $e) {
            \Log::error('Gagal kirim notif approved: ' . $e->getMessage());
        }
    }
}