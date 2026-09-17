<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    /**
     * Menampilkan riwayat booking
     * - User: lihat riwayat sendiri
     * - Admin: lihat semua riwayat
     * Detail booking tampil via POP-UP di halaman ini
     */
    public function index()
    {
        $user = Auth::user();
        $isAdmin = $user && ($user->role === 'admin' || $user->is_admin === true);

        $query = Booking::with(['user', 'aula']);

        // Jika bukan admin, hanya tampilkan booking sendiri
        if (!$isAdmin) {
            $query->where('user_id', Auth::id());
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('riwayat.index', compact('bookings', 'isAdmin'));
    }

    /**
     * Redirect ke index — detail booking tampil via pop-up di index
     */
    public function show(int $id)
    {
        return redirect()->route('riwayat.index');
    }

    /**
     * Membatalkan booking (hanya jika status pending)
     * - User: hanya bisa batalkan milik sendiri
     * - Admin: bisa batalkan semua
     */
    public function cancel(int $id)
    {
        try {
            $user = Auth::user();
            $isAdmin = $user && ($user->role === 'admin' || $user->is_admin === true);

            $query = Booking::where('status', 'pending');

            // Jika bukan admin, hanya bisa batalkan booking sendiri
            if (!$isAdmin) {
                $query->where('user_id', Auth::id());
            }

            $booking = $query->findOrFail($id);
            $booking->status = 'canceled';
            $booking->save();

            return redirect()->back()
                ->with('success', 'Booking berhasil dibatalkan!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal membatalkan booking: ' . $e->getMessage());
        }
    }
}