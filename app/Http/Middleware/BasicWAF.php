<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

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
        // Match real HTML event-handler attributes only (inside a tag), not any
        // plain text starting with "on" (e.g. "one = 1" in a notes field).
        '/<[^>]+\son\w+\s*=/i',

        // Path Traversal
        '/\.\.[\/\\\\]/',
        '/etc\/passwd/i',
        '/proc\/self/i',
    ];

    /**
     * Field-level exclusions: specific input fields on specific routes
     * that should be skipped during WAF inspection (e.g. OAuth tokens,
     * payment HMAC signatures) to avoid false positives.
     *
     * Format: 'route_pattern' => ['field1', 'field2']
     */
    protected $fieldExclusions = [
        'auth/google/*' => ['code', 'state', 'scope', 'authuser', 'prompt', 'session_state'],
        'payment/paymob/*' => ['hmac', 'token', 'source_data_pan', 'source_data_sub_type'],
        'payment/paypal/*' => ['token', 'PayerID', 'ba_token'],
        // Payment webhooks live at /webhooks/* (see routes/web.php), not /api/webhooks/*
        'webhooks/*' => ['hmac', 'obj'],
        'api/webhooks/*' => ['hmac', 'obj'],
        // Lesson body: an LMS legitimately teaches SQL/JS, so "select ... from" or
        // "<script>" in course text must not 403. The field is sanitized with
        // HTMLPurifier on write (CurriculumController::updateLesson) instead.
        '*/lessons/*' => ['content'],
        '*/sections/*/lessons' => ['content'],
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Determine which fields to skip for the current route
        $excludedFields = $this->getExcludedFields($request);

        // Check all input for suspicious patterns (recursively)
        $inputs = array_merge(
            $request->all(),
            $request->query->all()
        );

        // Flatten with full dot-paths. array_walk_recursive keyed by leaf name,
        // so a later benign field with the same name overwrote (and hid) an
        // earlier suspicious value — dot-paths are collision-free.
        $flatValues = [];
        foreach (\Illuminate\Support\Arr::dot($inputs) as $path => $value) {
            if (is_string($value)) {
                $flatValues[$path] = $value;
            }
        }

        // Also check critical headers (User-Agent, Referer)
        foreach (['user-agent', 'referer'] as $header) {
            $headerValue = $request->header($header);
            if (is_string($headerValue)) {
                $flatValues['header_'.$header] = $headerValue;
            }
        }

        foreach ($flatValues as $key => $value) {
            // Skip excluded fields for this route (OAuth tokens, HMAC signatures, etc.).
            // Match any dot-path segment so excluding a parent key (e.g. Paymob's
            // "obj") also covers its nested values.
            if ($excludedFields && array_intersect(explode('.', (string) $key), $excludedFields)) {
                continue;
            }

            if ($this->isSuspicious($value)) {
                Log::channel('security')->warning('Suspicious request detected', [
                    'ip' => $request->ip(),
                    'url' => $request->fullUrl(),
                    'input_key' => $key,
                    'input_value' => mb_substr($value, 0, 200),
                    'user_agent' => $request->userAgent(),
                ]);

                return response()->json([
                    'error' => 'Request blocked for security reasons',
                ], 403);
            }
        }

        return $next($request);
    }

    /**
     * Get excluded fields for the current request path.
     */
    protected function getExcludedFields(Request $request): array
    {
        foreach ($this->fieldExclusions as $pattern => $fields) {
            if ($request->is($pattern)) {
                return $fields;
            }
        }

        return [];
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
