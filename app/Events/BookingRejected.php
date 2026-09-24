<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BookingRejected implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Booking $booking, public ?string $alasan = null)
    {
        // ⭐ OPTIMASI: Pastikan relasi sudah dimuat untuk menghindari query tambahan
        $this->booking->loadMissing(['user', 'aula']);

        try {
            if ($this->booking->user) {
                $this->booking->user->sendNotification(
                    type: 'booking',
                    title: 'Booking Ditolak',
                    message: "Booking aula {$this->booking->aula?->nama} tanggal {$this->booking->tanggal_booking} ditolak." . ($this->alasan ? " Alasan: {$this->alasan}" : ''),
                    data: [
                        'booking_id' => $this->booking->id,
                        'status'     => 'rejected',
                        'alasan'     => $this->alasan,
                    ],
                    url: route('riwayat.index', $this->booking->id),
                );
            }
        } catch (\Exception $e) {
            Log::warning('Gagal kirim notif rejected: ' . $e->getMessage());
        }
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('user.' . $this->booking->user_id)];
    }

    public function broadcastAs(): string
    {
        return 'booking.rejected';
    }

    public function broadcastWith(): array
    {
        return [
            'id'      => $this->booking->id,
            'aula'    => $this->booking->aula?->nama,
            'tanggal' => $this->booking->tanggal_booking,
            'alasan'  => $this->alasan,
        ];
    }
}