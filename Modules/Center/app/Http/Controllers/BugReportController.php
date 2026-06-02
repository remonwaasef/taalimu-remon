<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BugReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
                $safeExt = in_array($file->getClientOriginalExtension(), ['jpg','jpeg','png','gif','webp']) ? $file->getClientOriginalExtension() : 'png';
                $fileName = 'bug-reports/' . \Illuminate\Support\Str::random(30) . '.' . $safeExt;
                
                // Save to Laravel's internal storage (storage/app/public)
                \Illuminate\Support\Facades\Storage::disk('public')->put(
                    $fileName,
                    file_get_contents($file->getRealPath())
                );
                $screenshotPath = $fileName;
                
                // Also try saving to public/storage as fallback
                $this->copyToPublicStorage($fileName);
                
            } elseif ($request->filled('auto_screenshot')) {
                $imageData = $request->input('auto_screenshot');
                
                if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                    $extension = strtolower($type[1]);
                    // Security: Only allow safe image extensions from base64
                    if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                        $extension = 'png';
                    }
                    $image = base64_decode(substr($imageData, strpos($imageData, ',') + 1));
                    
                    if ($image) {
                        $fileName = 'bug-reports/' . \Illuminate\Support\Str::random(30) . '_auto.' . $extension;
                        
                        // Save to Laravel's internal storage (storage/app/public)
                        if (\Illuminate\Support\Facades\Storage::disk('public')->put($fileName, $image)) {
                            $screenshotPath = $fileName;
                            
                            // Also try saving to public/storage as fallback
                            $this->copyToPublicStorage($fileName);
                        } else {
                            $errorDebug = "Storage::put failed for " . $fileName;
                        }
                    } else {
                        $errorDebug = "Base64 decode failed";
                    }
                } else {
                    $errorDebug = "Regex match failed for image data";
                }
            }
        } catch (\Exception $e) {
            $errorDebug = "Exception: " . $e->getMessage();
            Log::error("Bug Report Image Save Error: " . $e->getMessage());
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
            'admin_notes' => $errorDebug ? "Error saving screenshot: " . $errorDebug : null,
        ]);

        // Send notifications
        $this->sendTelegramNotification($report, $errorDebug);
        $this->sendEmailNotification($report);

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
            if (str_contains($description, $kw)) return 'critical';
        }
        foreach ($highKeywords as $kw) {
            if (str_contains($description, $kw)) return 'high';
        }
        foreach ($lowKeywords as $kw) {
            if (str_contains($description, $kw)) return 'low';
        }

        return 'medium';
    }

    /**
     * Send notification to Telegram.
     */
    private function sendTelegramNotification(BugReport $report, ?string $debugError = null): void
    {
        try {
            $botToken = config('services.telegram.bot_token');
            $chatId = config('services.telegram.admin_chat_id');

            if (!$botToken || !$chatId) {
                return;
            }

            $tenant = $report->tenant;
            $user = $report->user;

            $priorityEmoji = match($report->priority) {
                'critical' => '🔴',
                'high' => '🟠',
                'medium' => '🟡',
                'low' => '🟢',
                default => '⚪',
            };

            $categoryEmoji = match($report->category) {
                'bug' => '🐛',
                'suggestion' => '💡',
                'ui_issue' => '🎨',
                'performance' => '⚡',
                default => '📝',
            };

            $message = "━━━━━━━━━━━━━━━━━━━━\n";
            $message .= "🚨 *بلاغ جديد - تعليمُه*\n";
            $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
            $message .= "{$categoryEmoji} *النوع:* {$report->category}\n";
            $message .= "{$priorityEmoji} *الأولوية:* {$report->priority}\n\n";
            $message .= "📌 *العنوان:* {$report->title}\n\n";
            $message .= "📝 *الوصف:*\n{$report->description}\n\n";
            
            if ($debugError) {
                $message .= "⚠️ *خطأ في الصورة:* `{$debugError}`\n\n";
            } else {
                $fullRealPath = $report->screenshot ? (\Illuminate\Support\Facades\Storage::disk('public')->path($report->screenshot)) : null;
                if ($fullRealPath && file_exists($fullRealPath)) {
                    $message .= "📸 *تم إرفاق لقطة الشاشة أدناه*\n\n";
                }
            }

            $message .= "━━━━━━━━━━━━━━━━━━━━\n";
            $message .= "🏢 *المركز:* " . ($tenant?->name ?? 'N/A') . "\n";
            $message .= "👤 *المستخدم:* " . ($user?->name ?? 'N/A') . "\n";
            $message .= "📧 *الإيميل:* " . ($user?->email ?? 'N/A') . "\n";
            $message .= "🔗 *الصفحة:* {$report->page_url}\n";
            $message .= "📱 *المتصفح:* " . ($report->browser_info['browser'] ?? 'N/A') . "\n";
            $message .= "🆔 *رقم البلاغ:* #{$report->id}\n";
            $message .= "━━━━━━━━━━━━━━━━━━━━";

            $fullRealPath = $report->screenshot ? (\Illuminate\Support\Facades\Storage::disk('public')->path($report->screenshot)) : null;

            if ($fullRealPath && file_exists($fullRealPath)) {
                // Send as Photo
                \Illuminate\Support\Facades\Http::attach(
                    'photo', file_get_contents($fullRealPath), basename($fullRealPath)
                )->post("https://api.telegram.org/bot{$botToken}/sendPhoto", [
                    'chat_id' => $chatId,
                    'caption' => $message,
                    'parse_mode' => 'Markdown',
                ]);
            } else {
                // Send as Text only
                \Illuminate\Support\Facades\Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => $message,
                    'parse_mode' => 'Markdown',
                ]);
            }
        } catch (\Exception $e) {
            Log::warning('Telegram bug report notification failed: ' . $e->getMessage());
        }
    }

    /**
     * Send notification to developer email.
     */
    private function sendEmailNotification(BugReport $report): void
    {
        try {
            $developerEmail = config('mail.bug_report_address', config('mail.from.address'));

            if (!$developerEmail) {
                return;
            }

            $tenant = $report->tenant;
            $user = $report->user;

            $subject = "[Taalimu Bug #{$report->id}] [{$report->priority}] {$report->title}";

            $body = "<div style='font-family: Cairo, Arial, sans-serif; direction: rtl; padding: 20px;'>";
            $body .= "<div style='background: linear-gradient(135deg, #059669, #047857); color: white; padding: 20px; border-radius: 12px; margin-bottom: 20px;'>";
            $body .= "<h2 style='margin: 0;'>🚨 بلاغ جديد - تعليمُه</h2>";
            $body .= "<p style='margin: 5px 0 0; opacity: 0.9;'>#{$report->id} | {$report->priority} | {$report->category}</p>";
            $body .= "</div>";

            $body .= "<div style='background: #f8fafc; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0;'>";
            $body .= "<h3 style='color: #1e293b;'>📌 {$report->title}</h3>";
            $body .= "<p style='color: #475569; line-height: 1.8;'>{$report->description}</p>";
            $body .= "<hr style='border: none; border-top: 1px solid #e2e8f0;'>";
            $body .= "<table style='width: 100%; color: #64748b; font-size: 14px;'>";
            $body .= "<tr><td><strong>🏢 المركز:</strong></td><td>" . ($tenant?->name ?? 'N/A') . "</td></tr>";
            $body .= "<tr><td><strong>👤 المستخدم:</strong></td><td>" . ($user?->name ?? 'N/A') . " (" . ($user?->email ?? 'N/A') . ")</td></tr>";
            $body .= "<tr><td><strong>🔗 الصفحة:</strong></td><td><a href='{$report->page_url}'>{$report->page_url}</a></td></tr>";
            $body .= "<tr><td><strong>📱 المتصفح:</strong></td><td>" . ($report->browser_info['browser'] ?? 'N/A') . " / " . ($report->browser_info['os'] ?? 'N/A') . "</td></tr>";
            $body .= "<tr><td><strong>🕐 الوقت:</strong></td><td>{$report->created_at}</td></tr>";
            $body .= "</table>";
            $body .= "</div></div>";

            Mail::html($body, function ($mail) use ($developerEmail, $subject) {
                $mail->to($developerEmail)
                     ->subject($subject);
            });

        } catch (\Exception $e) {
            Log::warning('Email bug report notification failed: ' . $e->getMessage());
        }
    }

    /**
     * Copy a stored file to public/storage as a fallback for direct URL access.
     * This handles servers where the storage symlink may not be working.
     */
    private function copyToPublicStorage(string $fileName): void
    {
        try {
            $sourcePath = storage_path('app/public/' . $fileName);
            $destPath = public_path('storage/' . $fileName);
            $destDir = dirname($destPath);

            if (!file_exists($destDir)) {
                mkdir($destDir, 0755, true);
            }

            if (file_exists($sourcePath)) {
                copy($sourcePath, $destPath);
            }
        } catch (\Exception $e) {
            Log::warning('Failed to copy screenshot to public storage: ' . $e->getMessage());
        }
    }
}
