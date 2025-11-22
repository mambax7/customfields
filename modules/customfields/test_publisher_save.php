<?php
/**
 * Publisher Entegrasyon Test
 * Bu dosyayı modules/customfields/ klasörüne koyun
 * URL: http://siteniz.com/modules/customfields/test_publisher_save.php
 */

include '../../mainfile.php';

echo '<h2>Publisher Kaydetme Testi</h2>';

// Manuel POST verisi simüle et
$_POST['customfield_1'] = 'Test Başlık - ' . date('H:i:s');
$_POST['customfield_2'] = 'Test Resim Verisi';

echo '<h3>1. POST Verisi (Simülasyon)</h3>';
echo '<pre>';
print_r($_POST);
echo '</pre>';

// CustomFields functions dahil et
include_once XOOPS_ROOT_PATH . '/modules/customfields/include/functions.php';

echo '<h3>2. customfields_saveData() Çağrısı</h3>';

// Test item ID
$test_item_id = 999;

echo 'Kaydediliyor: module=publisher, item_id=' . $test_item_id . '<br>';

// Kaydet
$result = customfields_saveData('publisher', $test_item_id);

echo 'Sonuç: ' . ($result ? '✅ TRUE' : '❌ FALSE') . '<br>';

// Kontrol et
echo '<h3>3. Veritabanı Kontrolü</h3>';
global $xoopsDB;

$table = $xoopsDB->prefix('customfields_data');
$query = "SELECT * FROM $table WHERE target_module='publisher' AND item_id=$test_item_id";
echo 'SQL: ' . $query . '<br><br>';

$result = $xoopsDB->query($query);
if ($result && $xoopsDB->getRowsNum($result) > 0) {
    echo '✅ Veri bulundu:<br>';
    echo '<table border="1" cellpadding="5" style="border-collapse: collapse;">';
    echo '<tr style="background: #48bb78; color: white;">';
    echo '<th>data_id</th><th>field_id</th><th>item_id</th><th>field_value</th><th>created</th>';
    echo '</tr>';
    while ($row = $xoopsDB->fetchArray($result)) {
        echo '<tr>';
        echo '<td>' . $row['data_id'] . '</td>';
        echo '<td>' . $row['field_id'] . '</td>';
        echo '<td>' . $row['item_id'] . '</td>';
        echo '<td><strong>' . htmlspecialchars($row['field_value']) . '</strong></td>';
        echo '<td>' . date('Y-m-d H:i:s', $row['created']) . '</td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo '❌ Veri kaydedilemedi!<br>';
    echo '<p style="color: red;">Muhtemelen handler veya veritabanı sorunu var.</p>';
}

// Error log'dan son satırları göster
echo '<h3>4. Son Error Log Kayıtları</h3>';
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
                echo '<span style="color: blue;">' . htmlspecialchars($line) . '</span>';
            } elseif (stripos($line, 'ERROR') !== false) {
                echo '<span style="color: red;">' . htmlspecialchars($line) . '</span>';
            } else {
                echo htmlspecialchars($line);
            }
        }
        echo '</pre>';
    } else {
        echo '<p>CustomFields ile ilgili log kaydı bulunamadı.</p>';
    }
} else {
    echo '<p>Error log dosyası bulunamadı: ' . $error_log . '</p>';
}

echo '<hr>';
echo '<h3>SONRAKİ ADIMLAR</h3>';
echo '<ol>';
echo '<li>Bu sayfayı birkaç kez yenileyin (F5)</li>';
echo '<li>Her seferinde yeni veri eklenmeli</li>';
echo '<li>Error log\'da "CustomFields saveData called" görünmeli</li>';
echo '<li>Eğer çalışıyorsa, Publisher entegrasyonunu kontrol edin</li>';
echo '</ol>';

echo '<p><a href="test_publisher.php">← Ana Test Sayfasına Dön</a></p>';
?>