<?php
namespace XoopsModules\Customfields\Renderer;

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

class SelectRenderer
{
    public function render($field, $value)
    {
        $options = method_exists($field, 'getOptions') ? (array)$field->getOptions() : [];
        $val = (string)$value;
        if (isset($options[$val])) {
            return \customfields_esc($options[$val]);
        }
        return \customfields_esc($val);
    }
}
