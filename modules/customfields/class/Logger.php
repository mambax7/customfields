<?php
namespace XoopsModules\Customfields;

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

/**
 * Minimal logging adapter for the CustomFields module.
 * Uses PSR-3 logger when injected; falls back to error_log().
 */
class Logger
{
    /** @var \Psr\Log\LoggerInterface|null */
    private static $psrLogger = null;

    /**
     * Optionally inject a PSR-3 logger.
     * @param mixed $logger
     */
    public static function setLogger($logger)
    {
        if (interface_exists('Psr\\Log\\LoggerInterface') && $logger instanceof \Psr\Log\LoggerInterface) {
            self::$psrLogger = $logger;
        }
    }

    public static function info($message)
    {
        self::log('info', $message);
    }

    public static function warning($message)
    {
        self::log('warning', $message);
    }

    public static function error($message)
    {
        self::log('error', $message);
    }

    private static function log($level, $message)
    {
        $msg = is_scalar($message) ? (string)$message : var_export($message, true);
        if (self::$psrLogger) {
            // Call appropriate method if available; otherwise use generic log
            $method = strtolower((string)$level);
            if (method_exists(self::$psrLogger, $method)) {
                self::$psrLogger->$method($msg);
                return;
            }
            self::$psrLogger->log($level, $msg);
            return;
        }
        // Fallback: error_log with level tag
        @error_log('[CustomFields][' . strtoupper((string)$level) . '] ' . $msg);
    }
}
