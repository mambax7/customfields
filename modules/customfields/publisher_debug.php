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

include '../../mainfile.php';

echo '<html><head><meta charset="utf-8"><title>Publisher Debug</title>';
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

echo '<h1>🔍 Publisher Debug Tool</h1>';

// Test 1: Log dosyası oluşturabilir miyiz?
echo '<div class="box">';
echo '<h2>1. Log Dosyası Kontrolü</h2>';

if (is_writable(__DIR__)) {
    echo '<p class="success">✅ Klasör yazılabilir, log dosyası oluşturulabilir</p>';
    debug_log("Test log - " . date('Y-m-d H:i:s'));
    echo '<p>Log dosyası: <code>' . $log_file . '</code></p>';
    
    if (file_exists($log_file)) {
        echo '<p>✅ Log dosyası oluşturuldu</p>';
        echo '<a href="publisher_debug.log" target="_blank" class="button">Log Dosyasını Görüntüle</a>';
    }
} else {
    echo '<p class="error">❌ Klasör yazılabilir değil!</p>';
    echo '<p>Çözüm: modules/customfields/ klasörüne 755 veya 777 izni verin</p>';
}
echo '</div>';

// Test 2: CustomFields çalışıyor mu?
echo '<div class="box">';
echo '<h2>2. CustomFields Test</h2>';

include_once XOOPS_ROOT_PATH . '/modules/customfields/include/functions.php';

$fields = customfields_getFields('publisher');
echo '<p>Publisher için <strong>' . count($fields) . '</strong> alan bulundu</p>';

if (count($fields) > 0) {
    echo '<ul>';
    foreach ($fields as $field) {
        echo '<li>ID: ' . $field->getVar('field_id') . ' - ' . $field->getVar('field_name') . ' (' . $field->getVar('field_title') . ')</li>';
    }
    echo '</ul>';
}
echo '</div>';

// Test 3: Manuel kaydetme testi
echo '<div class="box">';
echo '<h2>3. Manuel Kaydetme Testi</h2>';

if (isset($_POST['manual_test'])) {
    echo '<div class="info">';
    echo '<h3>Test Başlatılıyor...</h3>';
    
    $test_item_id = 9999;
    
    // POST verisini simüle et
    $_POST['customfield_1'] = 'Test metin: ' . date('Y-m-d H:i:s');
    $_POST['customfield_2'] = 'Test resim yolu';
    
    echo '<p><strong>Simüle edilen POST verisi:</strong></p>';
    echo '<pre>' . print_r($_POST, true) . '</pre>';
    
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
        $sql = "SELECT * FROM " . $xoopsDB->prefix('customfields_data') . " WHERE target_module='publisher' AND item_id=" . $test_item_id;
        $result = $xoopsDB->query($sql);
        
        if ($result && $xoopsDB->getRowsNum($result) > 0) {
            echo '<p class="success">✅ <strong>BAŞARILI!</strong> Veri veritabanına kaydedildi!</p>';
            echo '<table border="1" cellpadding="5">';
            echo '<tr><th>Field ID</th><th>Değer</th></tr>';
            while ($row = $xoopsDB->fetchArray($result)) {
                echo '<tr><td>' . $row['field_id'] . '</td><td>' . htmlspecialchars($row['field_value']) . '</td></tr>';
            }
            echo '</table>';
            debug_log("Veritabanı kontrolü: BAŞARILI");
        } else {
            echo '<p class="error">❌ Veri veritabanında bulunamadı!</p>';
            debug_log("Veritabanı kontrolü: BAŞARISIZ");
        }
        
    } catch (Exception $e) {
        echo '<p class="error">❌ Hata: ' . $e->getMessage() . '</p>';
        debug_log("HATA: " . $e->getMessage());
    }
    
    debug_log("=== MANUEL TEST BİTTİ ===");
    echo '</div>';
    
    echo '<a href="publisher_debug.log" target="_blank" class="button">Log Dosyasını Görüntüle</a>';
    
} else {
    echo '<form method="post">';
    echo '<p>Bu test, Publisher Item ID 9999 ile veri kaydetmeyi simüle eder.</p>';
    echo '<button type="submit" name="manual_test" class="button">🧪 Manuel Test Başlat</button>';
    echo '</form>';
}

echo '</div>';

// Test 4: Gerçek Publisher testi için talimatlar
echo '<div class="box warning">';
echo '<h2>4. Publisher Gerçek Test (Önemli!)</h2>';
echo '<p>Yukarıdaki manuel test başarılı olduysa, şimdi gerçek Publisher testine geçin:</p>';
echo '<ol>';
echo '<li><strong>Publisher item.php</strong> dosyasını açın</li>';
echo '<li><strong>Satır 127</strong> civarına (// İLAVE ALANLARI KAYDET satırından önce) şu kodu ekleyin:</li>';
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
');
echo '</pre>';

echo '<ol start="3">';
echo '<li>Kaydedin ve sunucuya yükleyin</li>';
echo '<li>Publisher\'da makale ekleyin/düzenleyin</li>';
echo '<li>İlave alanları doldurun</li>';
echo '<li><strong>KAYDET</strong></li>';
echo '<li>Aşağıdaki butona tıklayın:</li>';
echo '</ol>';

echo '<a href="publisher_debug.log?t=' . time() . '" target="_blank" class="button">📄 Log Dosyasını Görüntüle (Yeni Pencere)</a>';
echo '<a href="?refresh=' . time() . '" class="button">🔄 Bu Sayfayı Yenile</a>';

echo '</div>';

// Test 5: Mevcut log içeriğini göster
if (file_exists($log_file)) {
    echo '<div class="box">';
    echo '<h2>5. Mevcut Log İçeriği</h2>';
    $log_content = file_get_contents($log_file);
    if (!empty($log_content)) {
        echo '<pre>' . htmlspecialchars($log_content) . '</pre>';
    } else {
        echo '<p>Log dosyası boş</p>';
    }
    echo '</div>';
}

// Test 6: Veritabanı durumu
echo '<div class="box">';
echo '<h2>6. Veritabanı Durumu</h2>';

global $xoopsDB;
$result = $xoopsDB->query("SELECT COUNT(*) as cnt FROM " . $xoopsDB->prefix('customfields_data') . " WHERE target_module='publisher'");
if ($result) {
    $row = $xoopsDB->fetchArray($result);
    echo '<p>Publisher için toplam <strong>' . $row['cnt'] . '</strong> veri var</p>';
    
    if ($row['cnt'] > 0) {
        echo '<p class="success">✅ Veritabanında veri VAR!</p>';
        
        // Son 5 veriyi göster
        $result = $xoopsDB->query("
            SELECT d.*, f.field_name 
            FROM " . $xoopsDB->prefix('customfields_data') . " d
            LEFT JOIN " . $xoopsDB->prefix('customfields_fields') . " f ON d.field_id = f.field_id
            WHERE d.target_module='publisher'
            ORDER BY d.data_id DESC 
            LIMIT 5
        ");
        
        echo '<table border="1" cellpadding="5" style="width: 100%; border-collapse: collapse;">';
        echo '<tr><th>ID</th><th>Item ID</th><th>Alan</th><th>Değer</th><th>Tarih</th></tr>';
        while ($row = $xoopsDB->fetchArray($result)) {
            echo '<tr>';
            echo '<td>' . $row['data_id'] . '</td>';
            echo '<td><strong>' . $row['item_id'] . '</strong></td>';
            echo '<td>' . $row['field_name'] . '</td>';
            echo '<td>' . htmlspecialchars(substr($row['field_value'], 0, 50)) . '</td>';
            echo '<td>' . date('Y-m-d H:i', $row['created']) . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo '<p class="error">❌ Veritabanında Publisher verisi YOK!</p>';
    }
}

echo '</div>';

echo '<div class="box info">';
echo '<h3>📝 Sonuç</h3>';
echo '<p><strong>Manuel test başarılı olursa:</strong> Sorun Publisher entegrasyonunda</p>';
echo '<p><strong>Manuel test başarısız olursa:</strong> Sorun CustomFields handler\'ında</p>';
echo '<p><strong>Gerçek Publisher testinde:</strong> Log dosyasında "Item ID: 0" görürseniz sorun bu!</p>';
echo '</div>';

echo '</body></html>';
