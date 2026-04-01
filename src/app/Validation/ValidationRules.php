<?php

namespace App\Validation;

use Illuminate\Validation\Rule;

/**
 * Custom validation rules for input sanitization and security
 */
class ValidationRules
{
    /**
     * Safe string rule - prevents XSS attacks
     * No HTML tags, scripts, or special characters
     *
     * @return \Closure
     */
    public static function safeString(): \Closure
    {
        return function ($attribute, $value, $fail) {
            // Remove any HTML/script tags
            $cleaned = strip_tags($value);

            // Check for common XSS patterns
            $xssPatterns = [
                '/javascript:/i',
                '/on\w+\s*=/i',  // onload=, onclick=, etc
                '/<script/i',
                '/<iframe/i',
                '/eval\(/i',
                '/expression\(/i',
                '/vbscript:/i',
                '/livescript:/i',
            ];

            foreach ($xssPatterns as $pattern) {
                if (preg_match($pattern, $value)) {
                    $fail("The $attribute field contains invalid characters or scripts.");
                    return;
                }
            }

            // Check for SQL injection patterns
            $sqlPatterns = [
                '/(\bunion\b.*\bselect\b|\bselect\b.*\bfrom\b|\bdelete\b.*\bfrom\b|\bdrop\b.*\btable\b)/i',
                '/(-{2}|\/\*|;\s*(union|select|delete|drop|insert|update))/i',
            ];

            foreach ($sqlPatterns as $pattern) {
                if (preg_match($pattern, $value)) {
                    $fail("The $attribute field contains invalid patterns.");
                    return;
                }
            }

            // Check if cleaned value differs significantly (potential encoding attack)
            if (strlen($cleaned) === 0 && strlen($value) > 0) {
                $fail("The $attribute field contains only special characters.");
            }
        };
    }

    /**
     * Email rule - safe email validation
     *
     * @return array
     */
    public static function email(): array
    {
        return [
            'required',
            'email:rfc,dns',
            'max:255',
            'lowercase',
        ];
    }

    /**
     * Name rule - safe name validation (requester_name, destination)
     *
     * @return array
     */
    public static function nameField(): array
    {
        return [
            'required',
            'string',
            'max:255',
            'min:2',
            'regex:/^[a-zA-Z\s\-\.\'àáâãäåæçèéêëìíîïðñòóôõöøùúûüýþÿœ]+$/',
        ];
    }

    /**
     * Date rule - safe date validation
     *
     * @return array
     */
    public static function futureDate(): array
    {
        return [
            'required',
            'date_format:Y-m-d',
            'after_or_equal:today',
            'before_or_equal:+5 years',
        ];
    }

    /**
     * Status rule - safe enum validation
     *
     * @return array
     */
    public static function travelOrderStatus(): array
    {
        return [
            'required',
            'string',
            'max:50',
            Rule::enum(\App\Enums\TravelOrderStatus::class),
        ];
    }
}
