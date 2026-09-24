<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Events\BookingApproved;
use App\Events\BookingRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AdminBookingController extends Controller
{
    /**
     * Menampilkan semua booking untuk admin
     */
    public function index()
    {
        // ✅ KODE ASLI: Aman dari error kolom
        $bookings = Booking::with(['user', 'aula'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        // ⭐ OPTIMASI AMAN: Cache statistik selama 5 menit
        // Hanya ini yang diubah dari kode asli Anda
        $cacheKey = 'booking_statistics_' . today()->format('Y-m-d');
        $statistics = Cache::remember($cacheKey, 300, function () {
            return [
                'total' => Booking::count(),
                'pending' => Booking::where('status', 'pending')->count(),
                'approved' => Booking::where('status', 'approved')->count(),
                'rejected' => Booking::where('status', 'rejected')->count(),
                'completed' => Booking::where('status', 'completed')->count(),
                'canceled' => Booking::where('status', 'canceled')->count(),
                'today' => Booking::whereDate('tanggal_booking', today())->count(),
            ];
        });
        
        return view('admin.kelolabooking.index', compact('bookings', 'statistics'));
    }

    /**
     * Detail booking — redirect ke index karena detail pakai modal popup.
     */
    public function detail(int $id)
    {
        return redirect()->route('admin.kelolabooking.index');
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

            broadcast(new BookingApproved($booking));

            //  Clear cache saat ada perubahan status
            Cache::forget('booking_statistics_' . today()->format('Y-m-d'));

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
                // ⚠️ PERHATIAN: Pastikan kolom 'catatan' ada di tabel bookings
                // Jika error lagi, hapus baris ini atau tambahkan kolomnya via migration
                $booking->catatan = $request->alasan; 
            }
            
            $booking->save();

            broadcast(new BookingRejected($booking, $request->alasan));

            // ⭐ Clear cache saat ada perubahan status
            Cache::forget('booking_statistics_' . today()->format('Y-m-d'));

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

            // ⭐ Clear cache saat ada perubahan status
            Cache::forget('booking_statistics_' . today()->format('Y-m-d'));

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
                // ⚠️ PERHATIAN: Sama seperti di atas, pastikan kolom 'catatan' ada
                $booking->catatan = $request->catatan;
            }
            
            $booking->save();

            //  Clear cache saat ada perubahan status
            Cache::forget('booking_statistics_' . today()->format('Y-m-d'));

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

            // ⭐ Clear cache saat ada data dihapus
            Cache::forget('booking_statistics_' . today()->format('Y-m-d'));

            return redirect()->route('admin.kelolabooking.index')
                ->with('success', 'Booking berhasil dihapus!');

        } catch (\Exception $e) {
            Log::error('Destroy booking error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menghapus booking: ' . $e->getMessage());
        }
    }

    /**
     * Admin: Hapus banyak booking sekaligus (Bulk Delete)
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:bookings,id'
        ]);

        $ids = $request->input('ids');
        
        // Hapus booking yang dipilih
        Booking::whereIn('id', $ids)->delete();

        // Clear cache statistik
        Cache::forget('booking_statistics_' . today()->format('Y-m-d'));

        return response()->json([
            'success' => true,
            'message' => count($ids) . ' booking berhasil dihapus.'
        ]);
    }
}