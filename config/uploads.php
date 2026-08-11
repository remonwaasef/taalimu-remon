<?php

return [

    /*
    |--------------------------------------------------------------------------
    | WebP Auto-Compression
    |--------------------------------------------------------------------------
    |
    | Raster images (JPEG, PNG, WebP, non-animated GIF) are automatically
    | converted to WebP at upload time to cut storage and bandwidth costs.
    | Conversion requires the GD extension (imagewebp) or Imagick; when no
    | converter is available the original file is stored untouched.
    |
    */

    'webp' => [

        'enabled' => env('UPLOAD_WEBP_ENABLED', true),

        'quality' => (int) env('UPLOAD_WEBP_QUALITY', 80),

        /*
         * Keep the original file instead of the WebP when the conversion
         * does not actually reduce the file size (e.g. already optimized
         * sources). When false the WebP is always used.
         */
        'keep_smaller_only' => true,

        /*
         * MIME types eligible for conversion. Only types present in
         * HandlesFileUploads::$allowedMimeToExt can ever reach storage.
         */
        'convertible_mimes' => [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
        ],

        /*
         * Animated GIFs are never converted: re-encoding destroys the
         * animation frames and the original format is preserved instead.
         */
        'skip_animated_gifs' => true,

    ],

];
