<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Events\BookingApproved;
use App\Events\BookingRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

            // ⬇️ TAMBAH: broadcast ke user
            broadcast(new BookingApproved($booking));

            return redirect()->back()
                ->with('success', 'Booking berhasil disetujui!');

        } catch (\Exception $e) {
            Log::error('Approve booking error: ' . $e->getMessage());
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

            // ⬇️ TAMBAH: broadcast ke user
            broadcast(new BookingRejected($booking, $request->alasan));

            return redirect()->back()
                ->with('success', 'Booking berhasil ditolak!');

        } catch (\Exception $e) {
            Log::error('Reject booking error: ' . $e->getMessage());
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
            Log::error('Complete booking error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menyelesaikan booking: ' . $e->getMessage());
        }
    }

    /**
     * Admin: Update status booking (manual)
     */
    public function update(Request $request, int $id)
    {
        try {
            $booking = Booking::findOrFail($id);
            
            if ($request->filled('status')) {
                $booking->status = $request->status;
            }
            
            if ($request->filled('catatan')) {
                $booking->catatan = $request->catatan;
            }
            
            $booking->save();

            return redirect()->back()
                ->with('success', 'Status booking berhasil diubah!');

        } catch (\Exception $e) {
            Log::error('Update booking error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal mengubah status: ' . $e->getMessage());
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
            Log::error('Destroy booking error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menghapus booking: ' . $e->getMessage());
        }
    }
}