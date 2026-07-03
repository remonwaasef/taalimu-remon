<?php

namespace App\Services;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use PDOException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class IssueCategorizer
{
    /**
     * Severity mapping based on exception type
     */
    private array $severityMap = [
        QueryException::class => 'critical',
        PDOException::class => 'critical',
        \ErrorException::class => 'high',
        AuthenticationException::class => 'high',
        AuthorizationException::class => 'high',
        \RuntimeException::class => 'medium',
        ModelNotFoundException::class => 'medium',
        HttpException::class => 'medium',
        ValidationException::class => 'low',
        \InvalidArgumentException::class => 'low',
    ];

    /**
     * Category mapping based on exception type
     */
    private array $categoryMap = [
        QueryException::class => 'database',
        PDOException::class => 'database',
        ValidationException::class => 'validation',
        AuthenticationException::class => 'authentication',
        AuthorizationException::class => 'permission',
        ModelNotFoundException::class => 'not_found',
        HttpException::class => 'http',
        \RuntimeException::class => 'runtime',
        \ErrorException::class => 'error',
    ];

    /**
     * Keywords that indicate critical severity
     */
    private array $criticalKeywords = [
        'memory',
        'out of memory',
        'max_allowed_packet',
        'deadlock',
        'lock wait timeout',
        'connection refused',
        'access denied',
        'permission denied',
        'disk full',
        'no space left',
    ];

    /**
     * Determine severity based on exception
     */
    public function determineSeverity(Throwable $e): string
    {
        // Check for critical keywords in message
        $message = strtolower($e->getMessage());
        foreach ($this->criticalKeywords as $keyword) {
            if (str_contains($message, $keyword)) {
                return 'critical';
            }
        }

        // Check by exception class hierarchy
        foreach ($this->severityMap as $class => $severity) {
            if ($e instanceof $class) {
                return $severity;
            }
        }

        // Default based on HTTP status if available
        if ($e instanceof HttpException) {
            $status = $e->getStatusCode();
            if ($status >= 500) {
                return 'high';
            }
            if ($status >= 400) {
                return 'medium';
            }
        }

        return 'medium';
    }

    /**
     * Categorize exception
     */
    public function categorize(Throwable $e): string
    {
        // Check by exception class hierarchy
        foreach ($this->categoryMap as $class => $category) {
            if ($e instanceof $class) {
                return $category;
            }
        }

        // Try to guess from namespace/class name
        $className = get_class($e);

        if (str_contains($className, 'Database') || str_contains($className, 'Query')) {
            return 'database';
        }

        if (str_contains($className, 'Auth')) {
            return 'authentication';
        }

        if (str_contains($className, 'Validation')) {
            return 'validation';
        }

        if (str_contains($className, 'Http')) {
            return 'http';
        }

        return 'unknown';
    }

    /**
     * Generate a human-readable title from exception
     */
    public function generateTitle(Throwable $e): string
    {
        $className = class_basename($e);
        $message = $e->getMessage();

        // Truncate message if too long
        if (strlen($message) > 100) {
            $message = substr($message, 0, 97).'...';
        }

        // Clean up common patterns
        $message = preg_replace('/\s+/', ' ', $message);
        $message = trim($message);

        if (empty($message)) {
            return $className;
        }

        return "[{$className}] {$message}";
    }
}
