<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AulaController extends Controller
{
    // Menampilkan daftar aula di panel admin
    public function index()
    {
        $aulas = Aula::orderBy('created_at', 'desc')->get();
        return view('admin.aula.index', compact('aulas'));
    }

    // Menampilkan form tambah aula
    public function create()
    {
        return view('admin.aula.create');
    }

    // Menyimpan data aula baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255|unique:aulas,nama',
            'kapasitas' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
            'foto.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'fasilitas' => 'nullable|array',
            'fasilitas.*' => 'string|max:100',
            'status_aktif' => 'boolean'
        ], [
            'nama.required' => 'Nama aula wajib diisi',
            'nama.unique' => 'Nama aula sudah digunakan, silakan gunakan nama lain',
            'kapasitas.required' => 'Kapasitas wajib diisi',
            'kapasitas.min' => 'Kapasitas minimal 1 orang',
            'foto.*.image' => 'File yang diupload harus berupa gambar',
            'foto.*.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WEBP',
            'foto.*.max' => 'Ukuran gambar maksimal 5MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $aula = new Aula();
            $aula->nama = $request->nama;
            $aula->kapasitas = $request->kapasitas;
            $aula->deskripsi = $request->deskripsi;
            $aula->status_aktif = $request->has('status_aktif');

            // Handle Upload Foto (Maksimal 3 foto)
            if ($request->hasFile('foto')) {
                $files = $request->file('foto');
                if (count($files) > 3) {
                    return redirect()->back()
                        ->withErrors(['foto' => 'Maksimal 3 foto yang dapat diupload'])
                        ->withInput();
                }

                $fotoPaths = [];
                foreach ($files as $file) {
                    $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('uploads/aulas', $filename, 'public');
                    $fotoPaths[] = $path;
                }
                $aula->foto = $fotoPaths;
            }

            // Handle Fasilitas
            if ($request->has('fasilitas') && is_array($request->fasilitas)) {
                $fasilitas = array_filter($request->fasilitas, function($item) {
                    return !empty(trim($item));
                });
                $aula->fasilitas = array_values($fasilitas);
            } else {
                $aula->fasilitas = [];
            }

            $aula->save();

            return redirect()->route('admin.aula.index')
                ->with('success', 'Data aula "' . $aula->nama . '" berhasil ditambahkan!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Menampilkan form edit aula
    public function edit(int $id)
    {
        $aula = Aula::findOrFail($id);
        return view('admin.aula.edit', compact('aula'));
    }

    // Mengupdate data aula yang sudah ada
    public function update(Request $request, int $id)
    {
        $aula = Aula::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255|unique:aulas,nama,' . $id,
            'kapasitas' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
            'foto.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'fasilitas' => 'nullable|array',
            'fasilitas.*' => 'string|max:100',
            'status_aktif' => 'boolean'
        ], [
            'nama.required' => 'Nama aula wajib diisi',
            'nama.unique' => 'Nama aula sudah digunakan, silakan gunakan nama lain',
            'kapasitas.required' => 'Kapasitas wajib diisi',
            'kapasitas.min' => 'Kapasitas minimal 1 orang',
            'foto.*.image' => 'File yang diupload harus berupa gambar',
            'foto.*.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WEBP',
            'foto.*.max' => 'Ukuran gambar maksimal 5MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $aula->nama = $request->nama;
            $aula->kapasitas = $request->kapasitas;
            $aula->deskripsi = $request->deskripsi;
            $aula->status_aktif = $request->has('status_aktif');

            // Jika ada upload foto baru
            if ($request->hasFile('foto')) {
                $files = $request->file('foto');
                if (count($files) > 3) {
                    return redirect()->back()
                        ->withErrors(['foto' => 'Maksimal 3 foto yang dapat diupload'])
                        ->withInput();
                }

                // Hapus foto lama
                if ($aula->foto) {
                    foreach ($aula->foto as $oldFoto) {
                        Storage::disk('public')->delete($oldFoto);
                    }
                }

                $fotoPaths = [];
                foreach ($files as $file) {
                    $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('uploads/aulas', $filename, 'public');
                    $fotoPaths[] = $path;
                }
                $aula->foto = $fotoPaths;
            }

            // Handle Fasilitas
            if ($request->has('fasilitas') && is_array($request->fasilitas)) {
                $fasilitas = array_filter($request->fasilitas, function($item) {
                    return !empty(trim($item));
                });
                $aula->fasilitas = array_values($fasilitas);
            } else {
                $aula->fasilitas = [];
            }

            $aula->save();

            return redirect()->route('admin.aula.index')
                ->with('success', 'Data aula "' . $aula->nama . '" berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Menghapus data aula
    public function destroy(int $id)
    {
        try {
            $aula = Aula::findOrFail($id);
            $namaAula = $aula->nama;
            
            if ($aula->foto) {
                foreach ($aula->foto as $foto) {
                    Storage::disk('public')->delete($foto);
                }
            }

            $aula->delete();

            return redirect()->route('admin.aula.index')
                ->with('success', 'Data aula "' . $namaAula . '" berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    // Menampilkan detail aula (untuk booking)
    public function show(int $id)
    {
        try {
            $aula = Aula::findOrFail($id);
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $aula,
                    'foto_urls' => $aula->foto_urls
                ]);
            }
            
            return view('admin.aula.show', compact('aula'));
            
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aula tidak ditemukan'
                ], 404);
            }
            abort(404);
        }
    }

    // Toggle status aktif
    public function toggleStatus(int $id)
    {
        try {
            $aula = Aula::findOrFail($id);
            $aula->status_aktif = !$aula->status_aktif;
            $aula->save();

            $status = $aula->status_aktif ? 'diaktifkan' : 'dinonaktifkan';
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status aula berhasil ' . $status,
                    'status' => $aula->status_aktif
                ]);
            }

            return redirect()->back()
                ->with('success', 'Status aula berhasil ' . $status . '!');

        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengubah status: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }

    // Menghapus semua foto aula
    public function deletePhotos(int $id)
    {
        try {
            $aula = Aula::findOrFail($id);
            
            if ($aula->foto) {
                foreach ($aula->foto as $foto) {
                    Storage::disk('public')->delete($foto);
                }
                $aula->foto = [];
                $aula->save();
                
                if (request()->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Semua foto berhasil dihapus'
                    ]);
                }
                
                return redirect()->back()
                    ->with('success', 'Semua foto berhasil dihapus!');
            }
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada foto untuk dihapus'
                ]);
            }
            
            return redirect()->back()
                ->with('info', 'Tidak ada foto untuk dihapus');
            
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus foto: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Gagal menghapus foto: ' . $e->getMessage());
        }
    }

    // Menghapus satu foto tertentu
    public function deletePhoto(Request $request, int $id)
    {
        try {
            $aula = Aula::findOrFail($id);
            $photoIndex = $request->input('index');
            
            if ($photoIndex !== null && isset($aula->foto[$photoIndex])) {
                Storage::disk('public')->delete($aula->foto[$photoIndex]);
                
                $fotos = $aula->foto;
                unset($fotos[$photoIndex]);
                $aula->foto = array_values($fotos);
                $aula->save();
                
                if (request()->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Foto berhasil dihapus'
                    ]);
                }
                
                return redirect()->back()
                    ->with('success', 'Foto berhasil dihapus!');
            }
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Foto tidak ditemukan'
                ], 404);
            }
            
            return redirect()->back()
                ->with('error', 'Foto tidak ditemukan');
            
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus foto: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Gagal menghapus foto: ' . $e->getMessage());
        }
    }

    // API untuk mendapatkan daftar aula (untuk booking)
    public function getAulasForBooking()
    {
        try {
            $aulas = Aula::where('status_aktif', true)
                ->select('id', 'nama', 'kapasitas', 'deskripsi', 'foto')
                ->get()
                ->map(function($aula) {
                    return [
                        'id' => $aula->id,
                        'nama' => $aula->nama,
                        'kapasitas' => $aula->kapasitas,
                        'deskripsi' => $aula->deskripsi,
                        'foto_urls' => $aula->foto_urls,
                        'fasilitas' => $aula->fasilitas
                    ];
                });
            
            return response()->json([
                'success' => true,
                'data' => $aulas
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }
}