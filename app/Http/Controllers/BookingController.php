<?php

namespace App\Http\Controllers;

use App\Models\Aula;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    /**
     * Halaman utama booking (USER & ADMIN)
     * Menampilkan daftar aula yang tersedia untuk booking
     */
    public function index()
    {
        // Tampilkan daftar aula untuk semua role (user & admin)
        $aulas = Aula::where('status_aktif', true)->get();
        
        return view('booking.index', compact('aulas'));
    }

    /**
     * Form booking detail aula
     */
    public function show(int $id)
    {
        $aula = Aula::findOrFail($id);
        $user = Auth::user();
        
        $isAdmin = $user && ($user->role === 'admin' || $user->is_admin === true);
        
        if (!$aula->status_aktif && !$isAdmin) {
            return redirect()->route('booking.index')->with('error', 'Aula tidak tersedia.');
        }

        return view('booking.show', compact('aula'));
    }

    /**
     * Simpan booking
     */
    public function store(Request $request)
    {
        $rules = [
            'aula_id' => 'required|exists:aulas,id',
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'nama_penanggung_jawab' => 'required|string|max:255',
            'keperluan' => 'required|string|max:500',
            'jumlah_peserta' => 'required|integer|min:1',
            'sesi_waktu' => 'required|in:pagi,siang,seharian',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Cek ketersediaan
        if (!$this->isAvailable($request)) {
            return back()->with('error', 'Aula sudah dibooking pada tanggal dan sesi tersebut.')->withInput();
        }

        try {
            Booking::create([
                'user_id' => Auth::id(),
                'aula_id' => $request->aula_id,
                'tanggal_booking' => $request->tanggal_booking,
                'nama_penanggung_jawab' => $request->nama_penanggung_jawab,
                'keperluan' => $request->keperluan,
                'jumlah_peserta' => $request->jumlah_peserta,
                'sesi_waktu' => $request->sesi_waktu,
                'catatan' => $request->catatan,
                'status' => 'pending',
            ]);

            return redirect()->route('riwayat.index')
                ->with('success', 'Booking berhasil! Menunggu persetujuan admin.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal booking: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Cek ketersediaan (AJAX)
     */
    public function checkAvailability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'aula_id' => 'required|exists:aulas,id',
            'tanggal_booking' => 'required|date',
            'sesi_waktu' => 'required|in:pagi,siang,seharian'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $available = $this->isAvailable($request);

        return response()->json([
            'success' => true,
            'available' => $available,
            'message' => $available ? 'Aula tersedia' : 'Aula sudah dibooking'
        ]);
    }

    /**
     * Detail booking (User & Admin)
     */
    public function detail(int $id)
    {
        $user = Auth::user();
        $isAdmin = $user && ($user->role === 'admin' || $user->is_admin === true);
        
        $query = Booking::with(['user', 'aula']);
        
        // Jika bukan admin, hanya bisa lihat booking sendiri
        if (!$isAdmin) {
            $query->where('user_id', Auth::id());
        }

        $booking = $query->findOrFail($id);
        return view('booking.detail', compact('booking'));
    }

    /**
     * User: Cancel booking sendiri / Admin: Cancel semua
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

    // ============ PRIVATE HELPER METHODS ============

    /**
     * Cek ketersediaan booking (helper)
     */
    private function isAvailable(Request $request): bool
    {
        $query = Booking::where('aula_id', $request->aula_id)
            ->where('tanggal_booking', $request->tanggal_booking)
            ->whereIn('status', ['pending', 'approved']);

        if ($request->sesi_waktu === 'seharian') {
            return !$query->exists();
        }

        return !$query->where(function($q) use ($request) {
            $q->where('sesi_waktu', $request->sesi_waktu)
              ->orWhere('sesi_waktu', 'seharian');
        })->exists();
    }
}