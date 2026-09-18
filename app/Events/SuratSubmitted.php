<?php

namespace App\Events;

use App\Models\Surat;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SuratSubmitted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Surat $surat)
    {
        // Simpan notif ke database SEMUA ADMIN
        try {
            $admins = User::where('role', 'admin')
                ->orWhere('is_admin', true)
                ->get();

            foreach ($admins as $admin) {
                // ⭐ FIX: pakai positional parameter (bukan named)
                $admin->sendNotification(
                    'surat',
                    'Pengajuan Surat Baru',
                    "{$surat->no_surat} dari {$surat->user?->name}",
                    [
                        'surat_id'   => $surat->id,
                        'no_surat'   => $surat->no_surat,
                        'pengaju'    => $surat->user?->name,
                        'jenis'      => $surat->jenis_surat,
                        'created_at' => $surat->created_at,
                    ],
                    route('admin.kelolasurat.index')
                );
            }
        } catch (\Exception $e) {
            Log::warning('Gagal simpan notif surat: ' . $e->getMessage());
        }
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('admin.notifications')];
    }

    public function broadcastAs(): string
    {
        return 'surat.submitted';
    }

    public function broadcastWith(): array
    {
        return [
            'id'            => $this->surat->id,
            'no_surat'      => $this->surat->no_surat,
            'tanggal'       => $this->surat->tanggal?->toDateString(),
            'no_indek'      => $this->surat->no_indek,
            'jenis_surat'   => $this->surat->jenis_surat,
            'sifat_surat'   => $this->surat->sifat_surat,
            'asal_surat'    => $this->surat->asal_surat,
            'alamat_tujuan' => $this->surat->alamat_tujuan,
            'isi_surat'     => Str::limit($this->surat->isi_surat, 100),
            'keterangan'    => $this->surat->keterangan,
            'lampiran'      => $this->surat->banyak_lampiran,
            'file_url'      => $this->surat->file_url,
            'is_pdf'        => $this->surat->isFilePdf(),
            'is_image'      => $this->surat->isFileImage(),
            'pengaju'       => $this->surat->user?->name,
            'created_at'    => $this->surat->created_at?->diffForHumans(),
        ];
    }
}