<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Routing\Controllers\HasMiddleware;

class ConsentReportController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            function ($request, $next) {
                if (!auth()->check()) {
                    abort(403, 'Unauthorized access to GDPR data.');
                }
                
                $user = auth()->user();
                if (!$user->hasRole('super_admin') && !$user->hasRole('center_admin')) {
                    abort(403, 'Unauthorized access to GDPR data.');
                }
                
                return $next($request);
            },
        ];
    }

    public function index()
    {
        $query = DB::table('user_consents');
        
        // Scope by tenant if not super_admin
        if (!auth()->user()->hasRole('super_admin')) {
            $query->where('tenant_id', app('tenant')->id ?? null);
        }

        // إحصائيات الموافقات
        $stats = [
            'total_consents' => (clone $query)->count(),
            'analytics_accepted' => (clone $query)->where('analytics_consent', true)->count(),
            'marketing_accepted' => (clone $query)->where('marketing_consent', true)->count(),
            'today_consents' => (clone $query)->whereDate('created_at', today())->count(),
        ];

        // أحدث 50 موافقة
        $recent_consents = (clone $query)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return view('admin.consent-report', compact('stats', 'recent_consents'));
    }

    public function export()
    {
        $query = DB::table('user_consents');
        
        // Scope by tenant if not super_admin
        if (!auth()->user()->hasRole('super_admin')) {
            $query->where('tenant_id', app('tenant')->id ?? null);
        }

        // تصدير جميع الموافقات إلى CSV
        $consents = $query->get();
        
        $filename = 'cookie-consents-' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($consents) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, ['ID', 'User ID', 'Session ID', 'IP', 'Analytics', 'Marketing', 'Date']);
            
            $sanitizeCsv = function ($value) {
                if (is_string($value) && preg_match('/^[=\+\-@]/', $value)) {
                    return "'" . $value;
                }
                return $value;
            };

            // Data
            foreach ($consents as $consent) {
                fputcsv($file, array_map($sanitizeCsv, [
                    $consent->id,
                    $consent->user_id ?? 'Guest',
                    $consent->session_id,
                    $consent->ip_address,
                    $consent->analytics_consent ? 'Yes' : 'No',
                    $consent->marketing_consent ? 'Yes' : 'No',
                    $consent->consent_date,
                ]));
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
