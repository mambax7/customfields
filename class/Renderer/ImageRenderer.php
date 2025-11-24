<?php
namespace XoopsModules\Customfields\Renderer;

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

class ImageRenderer
{
    public function render($field, $value)
    {
        if ($value === '' || $value === null) {
            return '';
        }
        $src = \customfields_url($value);
        $alt = \customfields_esc($field->getVar('field_title'));
        return '<img src="' . \customfields_esc($src) . '" alt="' . $alt . '" title="' . $alt . '" class="customfield-image" loading="lazy">';
    }
}
