<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HandlesFileUploads
{
    /**
     * Allowed MIME types mapped to their safe extensions.
     * SVG removed: can contain embedded JavaScript (XSS).
     */
    protected array $allowedMimeToExt = [
        'image/jpeg' => ['jpg', 'jpeg'],
        'image/png' => ['png'],
        'image/gif' => ['gif'],
        'image/webp' => ['webp'],
        'application/pdf' => ['pdf'],
        'application/msword' => ['doc'],
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => ['docx'],
        'application/vnd.ms-excel' => ['xls'],
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => ['xlsx'],
        'video/mp4' => ['mp4'],
        'video/webm' => ['webm'],
        'audio/mpeg' => ['mp3'],
        'audio/wav' => ['wav'],
    ];

    /**
     * Maximum file size in kilobytes (default: 10MB).
     */
    protected int $maxFileSizeKB = 10240;

    /**
     * Handle file upload with automatic deletion of old file.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function handleFileUpload(
        Request $request,
        string $fieldName,
        ?string $currentFile,
        string $storagePath,
        string $disk = 'public'
    ): ?string {
        if ($request->hasFile($fieldName)) {
            return $this->uploadFile($request->file($fieldName), $currentFile, $storagePath, $disk);
        }

        return $currentFile;
    }

    /**
     * Handle file upload with automatic deletion of old file directly from UploadedFile.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function uploadFile(
        \Illuminate\Http\UploadedFile $file,
        ?string $currentFile,
        string $storagePath,
        string $disk = 'public'
    ): ?string {
        $mimeType = $file->getMimeType();
        $clientExtension = strtolower($file->getClientOriginalExtension());

        // Security 1: Validate MIME type against whitelist
        if (! array_key_exists($mimeType, $this->allowedMimeToExt)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'file' => __('validation.mimes', ['attribute' => 'file', 'values' => 'jpg, png, pdf, doc, xls, mp4']),
            ]);
        }

        // Security 2: Validate extension matches the MIME type (prevents .php disguised as image)
        $allowedExtensions = $this->allowedMimeToExt[$mimeType];
        if (! in_array($clientExtension, $allowedExtensions)) {
            \Illuminate\Support\Facades\Log::warning('File upload blocked: MIME/extension mismatch', [
                'mime' => $mimeType,
                'extension' => $clientExtension,
                'original_name' => $file->getClientOriginalName(),
            ]);
            throw \Illuminate\Validation\ValidationException::withMessages([
                'file' => __('validation.mimes', ['attribute' => 'file', 'values' => implode(', ', $allowedExtensions)]),
            ]);
        }

        // Security 3: Block dangerous double extensions (e.g., shell.php.jpg)
        $originalName = $file->getClientOriginalName();
        if (preg_match('/\.(php|phtml|phar|sh|bash|exe|bat|cmd|cgi|pl|py|rb|jsp|asp|aspx|htaccess)/i', pathinfo($originalName, PATHINFO_FILENAME))) {
            \Illuminate\Support\Facades\Log::warning('File upload blocked: dangerous double extension', [
                'original_name' => $originalName,
            ]);
            throw \Illuminate\Validation\ValidationException::withMessages([
                'file' => 'File type not allowed.',
            ]);
        }

        // Security 4: Validate file size
        if ($file->getSize() / 1024 > $this->maxFileSizeKB) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'file' => __('validation.max.file', ['attribute' => 'file', 'max' => $this->maxFileSizeKB]),
            ]);
        }

        // Delete old file if exists
        $this->deleteFile($currentFile, $disk);

        // Security 5: Generate a safe random filename (prevents path traversal & filename injection)
        $safeFilename = Str::random(40).'.'.$allowedExtensions[0];
        $tenantPrefix = app()->bound('tenant') ? app('tenant')->id : 'global';
        $fullPath = "{$tenantPrefix}/{$storagePath}";

        return $file->storeAs($fullPath, $safeFilename, $disk);
    }

    /**
     * Delete a file from storage.
     */
    protected function deleteFile(?string $filePath, string $disk = 'public'): bool
    {
        if ($filePath && Storage::disk($disk)->exists($filePath)) {
            return Storage::disk($disk)->delete($filePath);
        }

        return false;
    }
}
