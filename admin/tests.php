<?php
/**
 * CustomFields — Admin Diagnostics (migrated from standalone debug pages)
 * Admin-only controller action providing safe diagnostics behind XOOPS tokens.
 */

use XoopsModules\Customfields\Helper;

require_once __DIR__ . '/admin_header.php';
//require_once XOOPS_ROOT_PATH . '/modules/customfields/include/functions.php';


// Enforce admin access (double-check; admin area should already be gated)
if (!customfields_isAdminUser()) {
    header('HTTP/1.1 403 Forbidden');
    echo '<!doctype html><meta charset="utf-8"><title>Forbidden</title><p>Access denied.</p>';
    exit;
}

$helper = Helper::getInstance();
$helper->loadLanguage('admin');

// Template
$GLOBALS['xoopsOption']['template_main'] = 'customfields_admin_tests.tpl';
xoops_cp_header();

// Diagnostics context
$moduleDir = __DIR__ . '/..';
$logFile   = XOOPS_ROOT_PATH . '/modules/customfields/publisher_debug.log';

$writable  = is_writable(dirname($logFile));
if ($writable) {
    // touch log to verify write permissions (non-fatal if fails)
    @file_put_contents($logFile, '[' . date('Y-m-d H:i:s') . "] admin-tests boot\n", FILE_APPEND);
}

// Tabs: overview (default) or flow analyzer
$tab = isset($_GET['tab']) ? preg_replace('/[^a-z0-9_]/i', '', (string)$_GET['tab']) : 'overview';
$GLOBALS['xoopsTpl']->assign('tab', $tab);

// List fields for a target module (default: publisher)
$targetModule = isset($_GET['target_module']) ? preg_replace('/[^a-z0-9_]/i', '', (string)$_GET['target_module']) : 'publisher';
$fields       = customfields_getFields($targetModule);

// Optional manual save simulation (POST-only, token required)
$actionResult = [
    'ran' => false,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['manual_test'])) {
    $actionResult['ran'] = true;

    $tokenOk = true;
    if (isset($GLOBALS['xoopsSecurity']) && is_object($GLOBALS['xoopsSecurity'])) {
        $tokenOk = $GLOBALS['xoopsSecurity']->check();
    }

    if (!$tokenOk) {
        $actionResult['ok'] = false;
        $actionResult['message'] = 'Security token validation failed.';
    } else {
        // Prepare demo data
        $testItemId = 9999;
        // Simulate two sample fields if present
        if (!isset($_POST['customfield_1'])) {
            $_POST['customfield_1'] = 'Admin test at ' . date('Y-m-d H:i:s');
        }
        if (!isset($_POST['customfield_2'])) {
            $_POST['customfield_2'] = 'sample-image-path.jpg';
        }

        // Call saveData
        $simulateError = isset($_POST['simulate_error']) && $_POST['simulate_error'] == '1';
        $ok = customfields_saveData($targetModule, $testItemId, [ 'simulate_error' => $simulateError ]);

        $actionResult['ok'] = (bool)$ok;
        $actionResult['item_id'] = (int)$testItemId;
        $actionResult['simulated'] = (bool)$simulateError;

        // Query for persisted rows
        global $xoopsDB;
        $table = $xoopsDB->prefix('customfields_data');
        $sql   = "SELECT COUNT(*) AS c FROM {$table} WHERE target_module='" . addslashes($targetModule) . "' AND item_id=" . (int)$testItemId;
        $res   = $xoopsDB->query($sql);
        $count = 0;
        if ($res) {
            $row = $xoopsDB->fetchArray($res);
            if ($row && isset($row['c'])) {
                $count = (int)$row['c'];
            }
        }
        $actionResult['rows'] = $count;
    }
}

// Assign to template
$GLOBALS['xoopsTpl']->assign('dir_writable', (bool)$writable);
$GLOBALS['xoopsTpl']->assign('log_file', customfields_esc($logFile));
$GLOBALS['xoopsTpl']->assign('target_module', customfields_esc($targetModule));
$GLOBALS['xoopsTpl']->assign('fields', $fields);
$GLOBALS['xoopsTpl']->assign('has_fields', !empty($fields));
$GLOBALS['xoopsTpl']->assign('action', $actionResult);

// Token HTML for forms
$tokenHtml = '';
if (isset($GLOBALS['xoopsSecurity']) && is_object($GLOBALS['xoopsSecurity'])) {
    $tokenHtml = $GLOBALS['xoopsSecurity']->getTokenHTML();
}
$GLOBALS['xoopsTpl']->assign('token_html', $tokenHtml);

// For CustomFields log entries
$cfLogEntries = [];
if (file_exists($logFile)) {
    $lines = @file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (is_array($lines)) {
        $cfLogEntries = array_slice($lines, -200);
    }
}
// Join in PHP instead of Smarty
$GLOBALS['xoopsTpl']->assign('cf_log_entries', implode("\n", $cfLogEntries));

// For PHP error log entries
$phpLogEntries = [];
$phpErrLog = ini_get('error_log');
if ($phpErrLog && file_exists($phpErrLog)) {
    $plines = @file($phpErrLog, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (is_array($plines)) {
        $filtered = [];
        foreach ($plines as $ln) {
            if (stripos($ln, 'customfields') !== false) {
                $filtered[] = $ln;
            }
        }
        $phpLogEntries = array_slice($filtered, -200);
    }
}
// Join in PHP instead of Smarty
$GLOBALS['xoopsTpl']->assign('php_error_log', (string)$phpErrLog);
$GLOBALS['xoopsTpl']->assign('php_log_entries', implode("\n", $phpLogEntries));

// Flow analyzer (for flow tab)
$flow = [
    'file_exists' => false,
    'file_path' => XOOPS_ROOT_PATH . '/modules/publisher/admin/item.php',
    'render_form' => [ 'found' => false, 'line' => 0, 'snippet' => [] ],
    'save_data'   => [ 'found' => false, 'line' => 0, 'snippet' => [] ],
    'store_line'  => 0,
    'first_redirect_after_store' => 0,
    'saveData_after_store' => 0,
    'code_block' => [],
];

if ($tab === 'flow') {
    $itemPhp = $flow['file_path'];
    if (file_exists($itemPhp)) {
        $flow['file_exists'] = true;
        $lines = file($itemPhp);
        // renderForm
        foreach ($lines as $num => $line) {
            if (strpos($line, 'customfields_renderForm') !== false) {
                $flow['render_form']['found'] = true;
                $flow['render_form']['line'] = $num + 1;
                for ($i = max(0, $num-2); $i < min(count($lines), $num+3); $i++) {
                    $flow['render_form']['snippet'][] = $lines[$i];
                }
                break;
            }
        }
        // saveData
        foreach ($lines as $num => $line) {
            if (strpos($line, 'customfields_saveData') !== false) {
                $flow['save_data']['found'] = true;
                $flow['save_data']['line'] = $num + 1;
                for ($i = max(0, $num-3); $i < min(count($lines), $num+5); $i++) {
                    $flow['save_data']['snippet'][] = $lines[$i];
                }
                break;
            }
        }
        // store + subsequent checks
        foreach ($lines as $num => $line) {
            if (strpos($line, '->store()') !== false && strpos($line, 'itemObj') !== false && strpos($line, 'if') !== false) {
                $flow['store_line'] = $num + 1;
                for ($i = $num + 1; $i < min(count($lines), $num + 30); $i++) {
                    if ($flow['first_redirect_after_store'] == 0 && strpos($lines[$i], 'redirect_header') !== false) {
                        $flow['first_redirect_after_store'] = $i + 1;
                    }
                    if ($flow['saveData_after_store'] == 0 && strpos($lines[$i], 'customfields_saveData') !== false) {
                        $flow['saveData_after_store'] = $i + 1;
                    }
                    if ($flow['first_redirect_after_store'] > 0 && $flow['saveData_after_store'] > 0) {
                        break;
                    }
                }
                // code block excerpt
                for ($i = $flow['store_line'] - 1; $i < min(count($lines), $flow['store_line'] + 24); $i++) {
                    $flow['code_block'][] = $lines[$i];
                }
                break;
            }
        }
    }
}

// For flow snippets
$flow['render_form']['snippet_text'] = implode('', $flow['render_form']['snippet']);
$flow['save_data']['snippet_text'] = implode('', $flow['save_data']['snippet']);
$flow['code_block_text'] = implode('', $flow['code_block']);

$GLOBALS['xoopsTpl']->assign('flow', $flow);

require_once __DIR__ . '/admin_footer.php';
