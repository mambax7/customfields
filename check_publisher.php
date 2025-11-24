<?php
/**
 * Publisher item.php Kontrol ve Analiz
 */

require __DIR__ . '/header.php';
require_once __DIR__ . '/include/functions.php';

if (!customfields_isAdminUser()) {
    header('HTTP/1.1 403 Forbidden');
    echo '<!doctype html><meta charset="utf-8"><title>Forbidden</title><p>Access denied.</p>';
    exit;
}


echo '<html><head><meta charset="utf-8"><title>' . _MD_CUSTOMFIELDS_PUB_CHECK_PAGE_TITLE . '</title></head><body>';
echo '<h2>' . _MD_CUSTOMFIELDS_PUB_ITEM_CHECK . '</h2>';

$item_php = XOOPS_ROOT_PATH . '/modules/publisher/admin/item.php';

if (!file_exists($item_php)) {
    echo '<p style="color: red;">❌ ' . _MD_CUSTOMFIELDS_FILE_NOT_FOUND . '</p>';
    echo '<p>' . _MD_CUSTOMFIELDS_FILE_PATH . ': ' . htmlspecialchars($item_php, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</p>';
    exit;
}

echo '<p style="color: green;">✅ ' . _MD_CUSTOMFIELDS_FILE_FOUND . '</p>';

$lines = file($item_php);
$content = implode('', $lines);

// 1. customfields_renderForm kontrolü
echo '<h3>' . _MD_CUSTOMFIELDS_SECTION_RENDERFORM . '</h3>';
$renderForm_found = false;
$renderForm_line = 0;

foreach ($lines as $num => $line) {
    if (strpos($line, 'customfields_renderForm') !== false) {
        $renderForm_found = true;
        $renderForm_line = $num + 1;
        echo '<p style="color: green;">✅ customfields_renderForm() ' . _MD_CUSTOMFIELDS_FILE_FOUND . '<br>';
        echo sprintf(_MD_CUSTOMFIELDS_FOUND_AT_LINE, (int)$renderForm_line) . '</p>';
        echo '<pre style="background: #f0f0f0; padding: 10px; border-left: 3px solid green;">';
        for ($i = max(0, $num-2); $i < min(count($lines), $num+3); $i++) {
            if ($i == $num) {
                echo '<strong style="color: green;">' . htmlspecialchars($lines[$i], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</strong>';
            } else {
                echo htmlspecialchars($lines[$i], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            }
        }
        echo '</pre>';
        break;
    }
}

if (!$renderForm_found) {
    echo '<p style="color: red;">❌ customfields_renderForm() ' . _MD_CUSTOMFIELDS_NOT_FOUND . '</p>';
}

// 2. customfields_saveData kontrolü
echo '<h3>' . _MD_CUSTOMFIELDS_SECTION_SAVEDATA . '</h3>';
$saveData_found = false;
$saveData_line = 0;

foreach ($lines as $num => $line) {
    if (strpos($line, 'customfields_saveData') !== false) {
        $saveData_found = true;
        $saveData_line = $num + 1;
        echo '<p style="color: green;">✅ customfields_saveData() ' . _MD_CUSTOMFIELDS_FILE_FOUND . '<br>';
        echo sprintf(_MD_CUSTOMFIELDS_FOUND_AT_LINE, (int)$saveData_line) . '</p>';
        echo '<pre style="background: #f0f0f0; padding: 10px; border-left: 3px solid green;">';
        for ($i = max(0, $num-3); $i < min(count($lines), $num+5); $i++) {
            if ($i == $num) {
                echo '<strong style="color: green;">' . htmlspecialchars($lines[$i], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</strong>';
            } else {
                echo htmlspecialchars($lines[$i], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            }
        }
        echo '</pre>';
        break;
    }
}

if (!$saveData_found) {
    echo '<p style="color: red;">❌ customfields_saveData() ' . _MD_CUSTOMFIELDS_NOT_FOUND . '</p>';
}

// 3. Akış kontrolü - en kritik kısım
echo '<h3>' . _MD_CUSTOMFIELDS_FLOW_ANALYSIS . '</h3>';

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
echo '1️⃣ ' . sprintf(_MD_CUSTOMFIELDS_FLOW_STEP1, (int)$store_line) . '<br>';
echo '2️⃣ ' . sprintf(_MD_CUSTOMFIELDS_FLOW_STEP2, (int)$saveData_after_store) . '<br>';
echo '3️⃣ ' . sprintf(_MD_CUSTOMFIELDS_FLOW_STEP3, (int)$first_redirect_after_store) . '<br>';
echo '</div>';

echo '<h3>4. ' . _MD_CUSTOMFIELDS_RESULT . '</h3>';

if ($saveData_after_store > 0 && $first_redirect_after_store > 0) {
    if ($saveData_after_store < $first_redirect_after_store) {
        echo '<div style="background: #d4edda; padding: 20px; border-left: 5px solid #28a745; margin: 20px 0;">';
        echo '<h2 style="color: #28a745; margin-top: 0;">✅ ' . _MD_CUSTOMFIELDS_RESULT_OK_TITLE . '</h2>';
        echo '<p>' . _MD_CUSTOMFIELDS_RESULT_OK_MSG . '</p>';
        echo '</div>';
        
        echo '<h3>5. ' . _MD_CUSTOMFIELDS_NEXT_STEPS . '</h3>';
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
        echo '<h2 style="color: #dc3545; margin-top: 0;">❌ ' . _MD_CUSTOMFIELDS_RESULT_BAD_TITLE . '</h2>';
        echo '<p>' . _MD_CUSTOMFIELDS_RESULT_BAD_MSG . '</p>';
        echo '</div>';
    }
} else {
    echo '<div style="background: #fff3cd; padding: 20px; border-left: 5px solid #ffc107;">';
    echo '<h2 style="color: #856404; margin-top: 0;">⚠️ ' . _MD_CUSTOMFIELDS_RESULT . '</h2>';
    if ($saveData_after_store == 0) {
        echo '<p>' . _MD_CUSTOMFIELDS_NO_SAVE_FOUND . '</p>';
    }
    if ($first_redirect_after_store == 0) {
        echo '<p>' . _MD_CUSTOMFIELDS_NO_REDIRECT_FOUND . '</p>';
    }
    echo '</div>';
}

// 6. Kod bloğunu göster
echo '<h3>6. ' . _MD_CUSTOMFIELDS_CODE_BLOCK_AFTER_STORE . '</h3>';
echo '<p>' . sprintf('Satır %d - %d arası:', (int)$store_line, (int)($store_line + 25)) . '</p>';
echo '<pre style="background: #f5f5f5; padding: 10px; border: 1px solid #ddd; overflow-x: auto; font-size: 12px; line-height: 1.4;">';
for ($i = $store_line - 1; $i < min(count($lines), $store_line + 24); $i++) {
    $display_num = $i + 1;
    $line_content = $lines[$i];
    
    if (strpos($line_content, 'customfields_saveData') !== false) {
        echo '<span style="background: #d4edda; font-weight: bold;">' . str_pad($display_num, 4, ' ', STR_PAD_LEFT) . '│ ' . htmlspecialchars($line_content, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</span>';
    } elseif (strpos($line_content, 'redirect_header') !== false) {
        echo '<span style="background: #f8d7da; font-weight: bold;">' . str_pad($display_num, 4, ' ', STR_PAD_LEFT) . '│ ' . htmlspecialchars($line_content, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</span>';
    } else {
        echo str_pad($display_num, 4, ' ', STR_PAD_LEFT) . '│ ' . htmlspecialchars($line_content, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
echo '</pre>';

echo '</body></html>';
