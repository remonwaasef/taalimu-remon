<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BugReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BugReportController extends Controller
{
    /**
     * Store a new bug report from the floating widget.
     * Captures automatic technical context and sends notifications via Telegram + Email.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'category' => 'required|in:bug,suggestion,ui_issue,performance,other',
            'screenshot' => 'nullable|image|max:5120', // Max 5MB
        ]);

        $screenshotPath = null;
        $errorDebug = null;

        try {
            if ($request->hasFile('screenshot')) {
                $file = $request->file('screenshot');
                $safeExt = in_array($file->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif', 'webp']) ? $file->getClientOriginalExtension() : 'png';
                $fileName = 'bug-reports/'.\Illuminate\Support\Str::random(30).'.'.$safeExt;

                // Save to Laravel's internal storage (storage/app/public) using streaming to save memory
                $file->storeAs(
                    dirname($fileName),
                    basename($fileName),
                    'public'
                );
                $screenshotPath = $fileName;

            } elseif ($request->filled('auto_screenshot')) {
                $imageData = $request->input('auto_screenshot');

                if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                    $extension = strtolower($type[1]);
                    // Security: Only allow safe image extensions from base64
                    if (! in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                        $extension = 'png';
                    }
                    $image = base64_decode(substr($imageData, strpos($imageData, ',') + 1));

                    if ($image) {
                        $fileName = 'bug-reports/'.\Illuminate\Support\Str::random(30).'_auto.'.$extension;

                        // Save to Laravel's internal storage (storage/app/public)
                        if (\Illuminate\Support\Facades\Storage::disk('public')->put($fileName, $image)) {
                            $screenshotPath = $fileName;

                        } else {
                            $errorDebug = 'Storage::put failed for '.$fileName;
                        }
                    } else {
                        $errorDebug = 'Base64 decode failed';
                    }
                } else {
                    $errorDebug = 'Regex match failed for image data';
                }
            }
        } catch (\Exception $e) {
            $errorDebug = 'Exception: '.$e->getMessage();
            Log::error('Bug Report Image Save Error: '.$e->getMessage());
        }

        $tenantId = app('tenant')->id ?? auth()->user()->tenant_id ?? null;

        $report = BugReport::create([
            'tenant_id' => $tenantId,
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'priority' => $this->detectPriority($request->description),
            'page_url' => $request->input('page_url'),
            'browser_info' => $request->input('browser_info') ? json_decode($request->input('browser_info'), true) : null,
            'screenshot' => $screenshotPath,
            'status' => 'open',
            'admin_notes' => $errorDebug ? 'Error saving screenshot: '.$errorDebug : null,
        ]);

        // Eager load relations to prevent N+1 queries in the queue worker
        $report->load(['tenant', 'user']);

        // Send notifications in the background
        \App\Jobs\ProcessBugReportNotifications::dispatch($report, $errorDebug);

        return response()->json([
            'success' => true,
            'message' => __('center::bug_report.submitted_successfully'),
        ]);
    }

    /**
     * Auto-detect priority based on keywords in the description.
     */
    private function detectPriority(string $description): string
    {
        $description = mb_strtolower($description);

        $criticalKeywords = ['crash', 'broken', 'لا يعمل', 'خطأ فادح', 'data loss', 'فقدان بيانات', 'security', 'أمان'];
        $highKeywords = ['error', 'خطأ', 'bug', 'fail', 'فشل', 'لا يظهر', 'مشكلة كبيرة'];
        $lowKeywords = ['suggestion', 'اقتراح', 'تحسين', 'improvement', 'would be nice'];

        foreach ($criticalKeywords as $kw) {
            if (str_contains($description, $kw)) {
                return 'critical';
            }
        }
        foreach ($highKeywords as $kw) {
            if (str_contains($description, $kw)) {
                return 'high';
            }
        }
        foreach ($lowKeywords as $kw) {
            if (str_contains($description, $kw)) {
                return 'low';
            }
        }

        return 'medium';
    }
}
