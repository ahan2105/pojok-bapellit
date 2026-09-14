<?php

namespace App\Http\Controllers;

use App\Models\PengaturanSurat;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SuratController extends Controller
{
    /**
     * Halaman utama: Form ambil surat + daftar surat user
     */
    public function index(Request $request)
    {
        $query = Surat::where('user_id', Auth::id());

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('no_surat', 'like', "%{$search}%")
                  ->orWhere('isi_surat', 'like', "%{$search}%")
                  ->orWhere('jenis_surat', 'like', "%{$search}%");
            });
        }

        $surats = $query->orderBy('created_at', 'desc')->paginate(15);

        $pengaturan = PengaturanSurat::getSetting();
        $nomorTersedia = $pengaturan->generateNomor(1);

        return view('surat.index', compact('surats', 'nomorTersedia'));
    }

    /**
     * Form ambil surat (opsional)
     */
    public function create()
    {
        $pengaturan = PengaturanSurat::getSetting();
        $nomorTersedia = $pengaturan->generateNomor(1);

        return view('surat.create', compact('nomorTersedia'));
    }

    /**
     * Simpan pengajuan + assign nomor + increment counter
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jenis_surat' => 'nullable|string|max:100',
            'sifat_surat' => 'nullable|string|max:100',
            'tanggal' => 'nullable|date',
            'no_indek' => 'nullable|string|max:255',
            'alamat_tujuan' => 'nullable|string|max:255',
            'isi_surat' => 'required|string',
            'keterangan' => 'nullable|string',
            'file_surat' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'isi_surat.required' => 'Isi surat wajib diisi',
            'file_surat.required' => 'Lampiran wajib diupload',
            'file_surat.mimes' => 'File harus berupa PDF, JPG, atau PNG',
            'file_surat.max' => 'Ukuran file maksimal 5MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $pengaturan = PengaturanSurat::getSetting();
            $nomorSurat = $pengaturan->generateNomor(1);

            $file = $request->file('file_surat');
            $originalName = $file->getClientOriginalName();
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads/surat', $filename, 'public');

            Surat::create([
                'user_id' => Auth::id(),
                'no_surat' => $nomorSurat,
                'jenis_surat' => $request->input('jenis_surat'),
                'sifat_surat' => $request->input('sifat_surat'),
                'tanggal' => $request->input('tanggal', now()),
                'no_indek' => $request->input('no_indek'),
                'alamat_tujuan' => $request->input('alamat_tujuan'),
                'isi_surat' => $request->input('isi_surat'),
                'keterangan' => $request->input('keterangan'),
                'file_surat' => $path,
                'file_surat_original_name' => $originalName,
            ]);

            $pengaturan->incrementCounter(1);

            return redirect()->route('surat.index')
                ->with('success', 'Surat berhasil diambil! Nomor surat Anda: ' . $nomorSurat);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Detail surat user
     */
    public function show(int $id)
    {
        $surat = Surat::where('user_id', Auth::id())->findOrFail($id);
        return view('surat.show', compact('surat'));
    }

    /**
     * Download file surat (nama asli)
     */
    public function download(int $id)
    {
        $surat = Surat::where('user_id', Auth::id())->findOrFail($id);

        if (!$surat->file_surat || !Storage::disk('public')->exists($surat->file_surat)) {
            return redirect()->back()->with('error', 'File tidak tersedia.');
        }

        $path = Storage::disk('public')->path($surat->file_surat);

        $filename = $surat->file_surat_original_name
            ?? (str_replace('/', '-', $surat->no_surat) . '.' . pathinfo($surat->file_surat, PATHINFO_EXTENSION));

        return response()->download($path, $filename);
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