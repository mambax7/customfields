<?php
namespace XoopsModules\Customfields\Renderer;

use XoopsModules\Customfields\Config;

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

/**
 * Renders date field values using module display format configuration.
 */
class DateRenderer
{
    /**
     * @param object $field Field object with getVar()
     * @param mixed  $value Date value (string like 'Y-m-d' or timestamp parseable by strtotime)
     * @return string
     */
    public function render($field, $value)
    {
        if ($value === '' || $value === null) {
            return '';
        }
        $ts = @strtotime((string)$value);
        if ($ts === false) {
            return '';
        }
        $fmt = Config::getDisplayDateFormat();
        return date($fmt, $ts);
    }
}
