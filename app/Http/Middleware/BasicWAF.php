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
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check all input for suspicious patterns
        $inputs = array_merge(
            $request->all(),
            $request->query->all(),
            $request->headers->all()
        );

        foreach ($inputs as $key => $value) {
            if (is_string($value) && $this->isSuspicious($value)) {
                Log::channel('security')->warning('Suspicious request detected', [
                    'ip' => $request->ip(),
                    'url' => $request->fullUrl(),
                    'input_key' => $key,
                    'input_value' => $value,
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
