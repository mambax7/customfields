<?php
/**
 * İlave alanları diğer modüllere entegre etmek için yardımcı fonksiyonlar
 */

use  XoopsModules\Customfields\{
    CustomField,
    CustomFieldHandler,
    CustomFieldData,
    CustomFieldDataHandler,
    Helper
};




if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

/**
 * Handler sınıflarını yükle
 */
function customfields_loadHandlers()
{
    static $loaded = false;
    
    if (!$loaded) {
        require_once XOOPS_ROOT_PATH . '/modules/customfields/class/CustomField.php';
        require_once XOOPS_ROOT_PATH . '/modules/customfields/class/CustomFieldHandler.php';
        require_once XOOPS_ROOT_PATH . '/modules/customfields/class/CustomFieldData.php';
        $loaded = true;
    }
}

/**
 * CustomFieldHandler'ı al
 */
function customfields_getFieldHandler()
{
    global $xoopsDB;
    customfields_loadHandlers();
    return new CustomFieldHandler($xoopsDB);
}

/**
 * CustomFieldDataHandler'ı al
 */
function customfields_getDataHandler()
{
    global $xoopsDB;
    customfields_loadHandlers();
    return new CustomFieldDataHandler($xoopsDB);
}

function customfields_getFields($module_name, $show_in_form_only = false)
{
    $fieldHandler = customfields_getFieldHandler();
    return $fieldHandler->getFieldsByModule($module_name, $show_in_form_only);
}

function customfields_getData($module_name, $item_id)
{
    $dataHandler = customfields_getDataHandler();
    return $dataHandler->getItemData($module_name, $item_id);
}

function customfields_renderForm($module_name, $item_id = 0)
{
    $html = '';
    $fields = customfields_getFields($module_name, true);
    
    if (count($fields) == 0) {
        return $html;
    }
    
    $data = array();
    if ($item_id > 0) {
        $data = customfields_getData($module_name, $item_id);
    }
    
    $fieldHandler = customfields_getFieldHandler();
    
    $html .= '<div class="customfields-container">';
    $html .= '<fieldset><legend>İlave Bilgiler</legend>';
    
    foreach ($fields as $field) {
        $field_id = $field->getVar('field_id');
        $value = isset($data[$field_id]) ? $data[$field_id] : '';
        
        $html .= '<div class="form-group customfield-item">';
        $html .= '<label for="customfield_' . $field_id . '">';
        $html .= $field->getVar('field_title');
        if ($field->getVar('required')) {
            $html .= ' <span class="required">*</span>';
        }
        $html .= '</label>';
        
        if ($field->getVar('field_description')) {
            $html .= '<p class="help-block">' . $field->getVar('field_description') . '</p>';
        }
        
        $html .= $fieldHandler->renderField($field, $value);
        $html .= '</div>';
    }
    
    $html .= '</fieldset>';
    $html .= '</div>';
    
    return $html;
}

function customfields_saveData($module_name, $item_id)
{
    global $xoopsDB;
    
    // Debug log
    error_log("CustomFields saveData called: module=$module_name, item_id=$item_id");
    error_log("POST data: " . print_r($_POST, true));
    
    $fields = customfields_getFields($module_name);
    $dataHandler = customfields_getDataHandler();
    
    error_log("Found " . count($fields) . " fields for module $module_name");
    
    foreach ($fields as $field) {
        $field_id = $field->getVar('field_id');
        $field_name = 'customfield_' . $field_id;
        
        error_log("Processing field: $field_name (ID: $field_id)");
        
        if (isset($_POST[$field_name])) {
            $value = $_POST[$field_name];
            
            error_log("Field $field_name has value: $value");
            
            if (in_array($field->getVar('field_type'), array('image', 'file')) && isset($_FILES[$field_name])) {
                $value = customfields_handleFileUpload($field_name, $field->getVar('field_type'));
                error_log("File upload result: $value");
            }
            
            $result = $dataHandler->saveItemData($module_name, $item_id, $field_id, $value);
            error_log("Save result for field $field_id: " . ($result ? 'SUCCESS' : 'FAILED'));
        } else {
            error_log("Field $field_name NOT in POST data");
        }
    }
    
    return true;
}

function customfields_handleFileUpload($field_name, $field_type)
{
    if (!isset($_FILES[$field_name]) || $_FILES[$field_name]['error'] != UPLOAD_ERR_OK) {
        return '';
    }
    
    $upload_dir = XOOPS_ROOT_PATH . '/uploads/customfields/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $file = $_FILES[$field_name];
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if ($field_type == 'image') {
        $allowed = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        if (!in_array($extension, $allowed)) {
            return '';
        }
    } else {
        $allowed = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip', 'rar');
        if (!in_array($extension, $allowed)) {
            return '';
        }
    }
    
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $destination = $upload_dir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return 'uploads/customfields/' . $filename;
    }
    
    return '';
}

function customfields_deleteData($module_name, $item_id)
{
    global $xoopsDB;
    
    $sql = "DELETE FROM " . $xoopsDB->prefix('customfields_data') . " 
            WHERE target_module = " . $xoopsDB->quoteString($module_name) . " 
            AND item_id = " . intval($item_id);
    
    return $xoopsDB->queryF($sql);
}

function customfields_prepareForTemplate($module_name, $item_id)
{
    $fields = customfields_getFields($module_name);
    $data = customfields_getData($module_name, $item_id);
    
    $result = array();
    
    foreach ($fields as $field) {
        $field_id = $field->getVar('field_id');
        $value = isset($data[$field_id]) ? $data[$field_id] : '';
        
        $field_data = array(
            'id' => $field_id,
            'name' => $field->getVar('field_name'),
            'title' => $field->getVar('field_title'),
            'type' => $field->getVar('field_type'),
            'value' => $value,
            'formatted_value' => customfields_formatValue($field, $value)
        );
        
        $result[$field->getVar('field_name')] = $field_data;
    }
    
    return $result;
}

function customfields_formatValue($field, $value)
{
    if (empty($value)) {
        return '';
    }
    
    $type = $field->getVar('field_type');
    
    switch ($type) {
        case 'image':
            return '<img src="' . XOOPS_URL . '/' . $value . '" alt="' . $field->getVar('field_title') . '" class="customfield-image">';
            
        case 'file':
            return '<a href="' . XOOPS_URL . '/' . $value . '" target="_blank">' . basename($value) . '</a>';
            
        case 'date':
            return date('d.m.Y', strtotime($value));
            
        case 'checkbox':
            $values = explode(',', $value);
            $options = $field->getOptions();
            $labels = array();
            foreach ($values as $val) {
                if (isset($options[$val])) {
                    $labels[] = $options[$val];
                }
            }
            return implode(', ', $labels);
            
        case 'select':
        case 'radio':
            $options = $field->getOptions();
            return isset($options[$value]) ? $options[$value] : $value;
            
        case 'textarea':
            return nl2br(htmlspecialchars($value));
            
        case 'editor':
            return $value;
            
        default:
            return htmlspecialchars($value);
    }
}

/**
 * Bir item için tüm customfields verilerini al
 * 
 * @param string $module_name Modül adı (örn: 'publisher')
 * @param int $item_id Item ID
 * @return array Alan adı => değer dizisi
 */
function customfields_getItemData($module_name, $item_id)
{
    if (empty($module_name) || empty($item_id)) {
        return array();
    }
    
    $dataHandler = customfields_getDataHandler();
    return $dataHandler->getItemDataArray($module_name, $item_id);
}
