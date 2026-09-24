<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengaturanSurat;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminSuratController extends Controller
{
    /**
     * Daftar surat yang sudah diambil user
     */
    public function index(Request $request)
    {
        // ⭐ PERBAIKAN: Tambahkan profile_photo & bidang agar muncul di view
        $query = Surat::with('user:id,name,email,bidang,profile_photo');

        // Batasi kolom yang diambil untuk performa
        $query->select([
            'id', 'user_id', 'no_surat', 'no_indek', 'isi_surat', 
            'jenis_surat', 'asal_surat', 'alamat_tujuan', 'file_surat',
            'file_surat_original_name', 'banyak_lampiran', 'keterangan',
            'created_at', 'updated_at'
        ]);

        if ($request->filled('search')) {
            $search = $request->input('search');
            
            // Hindari LIKE %...% pada field TEXT yang besar
            $query->where(function ($q) use ($search) {
                $q->where('no_surat', 'like', "%{$search}%")
                  ->orWhere('no_indek', 'like', "%{$search}%")
                  ->orWhere('jenis_surat', 'like', "%{$search}%")
                  ->orWhere('asal_surat', 'like', "%{$search}%")
                  ->orWhere('alamat_tujuan', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Urutkan berdasarkan nomor surat agar terlihat jelas urutannya
        $surats = $query->orderBy('no_surat', 'desc')->paginate(30);

        $pengaturan = PengaturanSurat::getSetting();
        $nomorBerikutnya = $pengaturan->generateNomor(1);

        // Cache statistics selama 5 menit untuk mengurangi query COUNT
        $cacheKey = 'surat_statistics_' . today()->format('Y-m-d');
        $statistics = Cache::remember($cacheKey, 300, function () {
            return [
                'total' => Surat::count(),
                'bulan_ini' => Surat::whereMonth('created_at', date('m'))
                                     ->whereYear('created_at', date('Y'))
                                     ->count(),
                'hari_ini' => Surat::whereDate('created_at', today())->count(),
            ];
        });
        
        $statistics['nomor_berikutnya'] = $nomorBerikutnya;

        return view('admin.kelolasurat.index', compact('surats', 'statistics'));
    }

    /**
     * Form edit surat (admin bisa edit nomor dll)
     */
    public function edit(int $id)
    {
        $surat = Surat::with('user:id,name,email')->findOrFail($id);
        return view('admin.kelolasurat.edit', compact('surat'));
    }

    /**
     * Update surat (admin edit + ganti file)
     */
    public function update(Request $request, int $id)
    {
        $surat = Surat::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'no_surat' => 'required|string|max:255|unique:surats,no_surat,' . $id,
            'jenis_surat' => 'nullable|string|max:100',
            'sifat_surat' => 'nullable|string|max:100',
            'tanggal' => 'nullable|date',
            'asal_surat' => 'nullable|string|max:255',
            'alamat_tujuan' => 'nullable|string|max:255',
            'isi_surat' => 'required|string',
            'banyak_lampiran' => 'nullable|integer|min:0',
            'no_indek' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            
            // ⭐ VALIDASI FILE BARU (SAMA DENGAN USER)
            'file_surat' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:5120',
        ], [
            'file_surat.mimes' => 'File harus berupa PDF, DOC, DOCX, XLS, atau XLSX',
            'file_surat.max' => 'Ukuran file maksimal 5MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // 1. Update data teks dasar
            $surat->no_surat = $request->input('no_surat');
            $surat->jenis_surat = $request->input('jenis_surat');
            $surat->sifat_surat = $request->input('sifat_surat');
            $surat->tanggal = $request->input('tanggal');
            $surat->asal_surat = $request->input('asal_surat');
            $surat->alamat_tujuan = $request->input('alamat_tujuan');
            $surat->isi_surat = $request->input('isi_surat');
            $surat->banyak_lampiran = $request->input('banyak_lampiran') ?? 0;
            $surat->no_indek = $request->input('no_indek');
            $surat->keterangan = $request->input('keterangan');

            // 2. Handle Upload File Baru (jika ada)
            if ($request->hasFile('file_surat')) {
                // Hapus file lama dari storage jika ada
                if ($surat->file_surat && Storage::disk('public')->exists($surat->file_surat)) {
                    Storage::disk('public')->delete($surat->file_surat);
                }

                $file = $request->file('file_surat');
                $originalName = $file->getClientOriginalName();
                
                // Gunakan UUID agar nama file unik dan aman
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('uploads/surat', $filename, 'public');

                $surat->file_surat = $path;
                $surat->file_surat_original_name = $originalName;
            }

            $surat->save();

            // Clear cache statistics setelah update
            Cache::forget('surat_statistics_' . today()->format('Y-m-d'));

            return redirect()->route('admin.kelolasurat.index')
                ->with('success', 'Surat berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hapus surat
     */
    public function destroy(int $id)
    {
        try {
            $surat = Surat::findOrFail($id);

            if ($surat->file_surat && Storage::disk('public')->exists($surat->file_surat)) {
                Storage::disk('public')->delete($surat->file_surat);
            }

            $surat->delete();

            // Clear cache statistics setelah delete
            Cache::forget('surat_statistics_' . today()->format('Y-m-d'));

            return redirect()->route('admin.kelolasurat.index')
                ->with('success', 'Surat berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    /**
     * Hapus banyak surat sekaligus (Bulk Delete)
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:surats,id'
        ]);

        $ids = $request->input('ids');
        
        // Ambil data surat terlebih dahulu untuk menghapus file fisiknya
        $surats = Surat::whereIn('id', $ids)->get();

        foreach ($surats as $surat) {
            if ($surat->file_surat && Storage::disk('public')->exists($surat->file_surat)) {
                Storage::disk('public')->delete($surat->file_surat);
            }
        }

        // Hapus data dari database
        Surat::whereIn('id', $ids)->delete();

        // Clear cache statistics setelah bulk delete
        Cache::forget('surat_statistics_' . today()->format('Y-m-d'));

        return response()->json([
            'success' => true,
            'message' => count($ids) . ' surat berhasil dihapus.'
        ]);
    }

    /**
     * Download file dari user (nama file asli)
     */
    public function downloadFile(int $id)
    {
        $surat = Surat::findOrFail($id);

        if (!$surat->file_surat || !Storage::disk('public')->exists($surat->file_surat)) {
            return redirect()->back()->with('error', 'File tidak tersedia.');
        }

        $path = Storage::disk('public')->path($surat->file_surat);

        // Pakai nama file asli kalau ada
        $filename = $surat->file_surat_original_name
            ?? (str_replace('/', '-', $surat->no_surat) . '.' . pathinfo($surat->file_surat, PATHINFO_EXTENSION));

        return response()->download($path, $filename);
    }

    /**
     * Halaman pengaturan nomor surat
     */
    public function pengaturan()
    {
        $pengaturan = PengaturanSurat::getSetting();
        $previewNomor = $pengaturan->generateNomor(1);

        return view('admin.kelolasurat.pengaturan', compact('pengaturan', 'previewNomor'));
    }

    /**
     * Update pengaturan (hanya nomor terakhir)
     */
    public function updatePengaturan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomor_terakhir' => 'required|integer|min:0',
        ], [
            'nomor_terakhir.required' => 'Nomor terakhir wajib diisi',
            'nomor_terakhir.integer' => 'Nomor terakhir harus berupa angka',
            'nomor_terakhir.min' => 'Nomor terakhir minimal 0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $pengaturan = PengaturanSurat::getSetting();

            $pengaturan->update([
                'nomor_terakhir' => $request->input('nomor_terakhir'),
            ]);

            // Ambil ulang & generate preview
            $pengaturanBaru = PengaturanSurat::getSetting();
            $nomorBerikutnya = $pengaturanBaru->generateNomor(1);

            return redirect()->route('admin.kelolasurat.pengaturan')
                ->with('success', 'Pengaturan berhasil disimpan! Surat berikutnya: ' . $nomorBerikutnya);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
}