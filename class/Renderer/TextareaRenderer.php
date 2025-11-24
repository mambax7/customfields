<?php
namespace XoopsModules\Customfields\Renderer;

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

class TextareaRenderer
{
    public function render($field, $value)
    {
        return nl2br(htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
    }
}
