<?php
/**
 * CustomFieldData sınıfı - Özel alan verileri için
 */

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

class CustomFieldData extends XoopsObject
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

class CustomFieldDataHandler extends XoopsPersistableObjectHandler
{
    public function __construct($db)
    {
        parent::__construct($db, 'customfields_data', 'CustomFieldData', 'data_id', 'field_value');
    }

    public function getItemData($module_name, $item_id)
    {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('target_module', $module_name));
        $criteria->add(new Criteria('item_id', $item_id));
        
        $data_objects = $this->getObjects($criteria);
        $result = array();
        
        foreach ($data_objects as $data) {
            $result[$data->getVar('field_id')] = $data->getVar('field_value');
        }
        
        return $result;
    }

    /**
     * Item verilerini alan adlarıyla array olarak döndür
     * Template'de kullanım için: $item['customfields']['alan_adi']
     */
    public function getItemDataArray($module_name, $item_id)
    {
        global $xoopsDB;
        
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('target_module', $module_name));
        $criteria->add(new Criteria('item_id', $item_id));
        
        $data_objects = $this->getObjects($criteria);
        $result = array();
        
        // Field bilgilerini almak için handler'ı yükle
        require_once XOOPS_ROOT_PATH . '/modules/customfields/class/CustomFieldHandler.php';
        $fieldHandler = new CustomFieldHandler($xoopsDB);
        
        foreach ($data_objects as $data) {
            $field_id = $data->getVar('field_id');
            $field_value = $data->getVar('field_value');
            
            // Field bilgisini al
            $field = $fieldHandler->get($field_id);
            if ($field) {
                $field_name = $field->getVar('field_name');
                $result[$field_name] = $field_value;
            }
        }
        
        return $result;
    }

    public function saveItemData($module_name, $item_id, $field_id, $value)
    {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('target_module', $module_name));
        $criteria->add(new Criteria('item_id', $item_id));
        $criteria->add(new Criteria('field_id', $field_id));
        
        $existing = $this->getObjects($criteria);
        
        if (count($existing) > 0) {
            $data = $existing[0];
        } else {
            $data = $this->create();
            $data->setVar('target_module', $module_name);
            $data->setVar('item_id', $item_id);
            $data->setVar('field_id', $field_id);
            $data->setVar('created', time());
        }
        
        if (is_array($value)) {
            $value = implode(',', $value);
        }
        
        $data->setVar('field_value', $value);
        $data->setVar('modified', time());
        
        return $this->insert($data);
    }
}
