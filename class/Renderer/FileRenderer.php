<?php
namespace XoopsModules\Customfields\Renderer;

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

class FileRenderer
{
    public function render($field, $value)
    {
        if ($value === '' || $value === null) {
            return '';
        }
        $href = \customfields_url($value);
        $label = \customfields_esc(basename((string)$value));
        return '<a href="' . \customfields_esc($href) . '" target="_blank" rel="noopener">' . $label . '</a>';
    }
}
