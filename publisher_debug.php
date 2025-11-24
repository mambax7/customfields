<?php
/**
 * Publisher Debug - Ekran Çıktısı
 * Bu dosyayı modules/customfields/ klasörüne yükleyin
 */

// Debug modunu aç
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log dosyası oluştur
$log_file = __DIR__ . '/publisher_debug.log';

function debug_log($message) {
    global $log_file;
    $time = date('Y-m-d H:i:s');
    file_put_contents($log_file, "[$time] $message\n", FILE_APPEND);
}

require __DIR__ . '/header.php';
require_once __DIR__ . '/include/functions.php';

// Gate this page behind admin permission to avoid exposure in production
if (!customfields_isAdminUser()) {
    header('HTTP/1.1 403 Forbidden');
    echo '<!doctype html><meta charset="utf-8"><title>Forbidden</title><p>Access denied.</p>';
    exit;
}

echo '<html><head><meta charset="utf-8"><title>' . _MD_CUSTOMFIELDS_PUBDEBUG_PAGE_TITLE . '</title>';
echo '<style>
body { font-family: Arial; padding: 20px; background: #f5f5f5; }
.box { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.success { background: #d4edda; border-left: 4px solid #28a745; }
.error { background: #f8d7da; border-left: 4px solid #dc3545; }
.warning { background: #fff3cd; border-left: 4px solid #ffc107; }
.info { background: #d1ecf1; border-left: 4px solid #0dcaf0; }
pre { background: #f8f9fa; padding: 10px; overflow-x: auto; border-radius: 4px; }
.button { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; margin: 10px 0; }
</style></head><body>';

echo '<h1>🔍 ' . _MD_CUSTOMFIELDS_PUBDEBUG_PAGE_TITLE . '</h1>';

// Test 1: Log dosyası oluşturabilir miyiz?
echo '<div class="box">';
echo '<h2>' . _MD_CUSTOMFIELDS_PUBDEBUG_SECTION1_TITLE . '</h2>';

if (is_writable(__DIR__)) {
    echo '<p class="success">✅ ' . _MD_CUSTOMFIELDS_PUBDEBUG_FOLDER_WRITABLE . '</p>';
    debug_log("Test log - " . date('Y-m-d H:i:s'));
    echo '<p>' . _MD_CUSTOMFIELDS_PUBDEBUG_LOG_FILE . ': <code>' . customfields_esc($log_file) . '</code></p>';
    
    if (file_exists($log_file)) {
        echo '<p>✅ ' . _MD_CUSTOMFIELDS_PUBDEBUG_LOG_FILE . ' ' . _MD_CUSTOMFIELDS_FILE_FOUND . '</p>';
        echo '<a href="publisher_debug.log" target="_blank" class="button">' . _MD_CUSTOMFIELDS_PUBDEBUG_VIEW_LOG . '</a>';
    }
} else {
    echo '<p class="error">❌ ' . _MD_CUSTOMFIELDS_PUBDEBUG_FOLDER_NOT_WRITABLE . '</p>';
    echo '<p>' . _MD_CUSTOMFIELDS_PUBDEBUG_FIX_PERMS . '</p>';
}
echo '</div>';

// Test 2: CustomFields çalışıyor mu?
echo '<div class="box">';
echo '<h2>' . _MD_CUSTOMFIELDS_PUBDEBUG_SECTION2_TITLE . '</h2>';

include_once XOOPS_ROOT_PATH . '/modules/customfields/include/functions.php';

$fields = customfields_getFields('publisher');
echo '<p>' . sprintf(_MD_CUSTOMFIELDS_PUBDEBUG_FOUND_FIELDS, (int)count($fields)) . '</p>';

if (count($fields) > 0) {
    echo '<ul>';
    foreach ($fields as $field) {
        echo '<li>ID: ' . (int)$field->getVar('field_id') . ' - ' . customfields_esc($field->getVar('field_name')) . ' (' . customfields_esc($field->getVar('field_title')) . ')</li>';
    }
    echo '</ul>';
}
echo '</div>';

// Test 3: Manuel kaydetme testi
echo '<div class="box">';
echo '<h2>' . _MD_CUSTOMFIELDS_PUBDEBUG_SECTION3_TITLE . '</h2>';

if (isset($_POST['manual_test'])) {
    echo '<div class="info">';
    echo '<h3>' . _MD_CUSTOMFIELDS_PUBDEBUG_TEST_STARTING . '</h3>';
    
    $test_item_id = 9999;
    
    // POST verisini simüle et
    $_POST['customfield_1'] = 'Test metin: ' . date('Y-m-d H:i:s');
    $_POST['customfield_2'] = 'Test resim yolu';
    
    echo '<p><strong>' . _MD_CUSTOMFIELDS_PUBDEBUG_SIMULATED_POST . '</strong></p>';
    echo '<pre>' . htmlspecialchars(print_r($_POST, true), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</pre>';
    
    // Debug logla
    debug_log("=== MANUEL TEST BAŞLADI ===");
    debug_log("Item ID: " . $test_item_id);
    debug_log("POST: " . print_r($_POST, true));
    
    try {
        // customfields_saveData çağır
        $result = customfields_saveData('publisher', $test_item_id);
        
        debug_log("customfields_saveData sonucu: " . ($result ? 'true' : 'false'));
        
        // Veritabanını kontrol et
        global $xoopsDB;
        $sql = "SELECT * FROM " . $xoopsDB->prefix('customfields_data') .
               " WHERE target_module=" . $xoopsDB->quoteString('publisher') .
               " AND item_id=" . (int)$test_item_id;
        $result = $xoopsDB->query($sql);
        
        if ($result && $xoopsDB->getRowsNum($result) > 0) {
            echo '<p class="success">✅ <strong>' . _MD_CUSTOMFIELDS_PUBDEBUG_RESULT_OK . '</strong></p>';
            echo '<table border="1" cellpadding="5">';
            echo '<tr><th>Field ID</th><th>Değer</th></tr>';
            while ($row = $xoopsDB->fetchArray($result)) {
                echo '<tr><td>' . $row['field_id'] . '</td><td>' . htmlspecialchars($row['field_value'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</td></tr>';
            }
            echo '</table>';
            debug_log("Veritabanı kontrolü: BAŞARILI");
        } else {
            echo '<p class="error">❌ ' . _MD_CUSTOMFIELDS_PUBDEBUG_RESULT_FAIL . '</p>';
            debug_log("Veritabanı kontrolü: BAŞARISIZ");
        }
        
    } catch (Exception $e) {
        echo '<p class="error">❌ Hata: ' . customfields_esc($e->getMessage()) . '</p>';
        debug_log("HATA: " . $e->getMessage());
    }
    
    debug_log("=== MANUEL TEST BİTTİ ===");
    echo '</div>';
    
    echo '<a href="publisher_debug.log" target="_blank" class="button">' . _MD_CUSTOMFIELDS_PUBDEBUG_VIEW_LOG . '</a>';
    
} else {
    echo '<form method="post">';
    echo '<p>' . _MD_CUSTOMFIELDS_PUBDEBUG_MANUAL_FORM_DESC . '</p>';
    echo '<button type="submit" name="manual_test" class="button">🧪 ' . _MD_CUSTOMFIELDS_PUBDEBUG_MANUAL_BTN . '</button>';
    echo '</form>';
}

echo '</div>';

// Test 4: Gerçek Publisher testi için talimatlar
echo '<div class="box warning">';
echo '<h2>' . _MD_CUSTOMFIELDS_PUBDEBUG_SECTION4_TITLE . '</h2>';
echo '<p>' . _MD_CUSTOMFIELDS_PUBDEBUG_REAL_TEST_INTRO . '</p>';
echo '<ol>';
echo '<li>' . _MD_CUSTOMFIELDS_PUBDEBUG_OPEN_ITEMPHP . '</li>';
echo '<li>' . _MD_CUSTOMFIELDS_PUBDEBUG_FIND_LINE127 . '</li>';
echo '</ol>';

echo '<pre style="background: #2d3748; color: #e2e8f0; padding: 15px;">';
echo htmlspecialchars('
        // DEBUG
        $debug_file = XOOPS_ROOT_PATH . \'/modules/customfields/publisher_debug.log\';
        file_put_contents($debug_file, "\n=== PUBLISHER KAYIT " . date(\'Y-m-d H:i:s\') . " ===\n", FILE_APPEND);
        file_put_contents($debug_file, "Store başarılı!\n", FILE_APPEND);
        
        // İLAVE ALANLARI KAYDET
        include_once XOOPS_ROOT_PATH . \'/modules/customfields/include/functions.php\';
        $savedItemId = $itemObj->itemid();
        file_put_contents($debug_file, "Item ID: " . $savedItemId . "\n", FILE_APPEND);
        file_put_contents($debug_file, "POST verisi: " . print_r($_POST, true) . "\n", FILE_APPEND);
        customfields_saveData(\'publisher\', $savedItemId);
        file_put_contents($debug_file, "customfields_saveData() çağrıldı\n", FILE_APPEND);
        file_put_contents($debug_file, "=== BİTTİ ===\n\n", FILE_APPEND);
', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
echo '</pre>';

echo '<ol start="3">';
echo '<li>Kaydedin ve sunucuya yükleyin</li>';
echo '<li>Publisher\'da makale ekleyin/düzenleyin</li>';
echo '<li>İlave alanları doldurun</li>';
echo '<li><strong>KAYDET</strong></li>';
echo '<li>Aşağıdaki butona tıklayın:</li>';
echo '</ol>';

echo '<a href="publisher_debug.log?t=' . time() . '" target="_blank" class="button">📄 ' . _MD_CUSTOMFIELDS_PUBDEBUG_VIEW_LOG_NEW . '</a>';
echo '<a href="?refresh=' . time() . '" class="button">🔄 ' . _MD_CUSTOMFIELDS_PUBDEBUG_REFRESH_PAGE . '</a>';

echo '</div>';

// Test 5: Mevcut log içeriğini göster
if (file_exists($log_file)) {
    echo '<div class="box">';
    echo '<h2>' . _MD_CUSTOMFIELDS_PUBDEBUG_SECTION5_LOG_CONTENT . '</h2>';
    $log_content = file_get_contents($log_file);
    if (!empty($log_content)) {
        echo '<pre>' . htmlspecialchars($log_content, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</pre>';
    } else {
        echo '<p>' . _MD_CUSTOMFIELDS_PUBDEBUG_LOG_EMPTY . '</p>';
    }
    echo '</div>';
}

// Test 6: Veritabanı durumu
echo '<div class="box">';
echo '<h2>' . _MD_CUSTOMFIELDS_PUBDEBUG_SECTION6_DB_STATUS . '</h2>';

global $xoopsDB;
$result = $xoopsDB->query(
    "SELECT COUNT(*) as cnt FROM " . $xoopsDB->prefix('customfields_data') .
    " WHERE target_module=" . $xoopsDB->quoteString('publisher')
);
if ($result) {
    $row = $xoopsDB->fetchArray($result);
    echo '<p>' . sprintf(_MD_CUSTOMFIELDS_PUBDEBUG_DB_TOTAL, (int)$row['cnt']) . '</p>';
    
    if ($row['cnt'] > 0) {
        echo '<p class="success">✅ ' . _MD_CUSTOMFIELDS_PUBDEBUG_DB_YES . '</p>';
        
        // Son 5 veriyi göster
        $result = $xoopsDB->query(
            "SELECT d.*, f.field_name " .
            "FROM " . $xoopsDB->prefix('customfields_data') . " d " .
            "LEFT JOIN " . $xoopsDB->prefix('customfields_fields') . " f ON d.field_id = f.field_id " .
            "WHERE d.target_module=" . $xoopsDB->quoteString('publisher') . ' ' .
            "ORDER BY d.data_id DESC LIMIT 5"
        );
        
        echo '<table border="1" cellpadding="5" style="width: 100%; border-collapse: collapse;">';
        echo '<tr><th>ID</th><th>Item ID</th><th>Field</th><th>Value</th><th>Date</th></tr>';
        while ($row = $xoopsDB->fetchArray($result)) {
            echo '<tr>';
            echo '<td>' . $row['data_id'] . '</td>';
            echo '<td><strong>' . $row['item_id'] . '</strong></td>';
            echo '<td>' . $row['field_name'] . '</td>';
            echo '<td>' . htmlspecialchars(substr($row['field_value'], 0, 50), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</td>';
            echo '<td>' . date('Y-m-d H:i', $row['created']) . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo '<p class="error">❌ ' . _MD_CUSTOMFIELDS_PUBDEBUG_DB_NO . '</p>';
    }
}

echo '</div>';

echo '<div class="box info">';
echo '<h3>📝 ' . _MD_CUSTOMFIELDS_PUBDEBUG_RESULT_HEADING . '</h3>';
echo '<p><strong>' . _MD_CUSTOMFIELDS_PUBDEBUG_RESULT_HINT_OK . '</strong></p>';
echo '<p><strong>' . _MD_CUSTOMFIELDS_PUBDEBUG_RESULT_HINT_FAIL . '</strong></p>';
echo '<p><strong>' . _MD_CUSTOMFIELDS_PUBDEBUG_RESULT_HINT_ITEM0 . '</strong></p>';
echo '</div>';

echo '</body></html>';
