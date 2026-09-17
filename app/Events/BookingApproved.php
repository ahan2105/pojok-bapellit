<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BookingApproved implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Booking $booking)
    {
        // Kirim notif ke USER pemilik booking
        try {
            $booking->user?->sendNotification(
                type: 'booking',
                title: 'Booking Disetujui!',
                message: "Booking aula {$booking->aula?->nama} tanggal {$booking->tanggal_booking} telah disetujui.",
                data: [
                    'booking_id' => $booking->id,
                    'status'     => 'approved',
                ],
                url: route('riwayat.index', $booking->id),
            );
        } catch (\Exception $e) {
            Log::warning('Gagal kirim notif approved: ' . $e->getMessage());
        }
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('user.' . $this->booking->user_id)];
    }

    public function broadcastAs(): string
    {
        return 'booking.approved';
    }

    public function broadcastWith(): array
    {
        return [
            'id'      => $this->booking->id,
            'aula'    => $this->booking->aula?->nama,
            'tanggal' => $this->booking->tanggal_booking,
            'sesi'    => $this->booking->sesi_waktu,
        ];
    }
}