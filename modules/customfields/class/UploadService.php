<?php
namespace XoopsModules\Customfields;

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

/**
 * UploadService centralizes file/image upload validation and storage.
 * Defaults are conservative and remain compatible with PHP 7.4+.
 */
class UploadService
{
    /** @var string */
    private $uploadDir;

    public function __construct($uploadDir = null)
    {
        $dir = $uploadDir ?: Config::getUploadDir();
        $this->uploadDir = rtrim($dir, '/\\') . DIRECTORY_SEPARATOR;
    }

    /**
     * Handle an upload field by name and expected type (image|file).
     * Returns web-visible relative path on success (e.g. 'uploads/customfields/abc.jpg') or empty string on failure.
     */
    public function handle($fieldName, $fieldType)
    {
        if (!isset($_FILES[$fieldName]) || !is_array($_FILES[$fieldName])) {
            return '';
        }
        $file = $_FILES[$fieldName];
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            return '';
        }

        // Ensure upload directory exists
        if (!is_dir($this->uploadDir)) {
            if (!@mkdir($concurrentDirectory = $this->uploadDir, 0755, true) && !is_dir($concurrentDirectory)) {
                return '';
            }
        }

        // Enforce max size (via Config)
        $maxSize = Config::getMaxUploadSize();
        if (!empty($maxSize) && isset($file['size']) && (int)$file['size'] > $maxSize) {
            return '';
        }

        $originalName = isset($file['name']) ? basename((string)$file['name']) : '';
        $extension = strtolower((string)pathinfo($originalName, PATHINFO_EXTENSION));

        $allowed = Config::getAllowedExtensions($fieldType);
        if ($extension === '' || !in_array($extension, $allowed, true)) {
            return '';
        }

        // MIME verification (best-effort)
        $mime = $this->detectMime(isset($file['tmp_name']) ? $file['tmp_name'] : null);
        if ($mime !== null) {
            if (!$this->isMimeAllowed($fieldType, $mime)) {
                return '';
            }
        }

        // Unique file name
        $unique = uniqid('', true);
        if (function_exists('random_bytes')) {
            try {
                $unique .= bin2hex(random_bytes(8));
            } catch (\Throwable $e) {
                // ignore, uniqid still provides enough uniqueness for our use
            }
        }
        // Sanitize unique part to avoid dots and unsafe chars
        $unique = preg_replace('/[^a-zA-Z0-9]/', '', $unique);
        $filename = $unique . '_' . time() . '.' . $extension;
        $destination = $this->uploadDir . $filename;

        if ($this->move($file['tmp_name'], $destination)) {
            // Return relative path suitable for public URLs
            return 'uploads/customfields/' . $filename;
        }

        return '';
    }

    /**
     * Protected seam for moving uploaded files. Overridable in tests.
     */
    protected function move($tmp, $dest)
    {
        return @move_uploaded_file($tmp, $dest);
    }

    /**
     * Best-effort MIME detection. Returns string MIME or null if not available.
     * Overridable in tests to control behavior.
     */
    protected function detectMime($tmpPath)
    {
        if (!$tmpPath || !function_exists('finfo_open')) {
            return null;
        }
        $finfo = @finfo_open(FILEINFO_MIME_TYPE);
        if (!$finfo) {
            return null;
        }
        $mime = @finfo_file($finfo, $tmpPath);
        @finfo_close($finfo);
        return $mime ?: null;
    }

    /**
     * Check whether MIME is allowed for the given field type.
     */
    protected function isMimeAllowed($fieldType, $mime)
    {
        $allowedMimes = Config::getAllowedMimes($fieldType);
        return in_array($mime, $allowedMimes, true);
    }
}
