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
    /**
     * In-memory cache for fields by module for the current request.
     * @var array<string,array>
     */
    private static $fieldsCache = [];

    public function __construct($db)
    {
        parent::__construct($db, 'customfields_definitions', CustomField::class, 'field_id', 'field_name');
    }

    public function getFieldsByModule($module_name, $show_in_form_only = false)
    {
        $key = (string)$module_name . '|' . ((int)$show_in_form_only);
        if (isset(self::$fieldsCache[$key])) {
            return self::$fieldsCache[$key];
        }

        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('target_module', $module_name));
        if ($show_in_form_only) {
            $criteria->add(new \Criteria('show_in_form', 1));
        }
        $criteria->setSort('field_order');
        $criteria->setOrder('ASC');

        $objects = $this->getObjects($criteria);
        self::$fieldsCache[$key] = $objects;
        return $objects;
    }

    public function renderField($field, $value = '')
    {
        $html = '';
        $field_name = 'customfield_' . $field->getVar('field_id');
        $required = $field->getVar('required') ? 'required' : '';
        
        switch ($field->getVar('field_type')) {
            case 'text':
                $html = '<input type="text" name="' . $field_name . '" value="' . htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '" class="form-control" ' . $required . '>';
                break;
                
            case 'textarea':
                $html = '<textarea name="' . $field_name . '" class="form-control" rows="5" ' . $required . '>' . htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</textarea>';
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
                    $html .= '<div class="current-file">Mevcut: ' . htmlspecialchars(basename((string)$value), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</div>';
                }
                break;
                
            case 'select':
                $options = $field->getOptions();
                $html = '<select name="' . $field_name . '" class="form-control" ' . $required . '>';
                $html .= '<option value="">Seçiniz...</option>';
                foreach ($options as $opt_value => $opt_label) {
                    $selected = ($value == $opt_value) ? 'selected' : '';
                    $html .= '<option value="' . htmlspecialchars((string)$opt_value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '" ' . $selected . '>' . htmlspecialchars((string)$opt_label, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</option>';
                }
                $html .= '</select>';
                break;
                
            case 'checkbox':
                $options = $field->getOptions();
                $values = is_array($value) ? $value : explode(',', $value);
                foreach ($options as $opt_value => $opt_label) {
                    $checked = in_array($opt_value, $values) ? 'checked' : '';
                    $html .= '<label class="checkbox-inline">';
                    $html .= '<input type="checkbox" name="' . $field_name . '[]" value="' . htmlspecialchars((string)$opt_value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '" ' . $checked . '> ';
                    $html .= htmlspecialchars((string)$opt_label, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    $html .= '</label> ';
                }
                break;
                
            case 'radio':
                $options = $field->getOptions();
                foreach ($options as $opt_value => $opt_label) {
                    $checked = ($value == $opt_value) ? 'checked' : '';
                    $html .= '<label class="radio-inline">';
                    $html .= '<input type="radio" name="' . $field_name . '" value="' . htmlspecialchars((string)$opt_value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '" ' . $checked . '> ';
                    $html .= htmlspecialchars((string)$opt_label, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    $html .= '</label> ';
                }
                break;
                
            case 'date':
                $html = '<input type="date" name="' . $field_name . '" value="' . htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '" class="form-control" ' . $required . '>';
                break;
        }
        
        return $html;
    }

    /**
     * Override insert to clear cache upon changes.
     */
    public function insert($obj, $force = true)
    {
        $result = parent::insert($obj, $force);
        self::$fieldsCache = [];
        return $result;
    }

    /**
     * Override delete to clear cache.
     */
    public function delete($obj, $force = true)
    {
        $result = parent::delete($obj, $force);
        self::$fieldsCache = [];
        return $result;
    }
}
