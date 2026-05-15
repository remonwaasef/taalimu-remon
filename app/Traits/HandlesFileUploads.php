<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait HandlesFileUploads
{
    /**
     * Allowed MIME types for file uploads.
     */
    protected array $allowedMimes = [
        'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml',
        'application/pdf',
        'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'video/mp4', 'video/webm',
        'audio/mpeg', 'audio/wav',
    ];

    /**
     * Maximum file size in kilobytes (default: 10MB).
     */
    protected int $maxFileSizeKB = 10240;

    /**
     * Handle file upload with automatic deletion of old file.
     *
     * @param Request $request
     * @param string $fieldName
     * @param string|null $currentFile
     * @param string $storagePath
     * @param string $disk
     * @return string|null
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
     * @param \Illuminate\Http\UploadedFile $file
     * @param string|null $currentFile
     * @param string $storagePath
     * @param string $disk
     * @return string|null
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function uploadFile(
        \Illuminate\Http\UploadedFile $file,
        ?string $currentFile,
        string $storagePath,
        string $disk = 'public'
    ): ?string {
        // Security: Validate MIME type to prevent malicious uploads
        if (!in_array($file->getMimeType(), $this->allowedMimes)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'file' => __('validation.mimes', ['attribute' => 'file', 'values' => 'jpg, png, pdf, doc, xls, mp4']),
            ]);
        }

        // Security: Validate file size
        if ($file->getSize() / 1024 > $this->maxFileSizeKB) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'file' => __('validation.max.file', ['attribute' => 'file', 'max' => $this->maxFileSizeKB]),
            ]);
        }

        // Delete old file if exists
        $this->deleteFile($currentFile, $disk);
        
        // Store new file with Tenant Isolation
        $tenantPrefix = app()->bound('tenant') ? app('tenant')->id : 'global';
        return $file->store("{$tenantPrefix}/{$storagePath}", $disk);
    }
    
    /**
     * Delete a file from storage.
     *
     * @param string|null $filePath
     * @param string $disk
     * @return bool
     */
    protected function deleteFile(?string $filePath, string $disk = 'public'): bool
    {
        if ($filePath && Storage::disk($disk)->exists($filePath)) {
            return Storage::disk($disk)->delete($filePath);
        }
        
        return false;
    }
}
