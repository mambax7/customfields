<?php
/**
 * CustomFields Publisher Test & Debug Tool
 */

require __DIR__ . '/header.php';

require_once __DIR__ . '/include/functions.php';

if (!customfields_isAdminUser()) {
    header('HTTP/1.1 403 Forbidden');
    echo '<!doctype html><meta charset="utf-8"><title>Forbidden</title><p>Access denied.</p>';
    exit;
}

echo '<html><head><meta charset="utf-8"><title>' . _MD_CUSTOMFIELDS_CUSTOMFIELDS_TEST_TITLE . '</title></head><body>';
echo '<h2>' . _MD_CUSTOMFIELDS_PUBLISHER_TEST_TITLE . '</h2>';

// 1. CustomFields modülü kurulu mu?
echo '<h3>' . _MD_CUSTOMFIELDS_MODULE_CHECK . '</h3>';
if (file_exists(XOOPS_ROOT_PATH . '/modules/customfields/include/functions.php')) {
    echo '✅ ' . _MD_CUSTOMFIELDS_MODULE_FOUND . '<br>';
    include_once XOOPS_ROOT_PATH . '/modules/customfields/include/functions.php';
} else {
    echo '❌ ' . _MD_CUSTOMFIELDS_MODULE_NOT_FOUND . '<br>';
    exit;
}

// 2. Publisher alanları var mı?
echo '<h3>' . _MD_CUSTOMFIELDS_PUBLISHER_FIELDS . '</h3>';
$fields = customfields_getFields('publisher');
if (count($fields) > 0) {
    echo '✅ ' . sprintf(_MD_CUSTOMFIELDS_FIELDS_FOUND_N, (int)count($fields)) . '<br>';
    echo '<ul>';
    foreach ($fields as $field) {
        echo '<li>ID: ' . (int)$field->getVar('field_id') . ', ' . _MD_CUSTOMFIELDS_TABLE_HEADERS . ': <strong>' . htmlspecialchars($field->getVar('field_name'), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</strong>, ' . htmlspecialchars($field->getVar('field_title'), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</li>';
    }
    echo '</ul>';
} else {
    echo '❌ ' . _MD_CUSTOMFIELDS_FIELDS_NOT_FOUND . '<br>';
}

// 3. Veritabanı tabloları var mı?
echo '<h3>' . _MD_CUSTOMFIELDS_DB_TABLES . '</h3>';
global $xoopsDB;

try {
    // Tabloları listele
    $result = $xoopsDB->queryF("SHOW TABLES");
    $tables = array();
    while ($row = $xoopsDB->fetchRow($result)) {
        $tables[] = $row[0];
    }
    
    $table_fields = $xoopsDB->prefix('customfields_fields');
    $table_data = $xoopsDB->prefix('customfields_data');
    
    if (in_array($table_fields, $tables)) {
        echo '✅ ' . htmlspecialchars($table_fields, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . ' - ' . _MD_CUSTOMFIELDS_TABLE_EXISTS . '<br>';
    } else {
        echo '❌ ' . htmlspecialchars($table_fields, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . ' - ' . _MD_CUSTOMFIELDS_TABLE_MISSING . '<br>';
    }
    
    if (in_array($table_data, $tables)) {
        echo '✅ ' . htmlspecialchars($table_data, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . ' - ' . _MD_CUSTOMFIELDS_TABLE_EXISTS . '<br>';
        
        // Toplam veri sayısı
        $result = $xoopsDB->query("SELECT COUNT(*) as cnt FROM " . $table_data);
        if ($result) {
            $row = $xoopsDB->fetchArray($result);
            echo '📊 ' . sprintf(_MD_CUSTOMFIELDS_TOTAL_RECORDS, (int)$row['cnt']) . '<br>';
        }
        
        // Publisher verisi
        $result = $xoopsDB->query("SELECT COUNT(*) as cnt FROM " . $table_data . " WHERE target_module='publisher'");
        if ($result) {
            $row = $xoopsDB->fetchArray($result);
            echo '📊 ' . sprintf(_MD_CUSTOMFIELDS_PUBLISHER_RECORDS, (int)$row['cnt']) . '<br>';
        }
    } else {
        echo '❌ ' . htmlspecialchars($table_data, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . ' - ' . _MD_CUSTOMFIELDS_TABLE_MISSING . '<br>';
    }
} catch (Exception $e) {
    echo '❌ ' . sprintf(_MD_CUSTOMFIELDS_DB_ERROR, htmlspecialchars($e->getMessage(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')) . '<br>';
}

// 4. Son 10 Publisher verisini göster
echo '<h3>' . _MD_CUSTOMFIELDS_LAST_RECORDS . '</h3>';
try {
    $result = $xoopsDB->query("
        SELECT d.*, f.field_name, f.field_title 
        FROM " . $xoopsDB->prefix('customfields_data') . " d
        LEFT JOIN " . $xoopsDB->prefix('customfields_fields') . " f ON d.field_id = f.field_id
        WHERE d.target_module = 'publisher'
        ORDER BY d.data_id DESC 
        LIMIT 10
    ");
    
    if ($result && $xoopsDB->getRowsNum($result) > 0) {
        echo '<table border="1" cellpadding="5" style="border-collapse: collapse;">';
        echo '<tr style="background: #f0f0f0;">';
        echo '<th>ID</th><th>Item ID</th><th>Field</th><th>Value</th><th>Date</th>';
        echo '</tr>';
        while ($row = $xoopsDB->fetchArray($result)) {
            echo '<tr>';
            echo '<td>' . (int)$row['data_id'] . '</td>';
            echo '<td>' . (int)$row['item_id'] . '</td>';
            echo '<td>' . htmlspecialchars($row['field_name'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . ' (' . htmlspecialchars($row['field_title'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . ')</td>';
            echo '<td>' . htmlspecialchars(substr($row['field_value'], 0, 50), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</td>';
            echo '<td>' . date('Y-m-d H:i', (int)$row['created']) . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo '❌ ' . _MD_CUSTOMFIELDS_NO_PUBLISHER_DATA . '<br>';
        echo '<p style="color: red;">' . _MD_CUSTOMFIELDS_PROBLEM_NOT_SAVED . '</p>';
    }
} catch (Exception $e) {
    echo '❌ ' . sprintf(_MD_CUSTOMFIELDS_DB_ERROR, htmlspecialchars($e->getMessage(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')) . '<br>';
}

// 5. Test kaydetme
echo '<h3>' . _MD_CUSTOMFIELDS_MANUAL_TEST_TITLE . '</h3>';
if (isset($_POST['test_save']) && count($fields) > 0) {
    echo '<div style="background: #ffffcc; padding: 10px; margin: 10px 0;">';
    echo '<strong>' . _MD_CUSTOMFIELDS_TEST_STARTING . '</strong><br>';
    
    $test_item_id = 9999; // Test item ID
    $dataHandler = customfields_getDataHandler();
    
    $success_count = 0;
    foreach ($fields as $field) {
        $field_id = $field->getVar('field_id');
        $test_value = 'Test value: ' . date('Y-m-d H:i:s');
        
        echo sprintf(_MD_CUSTOMFIELDS_FIELD_SAVING, (int)$field_id, htmlspecialchars($field->getVar('field_name'), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
        
        $result = $dataHandler->saveItemData('publisher', $test_item_id, $field_id, $test_value);
        
        if ($result) {
            echo '<span style="color: green;">✓ ' . _MD_CUSTOMFIELDS_SUCCESS . '</span><br>';
            $success_count++;
        } else {
            echo '<span style="color: red;">✗ ' . _MD_CUSTOMFIELDS_FAILURE . '</span><br>';
        }
    }
    
    echo '<br><strong>' . sprintf(_MD_CUSTOMFIELDS_RESULT_SUMMARY, (int)$success_count, (int)count($fields)) . '</strong><br>';
    echo '<a href="test_publisher.php">' . _MD_CUSTOMFIELDS_REFRESH_AND_CHECK . '</a>';
    echo '</div>';
}

if (count($fields) > 0) {
    echo '<form method="post">';
    echo '<button type="submit" name="test_save" style="padding: 10px 20px; background: #4CAF50; color: white; border: none; cursor: pointer; font-size: 16px;">🧪 ' . _MD_CUSTOMFIELDS_MANUAL_TEST_BTN . '</button>';
    echo '</form>';
    echo '<p><small>' . _MD_CUSTOMFIELDS_MANUAL_TEST_DESC . '</small></p>';
}

// 6. PHP Error Log
echo '<h3>' . _MD_CUSTOMFIELDS_ERROR_LOG_TITLE . '</h3>';
$error_log = ini_get('error_log');
if ($error_log && file_exists($error_log)) {
    echo '📝 ' . _MD_CUSTOMFIELDS_ERROR_LOG_PATH . ': <code>' . htmlspecialchars((string)$error_log, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</code><br>';
    echo '<a href="?show_log=1">' . _MD_CUSTOMFIELDS_SHOW_LAST100 . '</a><br>';
    
    if (isset($_GET['show_log'])) {
        $lines = @file($error_log);
        if ($lines) {
            $lines = array_slice($lines, -100);
            
            // CustomFields ile ilgili satırları filtrele
            $filtered = array();
            foreach ($lines as $line) {
                if (stripos($line, 'customfields') !== false || stripos($line, 'DEBUG:') !== false) {
                    $filtered[] = $line;
                }
            }
            
            if (count($filtered) > 0) {
                echo '<h4>CustomFields logs (' . (int)count($filtered) . ' lines):</h4>';
                echo '<pre style="background:#f5f5f5; padding:10px; max-height:400px; overflow:auto; font-size: 11px;">';
                echo htmlspecialchars(implode('', $filtered), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                echo '</pre>';
            } else {
                echo '<p style="color: orange;">⚠️ ' . _MD_CUSTOMFIELDS_NO_CF_LOGS_LAST100 . '</p>';
                echo '<p>' . _MD_CUSTOMFIELDS_PUBLISHER_ADD_HINT . '</p>';
            }
        } else {
            echo '<p style="color: red;">❌ ' . _MD_CUSTOMFIELDS_ERROR_LOG_READ_FAIL . '</p>';
        }
    }
} else {
    echo '⚠️ ' . _MD_CUSTOMFIELDS_ERROR_LOG_NOT_FOUND . '<br>';
    echo _MD_CUSTOMFIELDS_PHPINI_CHECK . '<br>';
}

// 7. Publisher item.php kontrol
echo '<h3>' . _MD_CUSTOMFIELDS_INTEGRATION_CHECK_TITLE . '</h3>';
$publisher_item = XOOPS_ROOT_PATH . '/modules/publisher/admin/item.php';
if (file_exists($publisher_item)) {
    echo '✅ ' . _MD_CUSTOMFIELDS_ITEMPHP_FOUND . '<br>';
    
    $content = file_get_contents($publisher_item);
    
    // customfields_saveData kontrolü
    if (strpos($content, 'customfields_saveData') !== false) {
        echo '✅ <strong>customfields_saveData()</strong> ' . _MD_CUSTOMFIELDS_CALL_PRESENT . '<br>';
        
        // Satır numarasını bul
        $lines = explode("\n", $content);
        foreach ($lines as $num => $line) {
            if (strpos($line, 'customfields_saveData') !== false) {
                $line_num = $num + 1;
                echo '📍 ' . _MD_CUSTOMFIELDS_LINE . ' <strong>' . (int)$line_num . '</strong>: <code>' . htmlspecialchars(trim($line), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</code><br>';
            }
        }
    } else {
        echo '❌ <strong>customfields_saveData()</strong> ' . _MD_CUSTOMFIELDS_CALL_ABSENT . '<br>';
        echo '<p style="color: red; background: #fee; padding: 10px;">' . _MD_CUSTOMFIELDS_INTEGRATION_MISSING . '</p>';
    }
    
    // customfields_renderForm kontrolü
    if (strpos($content, 'customfields_renderForm') !== false) {
        echo '✅ <strong>customfields_renderForm()</strong> ' . _MD_CUSTOMFIELDS_CALL_PRESENT . '<br>';
    } else {
        echo '⚠️ <strong>customfields_renderForm()</strong> ' . _MD_CUSTOMFIELDS_FORM_CALL_ABSENT . '<br>';
    }
    
} else {
    echo '❌ ' . _MD_CUSTOMFIELDS_FILE_NOT_FOUND . '<br>';
}

echo '<hr>';
echo '<h3>📋 ' . _MD_CUSTOMFIELDS_NEXT_STEPS_CHECKLIST_TITLE . '</h3>';
echo '<ol>';
echo '<li>✅ ' . _MD_CUSTOMFIELDS_HANDLER_WORKS_IF_MANUAL_OK . '</li>';
echo '<li>✅ ' . _MD_CUSTOMFIELDS_CODE_IN_RIGHT_PLACE_IF_PRESENT . '</li>';
echo '<li>❌ ' . _MD_CUSTOMFIELDS_ITEM_ZERO_MEANS_NOT_WORKING . '</li>';
echo '<li>🔍 ' . _MD_CUSTOMFIELDS_ADD_ARTICLE_AND_CHECK_LOG . '</li>';
echo '<li>📝 ' . _MD_CUSTOMFIELDS_LOOK_FOR_DEBUG_ITEMID . '</li>';
echo '</ol>';

echo '<div style="background: #e7f3ff; padding: 15px; margin: 20px 0; border-left: 4px solid #2196F3;">';
echo '<h4 style="margin-top: 0;">💡 ' . _MD_CUSTOMFIELDS_QUICK_TEST_TITLE . '</h4>';
echo '<ol>';
echo '<li>' . _MD_CUSTOMFIELDS_PRESS_MANUAL_TEST . '</li>';
echo '<li>' . _MD_CUSTOMFIELDS_REFRESH_PAGE_SHORT . '</li>';
echo '<li>' . _MD_CUSTOMFIELDS_SHOULD_SEE_ITEM9999 . '</li>';
echo '<li>' . _MD_CUSTOMFIELDS_IF_SEE_ISSUE_IN_INTEGRATION . '</li>';
echo '<li>' . _MD_CUSTOMFIELDS_IF_NOT_SEE_ISSUE_IN_HANDLER . '</li>';
echo '</ol>';
echo '</div>';

echo '</body></html>';
