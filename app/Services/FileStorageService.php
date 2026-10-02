<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class FileStorageService
{
    /**
     * Store uploaded document file with UUID naming
     *
     * @param  string  $folder  Default folder path (e.g., 'uploads/surat')
     * @return array ['path' => string, 'original_name' => string, 'filename' => string]
     */
    public static function storeDocument(UploadedFile $file, string $folder = 'uploads'): array
    {
        $originalName = $file->getClientOriginalName();
        $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs($folder, $filename, 'public');

        return [
            'path' => $path,
            'original_name' => $originalName,
            'filename' => $filename,
        ];
    }

    /**
     * Get MIME type with fallback detection
     */
    public static function getMimeType(string $filePath): string
    {
        $mimeType = @mime_content_type($filePath);

        if (! $mimeType || $mimeType === 'application/octet-stream') {
            $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            $mimeType = self::getMimeTypeFromExtension($ext);
        }

        return $mimeType;
    }

    /**
     * Get MIME type from file extension
     */
    private static function getMimeTypeFromExtension(string $extension): string
    {
        return match ($extension) {
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            default => 'application/octet-stream',
        };
    }
}
