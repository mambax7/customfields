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
include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
include_once XOOPS_ROOT_PATH . '/modules/customfields/include/functions.php';
include_once XOOPS_ROOT_PATH . '/class/pagenav.php';

$helper = Helper::getInstance();

//$fieldHandler = customfields_getFieldHandler();

$fieldHandler  = $helper->getHandler('CustomField');

// op and id
$op       = Request::getCmd('op', 'list', 'REQUEST');
$field_id = Request::getInt('field_id', 0, 'REQUEST');

switch ($op) {
    case 'save':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('fields.php', 3, _AM_CUSTOMFIELDS_TOKEN_ERROR);
        }

        if ($field_id > 0) {
            $field = $fieldHandler->get($field_id);
            if (!($field instanceof CustomField)) {
                redirect_header('fields.php', 3, _AM_CUSTOMFIELDS_SAVE_ERROR);
            }
        } else {
            $field = $fieldHandler->create();
            $field->setVar('created', time());
        }

        $targetModule      = Request::getString('target_module', '', 'POST');
        $fieldName         = Request::getString('field_name', '', 'POST');
        $fieldTitle        = Request::getString('field_title', '', 'POST');
        $fieldDescription  = Request::getText('field_description', '', 'POST');
        $fieldType         = Request::getString('field_type', '', 'POST');
        $fieldOrder        = Request::getInt('field_order', 0, 'POST');
        $required          = Request::getInt('required', 0, 'POST') ? 1 : 0;
        $showInForm        = Request::getInt('show_in_form', 0, 'POST') ? 1 : 0;

        $field->setVar('target_module', $targetModule);
        $field->setVar('field_name', $fieldName);
        $field->setVar('field_title', $fieldTitle);
        $field->setVar('field_description', $fieldDescription);
        $field->setVar('field_type', $fieldType);
        $field->setVar('field_order', $fieldOrder);
        $field->setVar('required', $required);
        $field->setVar('show_in_form', $showInForm);
        $field->setVar('modified', time());

        if (in_array($fieldType, ['select', 'checkbox', 'radio'], true)) {
            $options       = [];
            $optionValues  = Request::getArray('option_values', [], 'POST');
            $optionLabels  = Request::getArray('option_labels', [], 'POST');

            foreach ($optionValues as $idx => $val) {
                $val   = trim($val);
                $label = isset($optionLabels[$idx]) ? trim($optionLabels[$idx]) : '';
                if ($val !== '' && $label !== '') {
                    $options[$val] = $label;
                }
            }
            $field->setOptions($options);
        } else {
            // reset options for non-option types
            $field->setOptions([]);
        }

        if ($fieldHandler->insert($field)) {
            redirect_header('fields.php', 2, _AM_CUSTOMFIELDS_FIELD_SAVED_SUCCESS);
        } else {
            redirect_header('fields.php', 3, _AM_CUSTOMFIELDS_SAVE_ERROR);
        }
        break;

    case 'delete':
        // POST-only delete
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('fields.php', 3, _AM_CUSTOMFIELDS_TOKEN_ERROR);
        }

        $fieldIdPost = Request::getInt('field_id', 0, 'POST');
        if ($fieldIdPost <= 0) {
            redirect_header('fields.php', 3, _AM_CUSTOMFIELDS_DELETE_ERROR);
        }

        $field = $fieldHandler->get($fieldIdPost);
        if ($field && $fieldHandler->delete($field)) {
            redirect_header('fields.php', 2, _AM_CUSTOMFIELDS_FIELD_DELETED);
        } else {
            redirect_header('fields.php', 3, _AM_CUSTOMFIELDS_DELETE_ERROR);
        }
        break;

    case 'add':
    case 'edit':
        // field form (add / edit)
        $GLOBALS['xoopsOption']['template_main'] = 'customfields_admin_field_form.tpl';
        xoops_cp_header();


        if ($op === 'edit' && $field_id > 0) {
            $field = $fieldHandler->get($field_id);
            if (!($field instanceof CustomField)) {
                redirect_header('fields.php', 3, _AM_CUSTOMFIELDS_SAVE_ERROR);
            }
            $isNew = false;
        } else {
            /** @var CustomField $field */
            $field = $fieldHandler->create();
            $isNew = true;
        }

        $options = $field->getOptions();
        $optionsForTpl = [];
        if (!empty($options) && is_array($options)) {
            foreach ($options as $value => $label) {
                $optionsForTpl[] = [
                    'value' => $value,
                    'label' => $label,
                ];
            }
        }

        // Ensure at least one option row for option-based types
        if (empty($optionsForTpl)) {
            $optionsForTpl[] = ['value' => '', 'label' => ''];
        }

        $fieldTypes = [
            'text'     => _AM_CUSTOMFIELDS_FIELD_TYPE_TEXT,
            'textarea' => _AM_CUSTOMFIELDS_FIELD_TYPE_TEXTAREA,
            'select'   => _AM_CUSTOMFIELDS_FIELD_TYPE_SELECT,
            'checkbox' => _AM_CUSTOMFIELDS_FIELD_TYPE_CHECKBOX,
            'radio'    => _AM_CUSTOMFIELDS_FIELD_TYPE_RADIO,
        ];

        $fieldData = [
            'field_id'         => $field->getVar('field_id'),
            'target_module'    => $field->getVar('target_module', 'e'),
            'field_name'       => $field->getVar('field_name', 'e'),
            'field_title'      => $field->getVar('field_title', 'e'),
            'field_description'=> $field->getVar('field_description', 'e'),
            'field_type'       => $field->getVar('field_type', 'e'),
            'field_order'      => (int) $field->getVar('field_order'),
            'required'         => (int) $field->getVar('required'),
            'show_in_form'     => (int) $field->getVar('show_in_form'),
            'options'          => $optionsForTpl,
        ];

        $GLOBALS['xoopsTpl']->assign('field', $fieldData);
        $GLOBALS['xoopsTpl']->assign('field_types', $fieldTypes);
        $GLOBALS['xoopsTpl']->assign('is_new', $isNew);
        $GLOBALS['xoopsTpl']->assign('token_html', $GLOBALS['xoopsSecurity']->getTokenHTML());

        xoops_cp_footer();
        break;

    case 'list':
    default:
        $GLOBALS['xoopsOption']['template_main'] = 'customfields_admin_fields.tpl';
        xoops_cp_header();

        // Filters & sorting
        $start        = Request::getInt('start', 0, 'GET');
        $limit        = Request::getInt('limit', 20, 'GET');
        $sort         = Request::getString('sort', 'field_id', 'GET');
        $order        = strtoupper(Request::getString('order', 'ASC', 'GET')) === 'DESC' ? 'DESC' : 'ASC';
        $filterModule = Request::getString('filter_module', '', 'GET');
        $filterType   = Request::getString('filter_type', '', 'GET');

        $allowedSorts = ['field_id', 'target_module', 'field_name', 'field_title', 'field_type'];
        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'field_id';
        }

        $criteria = new \CriteriaCompo();
        if ($filterModule !== '') {
            $criteria->add(new \Criteria('target_module', $filterModule));
        }
        if ($filterType !== '') {
            $criteria->add(new \Criteria('field_type', $filterType));
        }

        $count = $fieldHandler->getCount($criteria);

        $criteria->setStart($start);
        $criteria->setLimit($limit);
        $criteria->setSort($sort);
        $criteria->setOrder($order);

        $fields     = $fieldHandler->getObjects($criteria);
        $fieldsList = [];

        if (!empty($fields)) {
            foreach ($fields as $field) {
                $fieldsList[] = [
                    'field_id'      => $field->getVar('field_id'),
                    'target_module' => $field->getVar('target_module'),
                    'field_name'    => $field->getVar('field_name'),
                    'field_title'   => $field->getVar('field_title'),
                    'field_type'    => $field->getVar('field_type'),
                ];
            }
        }

        $nav = new \XoopsPageNav(
            $count,
            $limit,
            $start,
            'start',
            'limit=' . $limit
            . '&sort=' . urlencode($sort)
            . '&order=' . urlencode($order)
            . '&filter_module=' . urlencode($filterModule)
            . '&filter_type=' . urlencode($filterType)
        );

        $GLOBALS['xoopsTpl']->assign('fields', $fieldsList);
        $GLOBALS['xoopsTpl']->assign('has_fields', !empty($fieldsList));
        $GLOBALS['xoopsTpl']->assign('filter_module', $filterModule);
        $GLOBALS['xoopsTpl']->assign('filter_type', $filterType);
        $GLOBALS['xoopsTpl']->assign('sort', $sort);
        $GLOBALS['xoopsTpl']->assign('order', $order);
        $GLOBALS['xoopsTpl']->assign('pagenav', $nav->renderNav());
        $GLOBALS['xoopsTpl']->assign('token_html', $GLOBALS['xoopsSecurity']->getTokenHTML());

        xoops_cp_footer();
        break;
}
