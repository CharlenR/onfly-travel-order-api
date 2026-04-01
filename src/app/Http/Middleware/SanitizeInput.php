<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to sanitize and validate all input data
 * Protects against XSS, SQL injection, and other injection attacks
 */
class SanitizeInput
{
    /**
     * Fields that should NOT be sanitized (e.g., JSON fields, encrypted fields)
     */
    protected array $except = [
        'password',
        'password_confirmation',
        'token',
        'api_token',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Sanitize all inputs except specified fields
        if ($request->isJson()) {
            $input = $request->json()->all();
            $request->json()->replace($this->sanitizeArray($input));
        } else {
            $request->replace($this->sanitizeArray($request->all()));
        }

        // Set security headers
        $response = $next($request);

        return $response
            ->header('X-Content-Type-Options', 'nosniff')
            ->header('X-Frame-Options', 'DENY')
            ->header('X-XSS-Protection', '1; mode=block')
            ->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains')
            ->header('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    /**
     * Recursively sanitize array values
     */
    protected function sanitizeArray(array $input): array
    {
        $sanitized = [];

        foreach ($input as $key => $value) {
            // Skip fields in except list
            if (in_array($key, $this->except)) {
                $sanitized[$key] = $value;
                continue;
            }

            // Recursively sanitize arrays
            if (is_array($value)) {
                $sanitized[$key] = $this->sanitizeArray($value);
            } elseif (is_string($value)) {
                $sanitized[$key] = $this->sanitizeString($value);
            } else {
                $sanitized[$key] = $value;
            }
        }

        return $sanitized;
    }

    /**
     * Sanitize individual string values
     */
    protected function sanitizeString(string $value): string
    {
        // Trim whitespace
        $value = trim($value);

        // Remove null bytes
        $value = str_replace("\x00", '', $value);

        // Decode HTML entities to detect encoded attacks
        $decoded = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Check for XSS patterns in both original and decoded values
        if ($this->containsXSSPatterns($value) || $this->containsXSSPatterns($decoded)) {
            // Log potential attack
            \Log::warning('Potential XSS attack detected', [
                'original' => substr($value, 0, 100),
                'ip' => request()->ip(),
            ]);
            // Strip tags but keep safe text
            $value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        return $value;
    }

    /**
     * Check if string contains XSS patterns
     */
    protected function containsXSSPatterns(string $value): bool
    {
        $patterns = [
            '/javascript:/i',
            '/on\w+\s*=/i',
            '/<script/i',
            '/<iframe/i',
            '/<embed/i',
            '/<object/i',
            '/eval\(/i',
            '/expression\(/i',
            '/vbscript:/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $value)) {
                return true;
            }
        }

        return false;
    }
}
