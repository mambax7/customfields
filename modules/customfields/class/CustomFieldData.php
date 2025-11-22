<?php

namespace XoopsModules\Customfields;

/**
 * CustomFieldData sınıfı - Özel alan verileri için
 */



if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

class CustomFieldData extends \XoopsObject
{
    public function __construct()
    {
        $this->initVar('data_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('field_id', XOBJ_DTYPE_INT, null, true);
        $this->initVar('target_module', XOBJ_DTYPE_TXTBOX, null, true, 50);
        $this->initVar('item_id', XOBJ_DTYPE_INT, null, true);
        $this->initVar('field_value', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('created', XOBJ_DTYPE_INT, time(), false);
        $this->initVar('modified', XOBJ_DTYPE_INT, time(), false);
    }
}

