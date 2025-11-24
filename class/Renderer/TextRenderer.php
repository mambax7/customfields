<?php
namespace XoopsModules\Customfields\Renderer;

use XoopsModules\Customfields\FieldTypes;

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

class TextRenderer
{
    public function render($field, $value)
    {
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
