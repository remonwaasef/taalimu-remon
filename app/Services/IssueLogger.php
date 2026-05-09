<?php

namespace App\Services;

use App\Models\OperationIssue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class IssueLogger
{
    public function __construct(
        protected IssueCategorizer $categorizer,
        protected DuplicateDetector $duplicateDetector,
        protected IssueNotifier $notifier
    ) {}

    /**
     * Log an exception automatically
     */
    public function logException(Throwable $e, ?Request $request = null): ?OperationIssue
    {
        try {
            // Generate fingerprint for duplicate detection
            $fingerprint = OperationIssue::generateFingerprint($e);

            // Check for existing similar issue
            $existing = $this->duplicateDetector->find($fingerprint);
            if ($existing) {
                $existing->incrementOccurrence();
                return $existing;
            }

            // Create new issue
            $issue = OperationIssue::create([
                'uuid' => Str::uuid(),
                'tenant_id' => \Modules\Tenancy\app\Services\TenantResolver::id(),
                'user_id' => auth()->id(),
                'title' => $this->categorizer->generateTitle($e),
                'action' => $request?->route()?->getName() ?? $this->guessActionFromRequest($request),
                'url' => $request?->fullUrl(),
                'method' => $request?->method() ?? 'N/A',
                'message' => $e->getMessage(),
                'payload' => $this->sanitizePayload($request?->all()),
                'context' => $this->captureContext($request),
                'stack_trace' => $e->getTraceAsString(),
                'severity' => $this->categorizer->determineSeverity($e),
                'category' => $this->categorizer->categorize($e),
                'exception_class' => get_class($e),
                'exception_code' => (string) $e->getCode(),
                'file_path' => $e->getFile(),
                'line_number' => $e->getLine(),
                'fingerprint' => $fingerprint,
                'user_agent' => $request?->userAgent(),
                'ip_address' => $request?->ip(),
            ]);

            // Record creation in timeline
            $issue->timeline()->create([
                'type' => 'created',
                'new_value' => [
                    'severity' => $issue->severity,
                    'category' => $issue->category,
                ],
            ]);

            // Notify administrators if critical
            $this->notifier->notifyIfNeeded($issue);

            return $issue;
        } catch (Throwable $logError) {
            // Prevent infinite loops - just log to file
            Log::error('IssueLogger failed to log exception', [
                'original_error' => $e->getMessage(),
                'logger_error' => $logError->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Log a manual issue
     */
    public function log(string $action, string $message, array $options = []): ?OperationIssue
    {
        try {
            $request = request();
            
            $issue = OperationIssue::create([
                'uuid' => Str::uuid(),
                'tenant_id' => $options['tenant_id'] ?? (\Modules\Tenancy\app\Services\TenantResolver::id()),
                'user_id' => $options['user_id'] ?? auth()->id(),
                'title' => $options['title'] ?? "[Manual] {$message}",
                'action' => $action,
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'message' => $message,
                'payload' => $this->sanitizePayload($options['payload'] ?? null),
                'context' => $options['context'] ?? null,
                'stack_trace' => $options['stack_trace'] ?? null,
                'severity' => $options['severity'] ?? 'medium',
                'category' => $options['category'] ?? 'business_logic',
                'fingerprint' => md5($action . '|' . $message),
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
                'tags' => $options['tags'] ?? null,
            ]);

            // Record creation in timeline
            $issue->timeline()->create([
                'type' => 'created',
                'new_value' => [
                    'severity' => $issue->severity,
                    'category' => $issue->category,
                    'manual' => true,
                ],
            ]);

            return $issue;
        } catch (Throwable $e) {
            Log::error('IssueLogger failed to log manual issue', [
                'action' => $action,
                'message' => $message,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Log validation errors
     */
    public function logValidation(array $errors, Request $request): ?OperationIssue
    {
        return $this->log(
            $request->route()?->getName() ?? 'validation.failed',
            'Validation failed: ' . json_encode(array_keys($errors)),
            [
                'severity' => 'low',
                'category' => 'validation',
                'payload' => $this->sanitizePayload($request->all()),
                'context' => ['validation_errors' => $errors],
            ]
        );
    }

    /**
     * Sanitize payload to remove sensitive data
     */
    protected function sanitizePayload(?array $data): ?array
    {
        if (!$data) {
            return null;
        }

        $sensitive = [
            'password',
            'password_confirmation',
            'current_password',
            'new_password',
            'token',
            'api_token',
            'secret',
            'credit_card',
            'card_number',
            'cvv',
            'ssn',
        ];

        return collect($data)->map(function ($value, $key) use ($sensitive) {
            // Check if key contains sensitive keywords
            $keyLower = strtolower($key);
            foreach ($sensitive as $sensitiveKey) {
                if (str_contains($keyLower, $sensitiveKey)) {
                    return '[REDACTED]';
                }
            }
            
            // Recursively sanitize nested arrays
            if (is_array($value)) {
                return $this->sanitizePayload($value);
            }
            
            return $value;
        })->toArray();
    }

    /**
     * Capture additional context from request
     */
    protected function captureContext(?Request $request): ?array
    {
        if (!$request) {
            return null;
        }

        return [
            'headers' => $this->sanitizeHeaders($request->headers->all()),
            'session_id' => $request->session()?->getId() ?? null,
            'locale' => app()->getLocale(),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'memory_usage' => memory_get_peak_usage(true),
        ];
    }

    /**
     * Sanitize headers to remove sensitive information
     */
    protected function sanitizeHeaders(array $headers): array
    {
        $sensitiveHeaders = [
            'authorization',
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ];

        return collect($headers)->map(function ($value, $key) use ($sensitiveHeaders) {
            if (in_array(strtolower($key), $sensitiveHeaders)) {
                return ['[REDACTED]'];
            }
            return $value;
        })->toArray();
    }

    /**
     * Guess action name from request when route name is not available
     */
    protected function guessActionFromRequest(?Request $request): string
    {
        if (!$request) {
            return 'unknown';
        }

        $path = $request->path();
        $method = strtolower($request->method());

        // Clean up path
        $path = preg_replace('/\/\d+/', '.{id}', $path);
        $path = str_replace('/', '.', $path);

        return "{$method}.{$path}";
    }
}
