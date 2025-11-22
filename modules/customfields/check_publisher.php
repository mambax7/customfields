<?php
/**
 * Publisher item.php Kontrol ve Analiz
 */

include '../../mainfile.php';

echo '<html><head><meta charset="utf-8"><title>Publisher Kontrol</title></head><body>';
echo '<h2>Publisher item.php Kontrol</h2>';

$item_php = XOOPS_ROOT_PATH . '/modules/publisher/admin/item.php';

if (!file_exists($item_php)) {
    echo '<p style="color: red;">❌ Publisher item.php bulunamadı!</p>';
    echo '<p>Yol: ' . $item_php . '</p>';
    exit;
}

echo '<p style="color: green;">✅ Publisher item.php bulundu</p>';

$lines = file($item_php);
$content = implode('', $lines);

// 1. customfields_renderForm kontrolü
echo '<h3>1. customfields_renderForm() Kontrolü</h3>';
$renderForm_found = false;
$renderForm_line = 0;

foreach ($lines as $num => $line) {
    if (strpos($line, 'customfields_renderForm') !== false) {
        $renderForm_found = true;
        $renderForm_line = $num + 1;
        echo '<p style="color: green;">✅ customfields_renderForm() BULUNDU<br>';
        echo 'Satır: ' . $renderForm_line . '</p>';
        echo '<pre style="background: #f0f0f0; padding: 10px; border-left: 3px solid green;">';
        for ($i = max(0, $num-2); $i < min(count($lines), $num+3); $i++) {
            if ($i == $num) {
                echo '<strong style="color: green;">' . htmlspecialchars($lines[$i]) . '</strong>';
            } else {
                echo htmlspecialchars($lines[$i]);
            }
        }
        echo '</pre>';
        break;
    }
}

if (!$renderForm_found) {
    echo '<p style="color: red;">❌ customfields_renderForm() BULUNAMADI!</p>';
}

// 2. customfields_saveData kontrolü
echo '<h3>2. customfields_saveData() Kontrolü</h3>';
$saveData_found = false;
$saveData_line = 0;

foreach ($lines as $num => $line) {
    if (strpos($line, 'customfields_saveData') !== false) {
        $saveData_found = true;
        $saveData_line = $num + 1;
        echo '<p style="color: green;">✅ customfields_saveData() BULUNDU<br>';
        echo 'Satır: ' . $saveData_line . '</p>';
        echo '<pre style="background: #f0f0f0; padding: 10px; border-left: 3px solid green;">';
        for ($i = max(0, $num-3); $i < min(count($lines), $num+5); $i++) {
            if ($i == $num) {
                echo '<strong style="color: green;">' . htmlspecialchars($lines[$i]) . '</strong>';
            } else {
                echo htmlspecialchars($lines[$i]);
            }
        }
        echo '</pre>';
        break;
    }
}

if (!$saveData_found) {
    echo '<p style="color: red;">❌ customfields_saveData() BULUNAMADI!</p>';
}

// 3. Akış kontrolü - en kritik kısım
echo '<h3>3. Kod Akışı Analizi (EN ÖNEMLİ)</h3>';

$store_line = 0;
$first_redirect_after_store = 0;
$saveData_after_store = 0;

// store() satırını bul
foreach ($lines as $num => $line) {
    if (strpos($line, '->store()') !== false && strpos($line, 'itemObj') !== false && strpos($line, 'if') !== false) {
        $store_line = $num + 1;
        
        // Store'dan sonraki satırları kontrol et
        for ($i = $num + 1; $i < min(count($lines), $num + 30); $i++) {
            // İlk redirect'i bul
            if ($first_redirect_after_store == 0 && strpos($lines[$i], 'redirect_header') !== false) {
                $first_redirect_after_store = $i + 1;
            }
            
            // customfields_saveData'yı bul
            if ($saveData_after_store == 0 && strpos($lines[$i], 'customfields_saveData') !== false) {
                $saveData_after_store = $i + 1;
            }
            
            // İkisini de bulduk, yeter
            if ($first_redirect_after_store > 0 && $saveData_after_store > 0) {
                break;
            }
        }
        
        break;
    }
}

echo '<div style="background: #f0f0f0; padding: 15px; border-left: 4px solid #2196F3;">';
echo '<strong>Kod Akışı:</strong><br>';
echo '1️⃣ $itemObj->store() → Satır <strong>' . $store_line . '</strong><br>';
echo '2️⃣ customfields_saveData() → Satır <strong>' . $saveData_after_store . '</strong><br>';
echo '3️⃣ redirect_header() → Satır <strong>' . $first_redirect_after_store . '</strong><br>';
echo '</div>';

echo '<h3>4. SONUÇ</h3>';

if ($saveData_after_store > 0 && $first_redirect_after_store > 0) {
    if ($saveData_after_store < $first_redirect_after_store) {
        echo '<div style="background: #d4edda; padding: 20px; border-left: 5px solid #28a745; margin: 20px 0;">';
        echo '<h2 style="color: #28a745; margin-top: 0;">✅ MÜKEMMEL!</h2>';
        echo '<p><strong>customfields_saveData()</strong> (Satır ' . $saveData_after_store . ') <strong>redirect_header()</strong> (Satır ' . $first_redirect_after_store . ')\'dan <strong>ÖNCE</strong> çağrılıyor.</p>';
        echo '<p style="color: green;"><strong>Kod yapısı DOĞRU!</strong></p>';
        echo '</div>';
        
        echo '<h3>5. Sıradaki Adımlar</h3>';
        echo '<ol>';
        echo '<li>✅ Kod yapısı doğru</li>';
        echo '<li>🧪 Gerçek test yapın:</li>';
        echo '<ul>';
        echo '<li>Publisher > Makale Ekle</li>';
        echo '<li>İlave alanları doldurun</li>';
        echo '<li>Kaydedin</li>';
        echo '<li><a href="test_publisher.php">test_publisher.php</a> ile kontrol edin</li>';
        echo '</ul>';
        echo '<li>🔍 Eğer hala veri kaydedilmiyorsa:</li>';
        echo '<ul>';
        echo '<li>Error log\'u kontrol edin</li>';
        echo '<li>$savedItemId = 0 olabilir mi?</li>';
        echo '<li>POST verisi geliyor mu?</li>';
        echo '</ul>';
        echo '</ol>';
        
    } else {
        echo '<div style="background: #f8d7da; padding: 20px; border-left: 5px solid #dc3545; margin: 20px 0;">';
        echo '<h2 style="color: #dc3545; margin-top: 0;">❌ SORUN!</h2>';
        echo '<p><strong>customfields_saveData()</strong> (Satır ' . $saveData_after_store . ') <strong>redirect_header()</strong> (Satır ' . $first_redirect_after_store . ')\'dan <strong>SONRA</strong> çağrılıyor!</p>';
        echo '<p style="color: red;"><strong>Bu yüzden veri kaydedilmiyor!</strong></p>';
        echo '<p><strong>Çözüm:</strong> customfields_saveData() çağrısını yukarı, redirect_header()\'dan öncesine taşıyın.</p>';
        echo '</div>';
    }
} else {
    echo '<div style="background: #fff3cd; padding: 20px; border-left: 5px solid #ffc107;">';
    echo '<h2 style="color: #856404; margin-top: 0;">⚠️ UYARI</h2>';
    if ($saveData_after_store == 0) {
        echo '<p>customfields_saveData() store() sonrasında bulunamadı!</p>';
    }
    if ($first_redirect_after_store == 0) {
        echo '<p>redirect_header() bulunamadı!</p>';
    }
    echo '</div>';
}

// 6. Kod bloğunu göster
echo '<h3>6. Store() Sonrası Kod Bloğu</h3>';
echo '<p>Satır ' . $store_line . ' - ' . ($store_line + 25) . ' arası:</p>';
echo '<pre style="background: #f5f5f5; padding: 10px; border: 1px solid #ddd; overflow-x: auto; font-size: 12px; line-height: 1.4;">';
for ($i = $store_line - 1; $i < min(count($lines), $store_line + 24); $i++) {
    $display_num = $i + 1;
    $line_content = $lines[$i];
    
    if (strpos($line_content, 'customfields_saveData') !== false) {
        echo '<span style="background: #d4edda; font-weight: bold;">' . str_pad($display_num, 4, ' ', STR_PAD_LEFT) . '│ ' . htmlspecialchars($line_content) . '</span>';
    } elseif (strpos($line_content, 'redirect_header') !== false) {
        echo '<span style="background: #f8d7da; font-weight: bold;">' . str_pad($display_num, 4, ' ', STR_PAD_LEFT) . '│ ' . htmlspecialchars($line_content) . '</span>';
    } else {
        echo str_pad($display_num, 4, ' ', STR_PAD_LEFT) . '│ ' . htmlspecialchars($line_content);
    }
}
echo '</pre>';

echo '</body></html>';
