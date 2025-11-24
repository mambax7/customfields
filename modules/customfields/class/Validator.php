<?php
namespace XoopsModules\Customfields;

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

/**
 * Central validation and normalization helper for CustomFields.
 * Compatible with PHP 7.4+.
 */
class Validator
{
    /**
     * Validate arbitrary text with max length and optional regex.
     * Returns a trimmed string truncated to max length; if regex provided and fails, returns empty string.
     */
    public function validateText($value, $maxLen = 255, $regex = null)
    {
        $s = (string)$value;
        $s = trim($s);
        if ($maxLen > 0 && mb_strlen($s, 'UTF-8') > $maxLen) {
            $s = mb_substr($s, 0, $maxLen, 'UTF-8');
        }
        if ($regex !== null && @preg_match($regex, '') !== false) {
            if (!preg_match($regex, $s)) {
                return '';
            }
        }
        return $s;
    }

    /**
     * Validate a number. When $integer is true, returns int; else returns float. Returns null if invalid or out of range.
     */
    public function validateNumber($value, $min = null, $max = null, $integer = false)
    {
        if ($value === '' || $value === null) {
            return null;
        }
        if ($integer) {
            if (!is_numeric($value)) {
                return null;
            }
            $n = (int)$value;
        } else {
            if (!is_numeric($value)) {
                return null;
            }
            $n = (float)$value;
        }
        if ($min !== null && $n < $min) {
            return null;
        }
        if ($max !== null && $n > $max) {
            return null;
        }
        return $n;
    }

    /**
     * Validate date string against format (default 'Y-m-d'). Returns canonical formatted date or null.
     */
    public function validateDate($value, $format = 'Y-m-d')
    {
        $s = trim((string)$value);
        if ($s === '') {
            return null;
        }
        $dt = \DateTime::createFromFormat($format, $s);
        if (!$dt) {
            return null;
        }
        $errors = \DateTime::getLastErrors();
        if ($errors && ($errors['warning_count'] > 0 || $errors['error_count'] > 0)) {
            return null;
        }
        return $dt->format($format);
    }

    /**
     * Ensure the value is a member of allowed options; returns original when allowed, otherwise empty string.
     */
    public function validateOption($value, array $options)
    {
        $v = (string)$value;
        return in_array($v, $options, true) ? $v : '';
    }

    /**
     * Normalize a CSV string of values to comma-delimited list filtered by allowed keys.
     */
    public function normalizeCheckbox($csv, array $allowed)
    {
        $allowedSet = array_fill_keys($allowed, true);
        $parts = array_filter(array_map('trim', explode(',', (string)$csv)), 'strlen');
        $filtered = [];
        foreach ($parts as $p) {
            if (isset($allowedSet[$p])) {
                $filtered[] = $p;
            }
        }
        $filtered = array_values(array_unique($filtered));
        return implode(',', $filtered);
    }

    /**
     * Sanitize editor HTML via XOOPS text sanitizer when available; fallback allowlist or strip_tags.
     */
    public function sanitizeEditor($html)
    {
        $s = (string)$html;
        // XOOPS sanitizer
        if (class_exists('MyTextSanitizer')) {
            $ts = \MyTextSanitizer::getInstance();
            if (method_exists($ts, 'displayTarea')) {
                // Allow HTML but pass through XOOPS sanitizer
                return (string)$ts->displayTarea($s, true, true, true, true, true);
            }
        }
        // Basic fallback allowlist: b, i, em, strong, a, ul, ol, li, p, br
        $allowed = '<b><i><em><strong><a><ul><ol><li><p><br>'; 
        $s = strip_tags($s, $allowed);
        // Ensure rel="noopener" for links (best-effort)
        $s = preg_replace('/<a\s+/i', '<a rel="noopener" ', $s);
        return $s;
    }
}
