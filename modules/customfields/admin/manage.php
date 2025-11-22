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
require_once XOOPS_ROOT_PATH . '/modules/customfields/include/functions.php';

$fieldHandler = customfields_getFieldHandler();

$op = Request::getCmd('op', 'list', 'REQUEST');

switch ($op) {
    case 'delete':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('manage.php', 3, _AM_CUSTOMFIELDS_TOKEN_ERROR);
        }

        $fieldId = Request::getInt('field_id', 0, 'POST');
        if ($fieldId <= 0) {
            redirect_header('manage.php', 3, _AM_CUSTOMFIELDS_DELETE_ERROR);
        }

        /** @var CustomField|null $field */
        $field = $fieldHandler->get($fieldId);
        if ($field && $fieldHandler->delete($field)) {
            redirect_header('manage.php', 2, _AM_CUSTOMFIELDS_FIELD_DELETED);
        } else {
            redirect_header('manage.php', 3, _AM_CUSTOMFIELDS_DELETE_ERROR);
        }
        break;

    default: // list
        $GLOBALS['xoopsOption']['template_main'] = 'customfields_admin_manage.tpl';

        xoops_cp_header();

        $moduleFilter = Request::getString('module', '', 'GET');

        // Build module list for filter
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

        // Fetch fields with optional module filter
        $fieldCriteria = new \CriteriaCompo();
        if ($moduleFilter !== '') {
            $fieldCriteria->add(new \Criteria('target_module', $moduleFilter));
        }
        $fieldCriteria->setSort('target_module, field_order');
        $fieldCriteria->setOrder('ASC');

        $fields     = $fieldHandler->getObjects($fieldCriteria);
        $fieldsList = [];

        if (!empty($fields)) {
            foreach ($fields as $field) {
                $fieldsList[] = [
                    'field_id'      => $field->getVar('field_id'),
                    'target_module' => $field->getVar('target_module'),
                    'field_name'    => $field->getVar('field_name'),
                    'field_title'   => $field->getVar('field_title'),
                    'field_type'    => $field->getVar('field_type'),
                    'field_order'   => (int)$field->getVar('field_order'),
                    'required'      => (int)$field->getVar('required'),
                    'show_in_form'  => (int)$field->getVar('show_in_form'),
                ];
            }
        }

        $GLOBALS['xoopsTpl']->assign('modules', $modulesList);
        $GLOBALS['xoopsTpl']->assign('module_filter', $moduleFilter);
        $GLOBALS['xoopsTpl']->assign('fields', $fieldsList);
        $GLOBALS['xoopsTpl']->assign('has_fields', !empty($fieldsList));
        $GLOBALS['xoopsTpl']->assign('total_fields', count($fieldsList));
        $GLOBALS['xoopsTpl']->assign('token_html', $GLOBALS['xoopsSecurity']->getTokenHTML());

        xoops_cp_footer();
        break;
}
