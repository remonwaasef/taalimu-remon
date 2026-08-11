<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
     * Maximum bytes a raster image may occupy to be eligible for WebP
     * conversion. Bounds the peak memory used by the decoder (GD decodes
     * the full bitmap into RAM, so huge sources are stored as-is instead).
     */
    protected int $maxWebpSourceBytes = 8388608;

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

        return $this->storeUploadedFile($file, $fullPath, $safeFilename, $disk);
    }

    /**
     * Store the uploaded file. Raster images are transparently converted to
     * WebP first (when enabled and beneficial); every other file type and
     * every conversion failure falls back to the original bytes.
     */
    protected function storeUploadedFile(UploadedFile $file, string $fullPath, string $safeFilename, string $disk): string
    {
        if ($this->shouldConvertToWebp($file)) {
            $converted = $this->convertToWebp($file);

            if ($converted !== null) {
                [$tmpPath, $webpSize] = $converted;

                try {
                    $keepSmallerOnly = (bool) config('uploads.webp.keep_smaller_only', true);

                    if (! $keepSmallerOnly || $webpSize < $file->getSize()) {
                        return Storage::disk($disk)->putFileAs($fullPath, $tmpPath, $safeFilename.'.webp');
                    }
                } finally {
                    if (is_file($tmpPath)) {
                        @unlink($tmpPath);
                    }
                }
            }
        }

        return $file->storeAs($fullPath, $safeFilename, $disk);
    }

    /**
     * Whether the uploaded file should go through WebP conversion.
     */
    protected function shouldConvertToWebp(UploadedFile $file): bool
    {
        if (! (bool) config('uploads.webp.enabled', true)) {
            return false;
        }

        $mimeType = $file->getMimeType();

        if (! in_array($mimeType, (array) config('uploads.webp.convertible_mimes', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']), true)) {
            return false;
        }

        // Large bitmaps are too memory-hungry for GD; keep them as-is.
        if ($file->getSize() > $this->maxWebpSourceBytes) {
            return false;
        }

        if ($mimeType === 'image/gif' && (bool) config('uploads.webp.skip_animated_gifs', true) && $this->isAnimatedGif($file)) {
            return false;
        }

        return (int) config('uploads.webp.quality', 80) > 0;
    }

    /**
     * Convert a raster image to WebP using Imagick when available and GD
     * otherwise. Returns [temporaryPath, sizeInBytes] on success or null.
     *
     * @return array{0: string, 1: int}|null
     */
    protected function convertToWebp(UploadedFile $file): ?array
    {
        $tmpPath = tempnam(sys_get_temp_dir(), 'taalimu_webp_');

        if ($tmpPath === false) {
            return null;
        }

        try {
            if (class_exists(\Imagick::class)) {
                $image = new \Imagick($file->getRealPath());
                $image->setImageFormat('webp');
                $image->setOption('webp:lossless', 'false');
                $image->setImageCompressionQuality((int) config('uploads.webp.quality', 80));
                $written = $image->writeImage($tmpPath);
                $image->clear();

                if (! $written) {
                    @unlink($tmpPath);

                    return null;
                }
            } elseif (function_exists('imagewebp') && function_exists('imagecreatefromstring')) {
                $source = $this->loadImageWithGd($file);

                if ($source === null) {
                    @unlink($tmpPath);

                    return null;
                }

                $written = imagewebp($source, $tmpPath, (int) config('uploads.webp.quality', 80));
                imagedestroy($source);

                if (! $written) {
                    @unlink($tmpPath);

                    return null;
                }
            } else {
                @unlink($tmpPath);

                return null;
            }

            if (! is_file($tmpPath) || filesize($tmpPath) === 0) {
                @unlink($tmpPath);

                return null;
            }

            return [$tmpPath, filesize($tmpPath)];
        } catch (\Throwable $e) {
            Log::warning('WebP conversion failed, falling back to the original upload', [
                'error' => $e->getMessage(),
                'original_name' => $file->getClientOriginalName(),
                'mime' => $file->getMimeType(),
            ]);

            @unlink($tmpPath);

            return null;
        }
    }

    /**
     * Decode an image into a truecolor GD resource, preserving the alpha
     * channel (palette PNGs are promoted to truecolor so transparency
     * survives the WebP encoding).
     */
    protected function loadImageWithGd(UploadedFile $file): ?\GdImage
    {
        $content = file_get_contents($file->getRealPath());

        if ($content === false) {
            return null;
        }

        $image = @imagecreatefromstring($content);

        if ($image === false) {
            return null;
        }

        if (! imageistruecolor($image)) {
            $trueColor = imagecreatetruecolor(imagesx($image), imagesy($image));

            if ($trueColor === false) {
                imagedestroy($image);

                return null;
            }

            imagealphablending($trueColor, false);
            imagesavealpha($trueColor, true);
            imagecopy($trueColor, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
            imagedestroy($image);
            $image = $trueColor;
        }

        imagealphablending($image, false);
        imagesavealpha($image, true);

        return $image;
    }

    /**
     * Detect animation in a GIF by scanning for the NETSCAPE2.0 looping
     * application extension block (present in every animated GIF).
     */
    protected function isAnimatedGif(UploadedFile $file): bool
    {
        $handle = fopen($file->getRealPath(), 'rb');

        if ($handle === false) {
            return true;
        }

        $content = fread($handle, 65536);
        fclose($handle);

        return str_contains((string) $content, "\x21\xFF\x0BNETSCAPE2.0");
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
