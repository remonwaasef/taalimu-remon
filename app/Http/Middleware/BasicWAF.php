<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class BasicWAF
{
    /**
     * Suspicious patterns to detect
     */
    protected $patterns = [
        // SQL Injection
        '/(\bselect\b|\bunion\b|\binsert\b|\bupdate\b|\bdelete\b|\bdrop\b|\bcreate\b|\balter\b).*(\bfrom\b|\binto\b|\bwhere\b)/i',
        '/(\bor\b|\band\b)\s+[\'"]?\d+[\'"]?\s*=\s*[\'"]?\d+[\'"]?/i',
        
        // XSS
        '/<script[^>]*>.*?<\/script>/i',
        '/javascript:/i',
        '/on\w+\s*=/i',
        
        // Path Traversal
        '/\.\.[\/\\\\]/',
        '/etc\/passwd/i',
        '/proc\/self/i',
    ];

    /**
     * The URIs that should be excluded from WAF filtering.
     *
     * @var array<int, string>
     */
    protected $except = [
        'auth/google/*',
        'login*',
        'register*',
        'payment/*',
        'webhooks/*',
        'stripe/*',
        'admin/*',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for excluded routes
        foreach ($this->except as $except) {
            if ($request->is($except)) {
                return $next($request);
            }
        }

        // Check all input for suspicious patterns (recursively)
        $inputs = array_merge(
            $request->all(),
            $request->query->all()
        );

        // Recursively extract all string values from nested arrays
        $flatValues = [];
        array_walk_recursive($inputs, function ($value, $key) use (&$flatValues) {
            if (is_string($value)) {
                $flatValues[$key] = $value;
            }
        });

        // Also check critical headers (User-Agent, Referer)
        foreach (['user-agent', 'referer'] as $header) {
            $headerValue = $request->header($header);
            if (is_string($headerValue)) {
                $flatValues['header_' . $header] = $headerValue;
            }
        }

        foreach ($flatValues as $key => $value) {
            if ($this->isSuspicious($value)) {
                Log::channel('security')->warning('Suspicious request detected', [
                    'ip' => $request->ip(),
                    'url' => $request->fullUrl(),
                    'input_key' => $key,
                    'input_value' => mb_substr($value, 0, 200),
                    'user_agent' => $request->userAgent(),
                ]);

                return response()->json([
                    'error' => 'Request blocked for security reasons'
                ], 403);
            }
        }

        return $next($request);
    }

    /**
     * Check if input contains suspicious patterns
     */
    protected function isSuspicious(string $input): bool
    {
        foreach ($this->patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }
        return false;
    }
}
