<?php
/**
 * CustomField sınıfı - Özel alan tanımları için
 */

namespace XoopsModules\Customfields;

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

class CustomField extends \XoopsObject
{
    public function __construct()
    {
        $this->initVar('field_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('target_module', XOBJ_DTYPE_TXTBOX, null, true, 50);
        $this->initVar('field_name', XOBJ_DTYPE_TXTBOX, null, true, 100);
        $this->initVar('field_title', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('field_description', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('field_type', XOBJ_DTYPE_TXTBOX, 'text', true, 20);
        $this->initVar('field_options', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('field_order', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('required', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('show_in_form', XOBJ_DTYPE_INT, 1, false);
        $this->initVar('validation_rules', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('created', XOBJ_DTYPE_INT, time(), false);
        $this->initVar('modified', XOBJ_DTYPE_INT, time(), false);
    }

    public function getOptions()
    {
        $options = $this->getVar('field_options');
        if (empty($options)) {
            return array();
        }
        return json_decode($options, true);
    }

    public function setOptions($options)
    {
        if (is_array($options)) {
            $this->setVar('field_options', json_encode($options));
        }
    }
}
