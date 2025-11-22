<?php

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
$dataHandler  = customfields_getDataHandler();

// Stats
$totalFields = $fieldHandler->getCount();
$totalData   = $dataHandler->getCount();

// Module stats
$moduleStatsMap = [];
$fields         = $fieldHandler->getAll();

foreach ($fields as $field) {
    $module = (string)$field->getVar('target_module');
    if ($module === '') {
        continue;
    }
    if (!isset($moduleStatsMap[$module])) {
        $moduleStatsMap[$module] = 0;
    }
    $moduleStatsMap[$module]++;
}

// Normalize for template
$moduleStats = [];
foreach ($moduleStatsMap as $module => $count) {
    $moduleStats[] = [
        'module' => $module,
        'count'  => $count,
    ];
}

// Use Smarty template
$GLOBALS['xoopsOption']['template_main'] = 'customfields_admin_index.tpl';

xoops_cp_header();

$GLOBALS['xoopsTpl']->assign('total_fields', (int)$totalFields);
$GLOBALS['xoopsTpl']->assign('total_data', (int)$totalData);
$GLOBALS['xoopsTpl']->assign('total_modules', count($moduleStats));
$GLOBALS['xoopsTpl']->assign('module_stats', $moduleStats);
$GLOBALS['xoopsTpl']->assign('has_module_stats', !empty($moduleStats));

xoops_cp_footer();
