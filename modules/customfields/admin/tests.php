<?php

require_once __DIR__ . '/admin_header.php';
require_once XOOPS_ROOT_PATH . '/modules/customfields/include/functions.php';

// Use Smarty template
$GLOBALS['xoopsOption']['template_main'] = 'customfields_admin_tests.tpl';

xoops_cp_header();

// Build list of test links (open in new tab)
$base = rtrim((string)XOOPS_URL, '/');
$module = 'customfields';
$tests = array(
    array(
        'label' => 'check_publisher.php',
        'href'  => $base . '/modules/' . $module . '/check_publisher.php',
        'desc'  => 'Publisher basic environment check',
    ),
    array(
        'label' => 'publisher_debug.php',
        'href'  => $base . '/modules/' . $module . '/publisher_debug.php',
        'desc'  => 'Admin-only debug tool and manual save test',
    ),
    array(
        'label' => 'test_item_customfields.php',
        'href'  => $base . '/modules/' . $module . '/test_item_customfields.php',
        'desc'  => 'Render and validate CustomFields for a sample item',
    ),
    array(
        'label' => 'test_publisher.php',
        'href'  => $base . '/modules/' . $module . '/test_publisher.php',
        'desc'  => 'Integration test helper for Publisher',
    ),
    array(
        'label' => 'test_publisher_save.php',
        'href'  => $base . '/modules/' . $module . '/test_publisher_save.php',
        'desc'  => 'Exercise save flow for Publisher + CustomFields',
    ),
);

$GLOBALS['xoopsTpl']->assign('tests_title', defined('_AM_CUSTOMFIELDS_TESTS_TITLE') ? _AM_CUSTOMFIELDS_TESTS_TITLE : 'Tests');
$GLOBALS['xoopsTpl']->assign('tests_desc', defined('_AM_CUSTOMFIELDS_TESTS_DESC') ? _AM_CUSTOMFIELDS_TESTS_DESC : 'Developer test helpers for CustomFields integration. Use on non-production systems.');
$GLOBALS['xoopsTpl']->assign('test_links', $tests);

require_once __DIR__ . '/admin_footer.php';
