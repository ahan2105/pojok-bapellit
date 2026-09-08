<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    /**
     * Menampilkan semua booking untuk admin
     */
    public function index()
    {
        $bookings = Booking::with(['user', 'aula'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        $statistics = [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'approved' => Booking::where('status', 'approved')->count(),
            'rejected' => Booking::where('status', 'rejected')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'canceled' => Booking::where('status', 'canceled')->count(),
            'today' => Booking::whereDate('tanggal_booking', today())->count(),
        ];
        
        return view('admin.kelolabooking.index', compact('bookings', 'statistics'));
    }

    /**
     * Menampilkan detail booking untuk admin
     */
    public function detail(int $id)
    {
        $booking = Booking::with(['user', 'aula'])->findOrFail($id);
        return view('admin.kelolabooking.detail', compact('booking'));
    }

    /**
     * Admin: Menyetujui booking
     */
    public function approve(int $id)
    {
        try {
            $booking = Booking::findOrFail($id);
            
            if ($booking->status !== 'pending') {
                return redirect()->back()
                    ->with('error', 'Hanya booking dengan status "Menunggu" yang bisa disetujui.');
            }
            
            $booking->status = 'approved';
            $booking->save();

            return redirect()->back()
                ->with('success', 'Booking berhasil disetujui!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menyetujui booking: ' . $e->getMessage());
        }
    }

    /**
     * Admin: Menolak booking
     */
    public function reject(Request $request, int $id)
    {
        try {
            $booking = Booking::findOrFail($id);
            
            if ($booking->status !== 'pending') {
                return redirect()->back()
                    ->with('error', 'Hanya booking dengan status "Menunggu" yang bisa ditolak.');
            }
            
            $booking->status = 'rejected';
            
            if ($request->filled('alasan')) {
                $booking->catatan = $request->alasan;
            }
            
            $booking->save();

            return redirect()->back()
                ->with('success', 'Booking berhasil ditolak!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menolak booking: ' . $e->getMessage());
        }
    }

    /**
     * Admin: Menyelesaikan booking
     */
    public function complete(int $id)
    {
        try {
            $booking = Booking::findOrFail($id);
            
            if ($booking->status !== 'approved') {
                return redirect()->back()
                    ->with('error', 'Hanya booking yang sudah disetujui yang bisa diselesaikan.');
            }
            
            $booking->status = 'completed';
            $booking->save();

            return redirect()->back()
                ->with('success', 'Booking berhasil diselesaikan!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menyelesaikan booking: ' . $e->getMessage());
        }
    }

    /**
     * Admin: Menghapus booking
     */
    public function destroy(int $id)
    {
        try {
            $booking = Booking::findOrFail($id);
            $booking->delete();

            return redirect()->route('admin.kelolabooking.index')
                ->with('success', 'Booking berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus booking: ' . $e->getMessage());
        }
    }
}