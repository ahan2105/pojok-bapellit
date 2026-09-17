<?php

namespace App\Events;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BookingCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Booking $booking)
    {
        // Simpan notif ke database SEMUA ADMIN
        // biar lonceng admin nambah + tersimpan (gak hilang pas refresh)
        try {
            $admins = User::where('role', 'admin')
                ->orWhere('is_admin', true)
                ->get();

            foreach ($admins as $admin) {
                $admin->sendNotification(
                    type: 'booking',
                    title: 'Booking Baru',
                    message: "{$booking->nama_penanggung_jawab} booking {$booking->aula?->nama}",
                    data: [
                        'booking_id' => $booking->id,
                        'aula'       => $booking->aula?->nama,
                        'tanggal'    => $booking->tanggal_booking,
                        'sesi'       => $booking->sesi_waktu,
                    ],
                    url: route('admin.kelolabooking.index'),
                );
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