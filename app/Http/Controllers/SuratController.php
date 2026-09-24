<?php

namespace App\Http\Controllers;

use App\Models\PengaturanSurat;
use App\Models\Surat;
use App\Events\SuratSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SuratController extends Controller
{
    /**
     * Halaman utama: Form ambil surat + daftar surat dengan filter
     */
    public function index(Request $request)
    {
        // Default filter: hanya surat milik user login
        $filter = $request->input('filter', 'mine'); 
        
        // Eager load relasi user untuk menampilkan nama/bidang/foto di riwayat
        $query = Surat::with('user:id,name,bidang,profile_photo');

        if ($filter === 'all') {
            // Tampilkan semua surat (agar nomor surat terlihat jelas urutannya)
            // Urutkan berdasarkan nomor surat descending (terbaru di atas)
            $query->orderBy('no_surat', 'desc'); 
        } else {
            // Hanya surat milik user yang sedang login
            $query->where('user_id', Auth::id())
                  ->orderBy('created_at', 'desc');
        }

        // Fitur pencarian global
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('no_surat', 'like', "%{$search}%")
                  ->orWhere('isi_surat', 'like', "%{$search}%")
                  ->orWhere('jenis_surat', 'like', "%{$search}%")
                  // Cari juga berdasarkan nama user jika filter 'all'
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        $surats = $query->paginate(15);

        $pengaturan = PengaturanSurat::getSetting();
        // Generate nomor dummy untuk tampilan form (tidak increment sampai submit)
        $nomorTersedia = $pengaturan->generateNomor(1);

        return view('surat.index', compact('surats', 'nomorTersedia', 'filter'));
    }

    /**
     * Simpan pengajuan baru + assign nomor + increment counter
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jenis_surat'    => 'nullable|string|max:100',
            'sifat_surat'    => 'nullable|string|max:100',
            'tanggal'        => 'nullable|date',
            'no_indek'       => 'nullable|string|max:255',
            'alamat_tujuan'  => 'nullable|string|max:255',
            'isi_surat'      => 'required|string',
            'keterangan'     => 'nullable|string',
            'file_surat'     => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:5120',
        ], [
            'isi_surat.required'  => 'Isi surat wajib diisi',
            'file_surat.required' => 'Lampiran wajib diupload',
            'file_surat.mimes'    => 'File harus berupa PDF, DOC, DOCX, XLS, atau XLSX',
            'file_surat.max'      => 'Ukuran file maksimal 5MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $pengaturan = PengaturanSurat::getSetting();
            $nomorSurat = $pengaturan->generateNomor(1);

            $file = $request->file('file_surat');
            $originalName = $file->getClientOriginalName();
            
            // Gunakan UUID agar nama file unik dan aman
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads/surat', $filename, 'public');

            $surat = Surat::create([
                'user_id'                   => Auth::id(),
                'no_surat'                  => $nomorSurat,
                'jenis_surat'               => $request->input('jenis_surat'),
                'sifat_surat'               => $request->input('sifat_surat'),
                'tanggal'                   => $request->input('tanggal', now()),
                'no_indek'                  => $request->input('no_indek'),
                'alamat_tujuan'             => $request->input('alamat_tujuan'),
                'isi_surat'                 => $request->input('isi_surat'),
                'keterangan'                => $request->input('keterangan'),
                'file_surat'                => $path,
                'file_surat_original_name'  => $originalName,
            ]);

            // Increment counter HANYA setelah sukses create
            $pengaturan->incrementCounter(1);

            broadcast(new SuratSubmitted($surat));

            return redirect()->route('surat.index')
                ->with('success', 'Surat berhasil diambil! Nomor surat Anda: ' . $nomorSurat);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update data surat (Edit)
     */
    public function update(Request $request, int $id)
    {
        $surat = Surat::where('user_id', Auth::id())->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'jenis_surat'    => 'nullable|string|max:100',
            'sifat_surat'    => 'nullable|string|max:100',
            'tanggal'        => 'nullable|date',
            'no_indek'       => 'nullable|string|max:255',
            'alamat_tujuan'  => 'nullable|string|max:255',
            'isi_surat'      => 'required|string',
            'keterangan'     => 'nullable|string',
            // File opsional saat edit, tapi jika ada harus sesuai format
            'file_surat'     => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:5120',
        ], [
            'isi_surat.required' => 'Isi surat wajib diisi',
            'file_surat.mimes'   => 'File harus berupa PDF, DOC, DOCX, XLS, atau XLSX',
            'file_surat.max'     => 'Ukuran file maksimal 5MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $data = $request->only([
                'jenis_surat', 'sifat_surat', 'tanggal', 'no_indek', 
                'alamat_tujuan', 'isi_surat', 'keterangan'
            ]);

            // Handle Upload File Baru (jika ada)
            if ($request->hasFile('file_surat')) {
                // Hapus file lama dari storage
                if ($surat->file_surat && Storage::disk('public')->exists($surat->file_surat)) {
                    Storage::disk('public')->delete($surat->file_surat);
                }

                $file = $request->file('file_surat');
                $originalName = $file->getClientOriginalName();
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('uploads/surat', $filename, 'public');

                $data['file_surat'] = $path;
                $data['file_surat_original_name'] = $originalName;
            }

            $surat->update($data);

            return redirect()->route('surat.index')
                ->with('success', 'Data surat berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengupdate surat: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Download / Preview file surat
     */
    public function download(int $id)
    {
        // Izinkan download jika user adalah pemilik ATAU admin (opsional, sesuaikan kebijakan)
        // Untuk keamanan dasar, kita cek kepemilikan dulu
        $surat = Surat::where('user_id', Auth::id())->findOrFail($id);

        if (!$surat->file_surat || !Storage::disk('public')->exists($surat->file_surat)) {
            return redirect()->back()->with('error', 'File tidak tersedia.');
        }

        $path = Storage::disk('public')->path($surat->file_surat);

        if (!is_readable($path)) {
            return redirect()->back()->with('error', 'File tidak dapat diakses.');
        }

        $filename = $surat->file_surat_original_name;

        if (empty($filename)) {
            $ext = strtolower(pathinfo($surat->file_surat, PATHINFO_EXTENSION));
            $filename = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $surat->no_surat ?? 'surat') . '.' . $ext;
        } else {
            $filename = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $filename);
        }

        $mimeType = @mime_content_type($path);

        if (!$mimeType || $mimeType === 'application/octet-stream') {
            $ext = strtolower(pathinfo($surat->file_surat, PATHINFO_EXTENSION));
            $mimeType = $this->getMimeType($ext);
        }

        $disposition = ($mimeType === 'application/pdf') ? 'inline' : 'attachment';

        return response()->streamDownload(function () use ($path) {
            readfile($path);
        }, $filename, [
            'Content-Type'        => $mimeType,
            'Content-Disposition' => $disposition . '; filename="' . $filename . '"',
            'Cache-Control'       => 'no-cache, no-store, must-revalidate',
            'Pragma'              => 'no-cache',
            'Expires'             => '0',
        ]);
    }

    /**
     * MIME type fallback
     */
    private function getMimeType(string $extension): string
    {
        return match ($extension) {
            'pdf'  => 'application/pdf',
            'doc'  => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls'  => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            default => 'application/octet-stream',
        };
    }

    /**
     * Preview nomor berikutnya (AJAX)
     */
    public function previewNomor()
    {
        $pengaturan = PengaturanSurat::getSetting();
        $nomor = $pengaturan->generateNomor(1);

        return response()->json([
            'success' => true,
            'nomor' => $nomor,
        ]);
    }
}