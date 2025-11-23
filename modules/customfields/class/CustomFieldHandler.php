<?php
namespace XoopsModules\Customfields;

/**
 * CustomFieldHandler sınıfı
 */

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

class CustomFieldHandler extends \XoopsPersistableObjectHandler
{
    public function __construct($db)
    {
        parent::__construct($db, 'customfields_definitions', CustomField::class, 'field_id', 'field_name');
    }

    public function getFieldsByModule($module_name, $show_in_form_only = false)
    {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('target_module', $module_name));
        if ($show_in_form_only) {
            $criteria->add(new Criteria('show_in_form', 1));
        }
        $criteria->setSort('field_order');
        $criteria->setOrder('ASC');
        
        return $this->getObjects($criteria);
    }

    public function renderField($field, $value = '')
    {
        $html = '';
        $field_name = 'customfield_' . $field->getVar('field_id');
        $required = $field->getVar('required') ? 'required' : '';
        
        switch ($field->getVar('field_type')) {
            case 'text':
                $html = '<input type="text" name="' . $field_name . '" value="' . htmlspecialchars($value) . '" class="form-control" ' . $required . '>';
                break;
                
            case 'textarea':
                $html = '<textarea name="' . $field_name . '" class="form-control" rows="5" ' . $required . '>' . htmlspecialchars($value) . '</textarea>';
                break;
                
            case 'editor':
                xoops_load('XoopsFormDhtmlTextArea');
                $editor = new XoopsFormDhtmlTextArea('', $field_name, $value, 10, 50);
                $html = $editor->render();
                break;
                
            case 'image':
            case 'file':
                $html = '<input type="file" name="' . $field_name . '" class="form-control" ' . $required . '>';
                if (!empty($value)) {
                    $html .= '<div class="current-file">Mevcut: ' . basename($value) . '</div>';
                }
                break;
                
            case 'select':
                $options = $field->getOptions();
                $html = '<select name="' . $field_name . '" class="form-control" ' . $required . '>';
                $html .= '<option value="">Seçiniz...</option>';
                foreach ($options as $opt_value => $opt_label) {
                    $selected = ($value == $opt_value) ? 'selected' : '';
                    $html .= '<option value="' . htmlspecialchars($opt_value) . '" ' . $selected . '>' . htmlspecialchars($opt_label) . '</option>';
                }
                $html .= '</select>';
                break;
                
            case 'checkbox':
                $options = $field->getOptions();
                $values = is_array($value) ? $value : explode(',', $value);
                foreach ($options as $opt_value => $opt_label) {
                    $checked = in_array($opt_value, $values) ? 'checked' : '';
                    $html .= '<label class="checkbox-inline">';
                    $html .= '<input type="checkbox" name="' . $field_name . '[]" value="' . htmlspecialchars($opt_value) . '" ' . $checked . '> ';
                    $html .= htmlspecialchars($opt_label);
                    $html .= '</label> ';
                }
                break;
                
            case 'radio':
                $options = $field->getOptions();
                foreach ($options as $opt_value => $opt_label) {
                    $checked = ($value == $opt_value) ? 'checked' : '';
                    $html .= '<label class="radio-inline">';
                    $html .= '<input type="radio" name="' . $field_name . '" value="' . htmlspecialchars($opt_value) . '" ' . $checked . '> ';
                    $html .= htmlspecialchars($opt_label);
                    $html .= '</label> ';
                }
                break;
                
            case 'date':
                $html = '<input type="date" name="' . $field_name . '" value="' . htmlspecialchars($value) . '" class="form-control" ' . $required . '>';
                break;
        }
        
        return $html;
    }
}
