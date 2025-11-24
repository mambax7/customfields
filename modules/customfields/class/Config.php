<?php
namespace XoopsModules\Customfields;

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

/**
 * Central configuration accessors with sensible defaults.
 * Reads existing define() constants to preserve backward compatibility.
 */
class Config
{
    /**
     * Get absolute upload directory on filesystem for CustomFields.
     * @return string Absolute path ending with DIRECTORY_SEPARATOR
     */
    public static function getUploadDir()
    {
        $dir = XOOPS_ROOT_PATH . '/uploads/customfields/';
        return rtrim($dir, "/\\") . DIRECTORY_SEPARATOR;
    }

    /**
     * Max upload size in bytes. Default 5 MB.
     * @return int
     */
    public static function getMaxUploadSize()
    {
        return defined('CUSTOMFIELDS_MAX_UPLOAD_SIZE')
            ? (int)constant('CUSTOMFIELDS_MAX_UPLOAD_SIZE')
            : (5 * 1024 * 1024);
    }

    /**
     * @param string $type 'image' or 'file'
     * @return array
     */
    /**
     * Allowed file extensions (lowercased) for given field type.
     * @param string $type 'image' or 'file'
     * @return array
     */
    public static function getAllowedExtensions($type)
    {
        $type = (string)$type;
        if ($type === 'image') {
            // Allow override via constant array if defined
            if (defined('CUSTOMFIELDS_ALLOWED_IMAGE_EXT')) {
                $val = constant('CUSTOMFIELDS_ALLOWED_IMAGE_EXT');
                if (is_array($val)) {
                    return array_values(array_unique(array_map('strtolower', $val)));
                }
            }
            return array('jpg', 'jpeg', 'png', 'gif', 'webp');
        }
        if (defined('CUSTOMFIELDS_ALLOWED_FILE_EXT')) {
            $val = constant('CUSTOMFIELDS_ALLOWED_FILE_EXT');
            if (is_array($val)) {
                return array_values(array_unique(array_map('strtolower', $val)));
            }
        }
        return array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip', 'rar', '7z');
    }

    /**
     * @param string $type 'image' or 'file'
     * @return array
     */
    /**
     * Allowed MIME types for given field type.
     * @param string $type 'image' or 'file'
     * @return array
     */
    public static function getAllowedMimes($type)
    {
        $type = (string)$type;
        if ($type === 'image') {
            if (defined('CUSTOMFIELDS_ALLOWED_IMAGE_MIME')) {
                $val = constant('CUSTOMFIELDS_ALLOWED_IMAGE_MIME');
                if (is_array($val)) {
                    return $val;
                }
            }
            return array('image/jpeg', 'image/png', 'image/gif', 'image/webp');
        }
        if (defined('CUSTOMFIELDS_ALLOWED_FILE_MIME')) {
            $val = constant('CUSTOMFIELDS_ALLOWED_FILE_MIME');
            if (is_array($val)) {
                return $val;
            }
        }
        return array(
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/zip',
            'application/x-rar-compressed',
            'application/x-7z-compressed',
        );
    }

    /**
     * Date display format used when rendering date values.
     * Defaults to 'd.m.Y' for BC.
     * @return string
     */
    public static function getDisplayDateFormat()
    {
        return defined('CUSTOMFIELDS_DISPLAY_DATE_FORMAT')
            ? (string)constant('CUSTOMFIELDS_DISPLAY_DATE_FORMAT')
            : 'd.m.Y';
    }
}
