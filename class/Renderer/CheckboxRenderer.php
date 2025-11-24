<?php
namespace XoopsModules\Customfields\Renderer;

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

class CheckboxRenderer
{
    public function render($field, $value)
    {
        if ($value === '' || $value === null) {
            return '';
        }
        $values = explode(',', (string)$value);
        $options = method_exists($field, 'getOptions') ? (array)$field->getOptions() : [];
        $labels = [];
        foreach ($values as $val) {
            if (isset($options[$val])) {
                $labels[] = \customfields_esc($options[$val]);
            }
        }
        return implode(', ', $labels);
    }
}
