<?php
namespace XoopsModules\Customfields;

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

/**
 * FieldTypes constants to avoid magic strings in code.
 * Keep simple constants for PHP 7.4 compatibility.
 */
class FieldTypes
{
    public const TEXT = 'text';
    public const TEXTAREA = 'textarea';
    public const EDITOR = 'editor';
    public const IMAGE = 'image';
    public const FILE = 'file';
    public const SELECT = 'select';
    public const CHECKBOX = 'checkbox';
    public const RADIO = 'radio';
    public const DATE = 'date';
}
