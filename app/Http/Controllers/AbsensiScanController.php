<?php

namespace App\Http\Controllers;

use App\Models\AbsensiDetail;
use App\Models\AbsensiSesi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiScanController extends Controller
{
    /**
     * Halaman scan QR (untuk user)
     */
public function showScan()
{
    return view('presensi.scan');   
}

    /**
     * Handle scan via URL token (dari QR di-scan browser HP)
     * Otomatis HADIR & simpan ke DB
     */
    public function scanViaToken(Request $request, string $token)
    {
        $user = Auth::user();
        $sesi = AbsensiSesi::where('token_qr', $token)->first();

        // QR tidak valid
        if (!$sesi) {
            return view('absensi.scan-result', [
                'status'  => 'error',
                'title'   => 'QR Code Tidak Valid',
                'message' => 'QR Code tidak dikenali. Silakan minta admin menampilkan QR baru.',
            ]);
        }

        // QR kadaluarsa
        if (!$sesi->isQrValid()) {
            return view('absensi.scan-result', [
                'status'  => 'error',
                'title'   => 'QR Code Kadaluarsa',
                'message' => 'QR Code ini sudah tidak berlaku (kemungkinan sudah di-refresh). Silakan scan QR terbaru di layar admin.',
            ]);
        }

        // Sesi sudah dikunci
        if ($sesi->is_locked) {
            return view('absensi.scan-result', [
                'status'  => 'error',
                'title'   => 'Sesi Sudah Dikunci',
                'message' => 'Absensi untuk sesi ini sudah ditutup oleh admin.',
            ]);
        }

        // Cek apakah user terdaftar di sesi ini
        $detail = AbsensiDetail::where('absensi_sesi_id', $sesi->id)
            ->where('user_id', $user->id)
            ->first();

        // Kalau belum terdaftar → buat baru
        if (!$detail) {
            AbsensiDetail::create([
                'absensi_sesi_id'  => $sesi->id,
                'user_id'          => $user->id,
                'status_kehadiran' => 'hadir',
                'waktu_absen'      => now(),
            ]);

            return view('absensi.scan-result', [
                'status'  => 'success',
                'title'   => 'Absensi Berhasil!',
                'message' => 'Kehadiran Anda sudah tercatat. Terima kasih.',
                'sesi'    => $sesi,
                'waktu'   => now(),
            ]);
        }

        // Sudah pernah scan
        if ($detail->status_kehadiran === 'hadir') {
            return view('absensi.scan-result', [
                'status'  => 'info',
                'title'   => 'Sudah Absen',
                'message' => 'Anda sudah tercatat hadir di sesi ini pada '
                             . ($detail->waktu_absen?->translatedFormat('d F Y, H:i') ?? '-') . ' WIB.',
                'sesi'    => $sesi,
                'waktu'   => $detail->waktu_absen,
            ]);
        }

        // ⭐ Update ke HADIR — simpan otomatis
        $detail->update([
            'status_kehadiran' => 'hadir',
            'waktu_absen'      => now(),
            'keterangan'       => null,
        ]);

        return view('absensi.scan-result', [
            'status'  => 'success',
            'title'   => 'Absensi Berhasil!',
            'message' => 'Kehadiran Anda sudah tercatat. Terima kasih.',
            'sesi'    => $sesi,
            'waktu'   => now(),
        ]);
    }

    /**
     * Proses scan via AJAX (dari kamera HTML5 QR)
     */
    public function processScan(Request $request)
    {
        $request->validate(['token' => 'required|string']);

        $user = Auth::user();
        $sesi = AbsensiSesi::where('token_qr', $request->token)->first();

        if (!$sesi) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak valid.',
            ], 404);
        }

        if (!$sesi->isQrValid()) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code sudah kadaluarsa. Silakan scan QR terbaru di layar admin.',
            ], 410);
        }

        if ($sesi->is_locked) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi absensi sudah dikunci.',
            ], 423);
        }

        $detail = AbsensiDetail::where('absensi_sesi_id', $sesi->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$detail) {
            AbsensiDetail::create([
                'absensi_sesi_id'  => $sesi->id,
                'user_id'          => $user->id,
                'status_kehadiran' => 'hadir',
                'waktu_absen'      => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil! Kehadiran Anda tercatat.',
                'sesi'    => $sesi->nama_sesi,
                'waktu'   => now()->format('H:i:s'),
            ]);
        }

        if ($detail->status_kehadiran === 'hadir') {
            return response()->json([
                'success' => true,
                'already' => true,
                'message' => 'Anda sudah tercatat hadir sebelumnya.',
                'sesi'    => $sesi->nama_sesi,
                'waktu'   => $detail->waktu_absen?->format('H:i:s'),
            ]);
        }

        $detail->update([
            'status_kehadiran' => 'hadir',
            'waktu_absen'      => now(),
            'keterangan'       => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil! Kehadiran Anda tercatat.',
            'sesi'    => $sesi->nama_sesi,
            'waktu'   => now()->format('H:i:s'),
        ]);
    }
}