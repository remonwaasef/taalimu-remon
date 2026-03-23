<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupController extends Controller
{
    public function index()
    {
        $backups = [];
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
        $files = $disk->allFiles(config('backup.backup.name'));

        foreach ($files as $file) {
            if ($disk->exists($file) && substr($file, -4) == '.zip') {
                $backups[] = [
                    'file_path' => $file,
                    'file_name' => str_replace(config('backup.backup.name') . '/', '', $file),
                    'file_size' => $this->formatBytes($disk->size($file)),
                    'last_modified' => date('Y-m-d H:i:s', $disk->lastModified($file)),
                ];
            }
        }

        $backups = array_reverse($backups);

        return view('admin::backups.index', compact('backups'));
    }

    public function create()
    {
        try {
            // Run only database backup for speed and simplicity in this UI
            Artisan::call('backup:run', ['--only-db' => true]);
            
            return redirect()->back()->with('success', 'تم إنشاء النسخة الاحتياطية بنجاح.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'فشل إنشاء النسخة: ' . $e->getMessage());
        }
    }

    public function download($fileName)
    {
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
        $path = config('backup.backup.name') . '/' . $fileName;

        if ($disk->exists($path)) {
            return Storage::disk(config('backup.backup.destination.disks')[0])->download($path);
        }

        abort(404, "الملف غير موجود.");
    }

    public function delete($fileName)
    {
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
        $path = config('backup.backup.name') . '/' . $fileName;

        if ($disk->exists($path)) {
            $disk->delete($path);
            return redirect()->back()->with('success', 'تم حذف النسخة الاحتياطية.');
        }

        return redirect()->back()->with('error', 'الملف غير موجود.');
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
