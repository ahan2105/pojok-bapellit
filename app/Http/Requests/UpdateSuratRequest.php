<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSuratRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'jenis_surat' => 'nullable|string|max:100',
            'sifat_surat' => 'nullable|string|max:100',
            'tanggal' => 'nullable|date',
            'no_indek' => 'nullable|string|max:255',
            'alamat_tujuan' => 'nullable|string|max:255',
            'isi_surat' => 'required|string',
            'keterangan' => 'nullable|string',
            'file_surat' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'isi_surat.required' => 'Isi surat wajib diisi',
            'file_surat.mimes' => 'File harus berupa PDF, DOC, DOCX, XLS, atau XLSX',
            'file_surat.max' => 'Ukuran file maksimal 5MB',
        ];
    }
}
