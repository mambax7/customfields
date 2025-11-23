<?php

use Xmf\Request;
use XoopsModules\Customfields\{
    CustomField,
    CustomFieldHandler,
    CustomFieldData,
    CustomFieldDataHandler,
    Helper
};

require_once __DIR__ . '/admin_header.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

$helper       = Helper::getInstance();
/** @var CustomFieldHandler $fieldHandler */
$fieldHandler = $helper->getHandler('CustomField');

$op      = Request::getCmd('op', 'form', 'REQUEST');
$fieldId = Request::getInt('field_id', 0, 'REQUEST');

switch ($op) {
    case 'save':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('manage.php', 3, _AM_CUSTOMFIELDS_TOKEN_ERROR);
        }

        if ($fieldId > 0) {
            /** @var CustomField|null $field */
            $field = $fieldHandler->get($fieldId);
            if (!$field) {
                redirect_header('manage.php', 3, _AM_CUSTOMFIELDS_FIELD_NOT_FOUND);
            }
        } else {
            $field = $fieldHandler->create();
            $field->setVar('created', time());
        }

        $targetModule     = Request::getString('target_module', '', 'POST');
        $fieldName        = Request::getString('field_name', '', 'POST');
        $fieldTitle       = Request::getString('field_title', '', 'POST');
        $fieldDescription = Request::getString('field_description', '', 'POST');
        $fieldType        = Request::getString('field_type', '', 'POST');
        $fieldOrder       = Request::getInt('field_order', 0, 'POST');
        $required         = Request::getBool('required', false, 'POST') ? 1 : 0;
        $showInForm       = Request::getBool('show_in_form', false, 'POST') ? 1 : 0;

        $field->setVar('target_module', $targetModule);
        $field->setVar('field_name', $fieldName);
        $field->setVar('field_title', $fieldTitle);
        $field->setVar('field_description', $fieldDescription);
        $field->setVar('field_type', $fieldType);
        $field->setVar('field_order', $fieldOrder);
        $field->setVar('required', $required);
        $field->setVar('show_in_form', $showInForm);
        $field->setVar('modified', time());

        // Options for select / checkbox / radio
        $options = [];
        if (in_array($fieldType, ['select', 'checkbox', 'radio'], true)) {
            $optionValues = Request::getArray('option_values', [], 'POST');
            $optionLabels = Request::getArray('option_labels', [], 'POST');

            foreach ($optionValues as $idx => $value) {
                $value = trim((string)$value);
                $label = isset($optionLabels[$idx]) ? trim((string)$optionLabels[$idx]) : '';
                if ($value !== '' && $label !== '') {
                    $options[$value] = $label;
                }
            }
        }
        $field->setOptions($options);

        if ($fieldHandler->insert($field)) {
            redirect_header('manage.php', 2, _AM_CUSTOMFIELDS_FIELD_SAVED);
        } else {
            redirect_header('manage.php', 3, _AM_CUSTOMFIELDS_SAVE_ERROR);
        }
        break;

    case 'form':
    default:
        $GLOBALS['xoopsOption']['template_main'] = 'customfields_admin_add.tpl';
        xoops_cp_header();

        $isNew = true;
        if ($fieldId > 0) {
            /** @var CustomField|null $field */
            $field = $fieldHandler->get($fieldId);
            if (!$field) {
                redirect_header('manage.php', 3, _AM_CUSTOMFIELDS_FIELD_NOT_FOUND);
            }
            $isNew = false;
        } else {
            $field = $fieldHandler->create();
        }

        // Active modules for target_module select
        $moduleHandler = xoops_getHandler('module');
        $criteria      = new \Criteria('isactive', 1);
        $modules       = $moduleHandler->getObjects($criteria);
        $modulesList   = [];

        foreach ($modules as $module) {
            $modulesList[] = [
                'dirname' => $module->getVar('dirname'),
                'name'    => $module->getVar('name'),
            ];
        }

        // Existing options
        $optionsAssoc = $field->getOptions();
        $optionsList  = [];
        if (!empty($optionsAssoc) && is_array($optionsAssoc)) {
            foreach ($optionsAssoc as $value => $label) {
                $optionsList[] = [
                    'value' => $value,
                    'label' => $label,
                ];
            }
        }

        // Field base data for template
        $fieldData = [
            'target_module'   => $field->getVar('target_module', 'e'),
            'field_name'      => $field->getVar('field_name', 'e'),
            'field_title'     => $field->getVar('field_title', 'e'),
            'field_description' => $field->getVar('field_description', 'e'),
            'field_type'      => $field->getVar('field_type', 'e'),
            'field_order'     => (int)$field->getVar('field_order'),
            'required'        => (int)$field->getVar('required'),
            'show_in_form'    => (int)$field->getVar('show_in_form'),
        ];

        // Field types with localized labels
        $fieldTypes = [
            [
                'value' => 'text',
                'label' => _AM_CUSTOMFIELDS_FIELD_TYPE_TEXT,
            ],
            [
                'value' => 'textarea',
                'label' => _AM_CUSTOMFIELDS_FIELD_TYPE_TEXTAREA,
            ],
            [
                'value' => 'editor',
                'label' => _AM_CUSTOMFIELDS_FIELD_TYPE_EDITOR,
            ],
            [
                'value' => 'image',
                'label' => _AM_CUSTOMFIELDS_FIELD_TYPE_IMAGE,
            ],
            [
                'value' => 'file',
                'label' => _AM_CUSTOMFIELDS_FIELD_TYPE_FILE,
            ],
            [
                'value' => 'select',
                'label' => _AM_CUSTOMFIELDS_FIELD_TYPE_SELECT,
            ],
            [
                'value' => 'checkbox',
                'label' => _AM_CUSTOMFIELDS_FIELD_TYPE_CHECKBOX,
            ],
            [
                'value' => 'radio',
                'label' => _AM_CUSTOMFIELDS_FIELD_TYPE_RADIO,
            ],
            [
                'value' => 'date',
                'label' => _AM_CUSTOMFIELDS_FIELD_TYPE_DATE,
            ],
        ];

        $GLOBALS['xoopsTpl']->assign('is_new', $isNew);
        $GLOBALS['xoopsTpl']->assign('field_id', $fieldId);
        $GLOBALS['xoopsTpl']->assign('field', $fieldData);
        $GLOBALS['xoopsTpl']->assign('xmodules', $modulesList);
        $GLOBALS['xoopsTpl']->assign('options', $optionsList);
        $GLOBALS['xoopsTpl']->assign('field_types', $fieldTypes);
        $GLOBALS['xoopsTpl']->assign('token_html', $GLOBALS['xoopsSecurity']->getTokenHTML());

        break;
}

require_once __DIR__ . '/admin_footer.php';
