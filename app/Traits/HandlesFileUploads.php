<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait HandlesFileUploads
{
    /**
     * Handle file upload with automatic deletion of old file.
     *
     * @param Request $request
     * @param string $fieldName
     * @param string|null $currentFile
     * @param string $storagePath
     * @param string $disk
     * @return string|null
     */
    protected function handleFileUpload(
        Request $request,
        string $fieldName,
        ?string $currentFile,
        string $storagePath,
        string $disk = 'public'
    ): ?string {
        if ($request->hasFile($fieldName)) {
            // Delete old file if exists
            if ($currentFile) {
                Storage::disk($disk)->delete($currentFile);
            }
            
            // Store new file with Tenant Isolation
            $tenantPrefix = app()->bound('tenant') ? app('tenant')->id : 'global';
            // Ensure path doesn't end with slash, but prefix does if needed.
            // Actually, best to just put it in a folder: {tenant_id}/{path}
            // If storagePath is 'courses', result is '1/courses/filename.jpg'
            return $request->file($fieldName)->store("{$tenantPrefix}/{$storagePath}", $disk);
        }
        
        return $currentFile;
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
