<?php
namespace XoopsModules\Customfields\Renderer;

use XoopsModules\Customfields\FieldTypes;

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

/**
 * Lightweight renderer factory. Initially supports text, image, and file.
 * For unsupported types, returns null to allow legacy fallback logic.
 */
class RendererFactory
{
    /**
     * @param object $field XoopsObject-like with getVar()
     * @param mixed  $value
     * @return string|null Rendered HTML string or null if not handled here
     */
    public static function render($field, $value)
    {
        $type = method_exists($field, 'getVar') ? $field->getVar('field_type') : null;
        switch ($type) {
            case FieldTypes::TEXT:
                $r = new TextRenderer();
                return $r->render($field, $value);
            case FieldTypes::TEXTAREA:
                $r = new TextareaRenderer();
                return $r->render($field, $value);
            case FieldTypes::SELECT:
                $r = new SelectRenderer();
                return $r->render($field, $value);
            case FieldTypes::RADIO:
                $r = new RadioRenderer();
                return $r->render($field, $value);
            case FieldTypes::CHECKBOX:
                $r = new CheckboxRenderer();
                return $r->render($field, $value);
            case FieldTypes::DATE:
                $r = new DateRenderer();
                return $r->render($field, $value);
            case FieldTypes::IMAGE:
                $r = new ImageRenderer();
                return $r->render($field, $value);
            case FieldTypes::FILE:
                $r = new FileRenderer();
                return $r->render($field, $value);
            default:
                return null;
        }
    }
}
