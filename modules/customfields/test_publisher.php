<?php
/**
 * CustomFields Publisher Test & Debug Tool
 */

include '../../mainfile.php';

echo '<html><head><meta charset="utf-8"><title>CustomFields Test</title></head><body>';
echo '<h2>CustomFields Publisher Test</h2>';

// 1. CustomFields modülü kurulu mu?
echo '<h3>1. Modül Kontrolü</h3>';
if (file_exists(XOOPS_ROOT_PATH . '/modules/customfields/include/functions.php')) {
    echo '✅ CustomFields modülü bulundu<br>';
    include_once XOOPS_ROOT_PATH . '/modules/customfields/include/functions.php';
} else {
    echo '❌ CustomFields modülü bulunamadı!<br>';
    exit;
}

// 2. Publisher alanları var mı?
echo '<h3>2. Publisher Alanları</h3>';
$fields = customfields_getFields('publisher');
if (count($fields) > 0) {
    echo '✅ ' . count($fields) . ' alan bulundu:<br>';
    echo '<ul>';
    foreach ($fields as $field) {
        echo '<li>ID: ' . $field->getVar('field_id') . ', Ad: <strong>' . $field->getVar('field_name') . '</strong>, Başlık: ' . $field->getVar('field_title') . '</li>';
    }
    echo '</ul>';
} else {
    echo '❌ Publisher için alan bulunamadı! Önce alan oluşturun.<br>';
}

// 3. Veritabanı tabloları var mı?
echo '<h3>3. Veritabanı Tabloları</h3>';
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
        echo '✅ ' . $table_fields . ' tablosu var<br>';
    } else {
        echo '❌ ' . $table_fields . ' tablosu YOK!<br>';
    }
    
    if (in_array($table_data, $tables)) {
        echo '✅ ' . $table_data . ' tablosu var<br>';
        
        // Toplam veri sayısı
        $result = $xoopsDB->query("SELECT COUNT(*) as cnt FROM " . $table_data);
        if ($result) {
            $row = $xoopsDB->fetchArray($result);
            echo '📊 Toplam <strong>' . $row['cnt'] . '</strong> veri kaydı var<br>';
        }
        
        // Publisher verisi
        $result = $xoopsDB->query("SELECT COUNT(*) as cnt FROM " . $table_data . " WHERE target_module='publisher'");
        if ($result) {
            $row = $xoopsDB->fetchArray($result);
            echo '📊 Publisher için <strong>' . $row['cnt'] . '</strong> veri kaydı var<br>';
        }
    } else {
        echo '❌ ' . $table_data . ' tablosu YOK!<br>';
    }
} catch (Exception $e) {
    echo '❌ Veritabanı hatası: ' . $e->getMessage() . '<br>';
}

// 4. Son 10 Publisher verisini göster
echo '<h3>4. Son Publisher Kayıtları</h3>';
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
        echo '<th>ID</th><th>Item ID</th><th>Alan</th><th>Değer</th><th>Tarih</th>';
        echo '</tr>';
        while ($row = $xoopsDB->fetchArray($result)) {
            echo '<tr>';
            echo '<td>' . $row['data_id'] . '</td>';
            echo '<td>' . $row['item_id'] . '</td>';
            echo '<td>' . $row['field_name'] . ' (' . $row['field_title'] . ')</td>';
            echo '<td>' . htmlspecialchars(substr($row['field_value'], 0, 50)) . '</td>';
            echo '<td>' . date('Y-m-d H:i', $row['created']) . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo '❌ Henüz Publisher verisi yok<br>';
        echo '<p style="color: red;">BU SORUN! Makale ekleyip kaydederken veri kaydedilmiyor.</p>';
    }
} catch (Exception $e) {
    echo '❌ Sorgu hatası: ' . $e->getMessage() . '<br>';
}

// 5. Test kaydetme
echo '<h3>5. Manuel Test Kaydetme</h3>';
if (isset($_POST['test_save']) && count($fields) > 0) {
    echo '<div style="background: #ffffcc; padding: 10px; margin: 10px 0;">';
    echo '<strong>Test başlatılıyor...</strong><br>';
    
    $test_item_id = 9999; // Test item ID
    $dataHandler = customfields_getDataHandler();
    
    $success_count = 0;
    foreach ($fields as $field) {
        $field_id = $field->getVar('field_id');
        $test_value = 'Test değeri: ' . date('Y-m-d H:i:s');
        
        echo 'Alan ID ' . $field_id . ' (' . $field->getVar('field_name') . ') kaydediliyor... ';
        
        $result = $dataHandler->saveItemData('publisher', $test_item_id, $field_id, $test_value);
        
        if ($result) {
            echo '<span style="color: green;">✓ Başarılı</span><br>';
            $success_count++;
        } else {
            echo '<span style="color: red;">✗ Başarısız</span><br>';
        }
    }
    
    echo '<br><strong>Sonuç:</strong> ' . $success_count . '/' . count($fields) . ' alan kaydedildi<br>';
    echo '<a href="test_publisher.php">Sayfayı yenile ve kontrol et</a>';
    echo '</div>';
}

if (count($fields) > 0) {
    echo '<form method="post">';
    echo '<button type="submit" name="test_save" style="padding: 10px 20px; background: #4CAF50; color: white; border: none; cursor: pointer; font-size: 16px;">🧪 Manuel Test Yap (Item ID: 9999)</button>';
    echo '</form>';
    echo '<p><small>Bu buton test amaçlı item ID 9999 ile veri kaydeder. Publisher\'da gerçek makale ile test etmeniz gerekir.</small></p>';
}

// 6. PHP Error Log
echo '<h3>6. Error Log Kontrol</h3>';
$error_log = ini_get('error_log');
if ($error_log && file_exists($error_log)) {
    echo '📝 Error log: <code>' . $error_log . '</code><br>';
    echo '<a href="?show_log=1">Son 100 satırı göster (CustomFields ile filtrelenmemiş)</a><br>';
    
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
                echo '<h4>CustomFields ile ilgili loglar (' . count($filtered) . ' satır):</h4>';
                echo '<pre style="background:#f5f5f5; padding:10px; max-height:400px; overflow:auto; font-size: 11px;">';
                echo htmlspecialchars(implode('', $filtered));
                echo '</pre>';
            } else {
                echo '<p style="color: orange;">⚠️ Son 100 satırda CustomFields ile ilgili log bulunamadı.</p>';
                echo '<p>Bu normal olabilir. Publisher\'da makale ekleyip kaydedin, sonra tekrar kontrol edin.</p>';
            }
        } else {
            echo '<p style="color: red;">❌ Error log okunamadı.</p>';
        }
    }
} else {
    echo '⚠️ Error log bulunamadı veya ayarlanmamış<br>';
    echo 'php.ini\'de error_log ayarını kontrol edin.<br>';
}

// 7. Publisher item.php kontrol
echo '<h3>7. Publisher Entegrasyon Kontrol</h3>';
$publisher_item = XOOPS_ROOT_PATH . '/modules/publisher/admin/item.php';
if (file_exists($publisher_item)) {
    echo '✅ Publisher item.php bulundu<br>';
    
    $content = file_get_contents($publisher_item);
    
    // customfields_saveData kontrolü
    if (strpos($content, 'customfields_saveData') !== false) {
        echo '✅ <strong>customfields_saveData()</strong> çağrısı VAR<br>';
        
        // Satır numarasını bul
        $lines = explode("\n", $content);
        foreach ($lines as $num => $line) {
            if (strpos($line, 'customfields_saveData') !== false) {
                $line_num = $num + 1;
                echo '📍 Satır <strong>' . $line_num . '</strong>: <code>' . htmlspecialchars(trim($line)) . '</code><br>';
            }
        }
    } else {
        echo '❌ <strong>customfields_saveData()</strong> çağrısı YOK!<br>';
        echo '<p style="color: red; background: #fee; padding: 10px;">SORUN BULUNDU! Publisher item.php\'ye entegrasyon kodu eklenmemiş.</p>';
    }
    
    // customfields_renderForm kontrolü
    if (strpos($content, 'customfields_renderForm') !== false) {
        echo '✅ <strong>customfields_renderForm()</strong> çağrısı VAR<br>';
    } else {
        echo '⚠️ <strong>customfields_renderForm()</strong> çağrısı YOK (Form gösterilmiyor olabilir)<br>';
    }
    
} else {
    echo '❌ Publisher item.php bulunamadı!<br>';
}

echo '<hr>';
echo '<h3>📋 Sonraki Adımlar</h3>';
echo '<ol>';
echo '<li>✅ Eğer "Manuel Test Yap" butonu başarılı olursa → Handler çalışıyor demektir</li>';
echo '<li>✅ Eğer Publisher entegrasyon kontrolü "VAR" gösteriyorsa → Kod doğru yerde</li>';
echo '<li>❌ Eğer Publisher verisi "0" gösteriyorsa → item.php\'deki kod çalışmıyor demektir</li>';
echo '<li>🔍 Publisher\'da makale ekleyin → Error log\'u kontrol edin → DEBUG satırlarını arayın</li>';
echo '<li>📝 <code>DEBUG: Publisher itemid = X</code> satırını arayın</li>';
echo '</ol>';

echo '<div style="background: #e7f3ff; padding: 15px; margin: 20px 0; border-left: 4px solid #2196F3;">';
echo '<h4 style="margin-top: 0;">💡 Hızlı Test</h4>';
echo '<ol>';
echo '<li>"Manuel Test Yap" butonuna basın</li>';
echo '<li>Sayfayı yenileyin</li>';
echo '<li>"Son Publisher Kayıtları" bölümünde Item ID 9999 görmeli</li>';
echo '<li>Eğer görüyorsanız → Sorun Publisher entegrasyonunda</li>';
echo '<li>Eğer görmüyorsanız → Sorun Handler\'da</li>';
echo '</ol>';
echo '</div>';

echo '</body></html>';
