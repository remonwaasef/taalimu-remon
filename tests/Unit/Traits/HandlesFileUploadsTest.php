<?php

namespace Tests\Unit\Traits;

use App\Traits\HandlesFileUploads;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HandlesFileUploadsTest extends TestCase
{
    private UploadHarness $harness;

    /** @var array<int, string> */
    private array $tempFiles = [];

    protected function setUp(): void
    {
        parent::setUp();

        if (! extension_loaded('gd')) {
            $this->markTestSkipped('GD extension is required to run WebP conversion tests.');
        }

        $this->harness = new UploadHarness;
        Storage::fake('public');

        // Tenant prefix in the storage path requires a bound tenant (no DB needed)
        app()->instance('tenant', (object) ['id' => 42]);
    }

    protected function tearDown(): void
    {
        foreach ($this->tempFiles as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }

        parent::tearDown();
    }

    private function makeImage(string $extension, string $mime, bool $alpha = false): UploadedFile
    {
        $image = imagecreatetruecolor(320, 200);
        $this->assertNotFalse($image, 'GD should be able to allocate a test image');

        for ($x = 0; $x < 320; $x++) {
            $color = imagecolorallocate($image, (int) ($x * 255 / 320), 128, 255 - (int) ($x * 255 / 320));
            imageline($image, $x, 0, $x, 200, $color);
        }

        if ($alpha) {
            $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
            imagefilledrectangle($image, 0, 0, 160, 200, $transparent);
        }

        $path = tempnam(sys_get_temp_dir(), 'upload_test_').'.'.$extension;
        $this->tempFiles[] = $path;

        $saved = match ($extension) {
            'png' => imagepng($image, $path),
            'jpg', 'jpeg' => imagejpeg($image, $path, 90),
            'gif' => imagegif($image, $path),
            default => false,
        };

        imagedestroy($image);
        $this->assertNotFalse($saved, "Failed to generate a {$extension} test image");

        return new UploadedFile($path, 'photo.'.$extension, $mime, UPLOAD_ERR_OK, true);
    }

    private function makePdf(): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'upload_test_').'.pdf';
        $this->tempFiles[] = $path;
        file_put_contents($path, "%PDF-1.4\n1 0 obj<</Type/Catalog>>endobj\ntrailer<</Root 1 0 R>>\n%%EOF");

        return new UploadedFile($path, 'document.pdf', 'application/pdf', UPLOAD_ERR_OK, true);
    }

    #[Test]
    public function it_converts_png_to_webp_on_upload()
    {
        $path = $this->harness->upload($this->makeImage('png', 'image/png', alpha: true), null, 'images');

        $this->assertNotNull($path);
        $this->assertStringEndsWith('.webp', $path);

        $stored = Storage::disk('public')->get($path);
        $this->assertNotNull($stored);
        $this->assertSame('RIFF', substr($stored, 0, 4));
        $this->assertSame('WEBP', substr($stored, 8, 4));
    }

    #[Test]
    public function it_converts_jpeg_to_webp_on_upload()
    {
        $path = $this->harness->upload($this->makeImage('jpg', 'image/jpeg'), null, 'images');

        $this->assertNotNull($path);
        $this->assertStringEndsWith('.webp', $path);
    }

    #[Test]
    public function it_keeps_non_images_untouched()
    {
        $path = $this->harness->upload($this->makePdf(), null, 'documents');

        $this->assertNotNull($path);
        $this->assertStringEndsWith('.pdf', $path);
        $this->assertStringStartsWith('%PDF', Storage::disk('public')->get($path));
    }

    #[Test]
    public function it_keeps_animated_gifs_untouched()
    {
        $gif = $this->makeImage('gif', 'image/gif');
        file_put_contents($gif->getRealPath(), file_get_contents($gif->getRealPath())."\x21\xFF\x0BNETSCAPE2.0\x03\x01\x00\x00\x00");

        $path = $this->harness->upload($gif, null, 'images');

        $this->assertNotNull($path);
        $this->assertStringEndsWith('.gif', $path);
    }

    #[Test]
    public function it_converts_static_gifs_to_webp()
    {
        $path = $this->harness->upload($this->makeImage('gif', 'image/gif'), null, 'images');

        $this->assertNotNull($path);
        $this->assertStringEndsWith('.webp', $path);
    }

    #[Test]
    public function it_falls_back_to_original_when_conversion_is_disabled()
    {
        config(['uploads.webp.enabled' => false]);

        $path = $this->harness->upload($this->makeImage('png', 'image/png'), null, 'images');

        $this->assertNotNull($path);
        $this->assertStringEndsWith('.png', $path);
        $this->assertStringStartsWith("\x89PNG", Storage::disk('public')->get($path));
    }

    #[Test]
    public function it_falls_back_to_original_when_gd_cannot_decode_the_image()
    {
        $file = $this->makeImage('png', 'image/png');
        // Valid PNG header (finfo still reports image/png) but truncated payload
        // — GD cannot decode it, so the original bytes must be stored as-is.
        file_put_contents($file->getRealPath(), substr(file_get_contents($file->getRealPath()), 0, 32));

        $path = $this->harness->upload($file, null, 'images');

        $this->assertNotNull($path);
        $this->assertStringEndsWith('.png', $path);
    }
}

class UploadHarness
{
    use HandlesFileUploads;

    public function upload(UploadedFile $file, ?string $currentFile, string $storagePath, string $disk = 'public'): ?string
    {
        return $this->uploadFile($file, $currentFile, $storagePath, $disk);
    }
}
