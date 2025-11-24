<?php
/**
 * Publisher Entegrasyon Test
 * Bu dosyayı modules/customfields/ klasörüne koyun
 * URL: http://siteniz.com/modules/customfields/test_publisher_save.php
 */

require __DIR__ . '/header.php';

require_once __DIR__ . '/include/functions.php';

if (!customfields_isAdminUser()) {
    header('HTTP/1.1 403 Forbidden');
    echo '<!doctype html><meta charset="utf-8"><title>Forbidden</title><p>Access denied.</p>';
    exit;
}

echo '<h2>' . _MD_CUSTOMFIELDS_SAVE_TEST_TITLE . '</h2>';

// Manuel POST verisi simüle et
$_POST['customfield_1'] = sprintf(_MD_CUSTOMFIELDS_POST_SAMPLE_TITLE, date('H:i:s'));
$_POST['customfield_2'] = _MD_CUSTOMFIELDS_POST_SAMPLE_IMAGE;

echo '<h3>' . _MD_CUSTOMFIELDS_POST_SIM_SECTION . '</h3>';
echo '<pre>';
print_r($_POST);
echo '</pre>';

// CustomFields functions dahil et
include_once XOOPS_ROOT_PATH . '/modules/customfields/include/functions.php';

echo '<h3>' . _MD_CUSTOMFIELDS_CALL_SAVEDATA . '</h3>';

// Test item ID
$test_item_id = 999;

echo sprintf(_MD_CUSTOMFIELDS_SAVING_FOR, (int)$test_item_id) . '<br>';

// Kaydet
$result = customfields_saveData('publisher', $test_item_id);

echo sprintf(_MD_CUSTOMFIELDS_RESULT_BOOL, ($result ? '✅ TRUE' : '❌ FALSE')) . '<br>';

// Kontrol et
echo '<h3>' . _MD_CUSTOMFIELDS_DB_CHECK . '</h3>';
global $xoopsDB;

$table = $xoopsDB->prefix('customfields_data');
$query = "SELECT * FROM $table WHERE target_module='publisher' AND item_id=$test_item_id";
echo _MD_CUSTOMFIELDS_SQL . ': ' . htmlspecialchars($query, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '<br><br>';

$result = $xoopsDB->query($query);
if ($result && $xoopsDB->getRowsNum($result) > 0) {
    echo '✅ ' . _MD_CUSTOMFIELDS_DATA_FOUND . '<br>';
    echo '<table border="1" cellpadding="5" style="border-collapse: collapse;">';
    echo '<tr style="background: #48bb78; color: white;">';
    echo '<th>data_id</th><th>field_id</th><th>item_id</th><th>field_value</th><th>created</th>';
    echo '</tr>';
    while ($row = $xoopsDB->fetchArray($result)) {
        echo '<tr>';
        echo '<td>' . (int)$row['data_id'] . '</td>';
        echo '<td>' . (int)$row['field_id'] . '</td>';
        echo '<td>' . (int)$row['item_id'] . '</td>';
        echo '<td><strong>' . htmlspecialchars($row['field_value'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</strong></td>';
        echo '<td>' . date('Y-m-d H:i:s', (int)$row['created']) . '</td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo '❌ ' . _MD_CUSTOMFIELDS_DATA_NOT_SAVED . '<br>';
    echo '<p style="color: red;">' . _MD_CUSTOMFIELDS_POSSIBLE_HANDLER_ISSUE . '</p>';
}

// Error log'dan son satırları göster
echo '<h3>' . _MD_CUSTOMFIELDS_LAST_ERROR_LOGS . '</h3>';
$error_log = ini_get('error_log');
if ($error_log && file_exists($error_log)) {
    $lines = file($error_log);
    $customfields_lines = array_filter($lines, function($line) {
        return stripos($line, 'customfields') !== false;
    });
    $customfields_lines = array_slice($customfields_lines, -20);

    if (count($customfields_lines) > 0) {
        echo '<pre style="background:#f5f5f5; padding:10px; max-height:300px; overflow:auto;">';
        foreach ($customfields_lines as $line) {
            if (stripos($line, 'DEBUG') !== false) {
                echo '<span style="color: blue;">' . htmlspecialchars($line, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</span>';
            } elseif (stripos($line, 'ERROR') !== false) {
                echo '<span style="color: red;">' . htmlspecialchars($line, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</span>';
            } else {
                echo htmlspecialchars($line, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            }
        }
        echo '</pre>';
    } else {
        echo '<p>' . _MD_CUSTOMFIELDS_NO_CF_LOG_ENTRIES . '</p>';
    }
} else {
    echo '<p>' . sprintf(_MD_CUSTOMFIELDS_ERRLOG_NOT_FOUND, htmlspecialchars((string)$error_log, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')) . '</p>';
}

echo '<hr>';
echo '<h3>' . _MD_CUSTOMFIELDS_NEXT_STEPS_TITLE . '</h3>';
echo '<ol>';
echo '<li>' . _MD_CUSTOMFIELDS_NEXT_STEP_REFRESH . '</li>';
echo '<li>' . _MD_CUSTOMFIELDS_NEXT_STEP_EXPECT . '</li>';
echo '<li>' . _MD_CUSTOMFIELDS_NEXT_STEP_LOG . '</li>';
echo '<li>' . _MD_CUSTOMFIELDS_NEXT_STEP_INTEGRATION . '</li>';
echo '</ol>';
echo '<p><a href="test_publisher.php">' . _MD_CUSTOMFIELDS_BACK_TO_MAIN . '</a></p>';
?>

