<?php
/**
 * Item.php CustomFields Test
 * Bu dosyayı modules/customfields/ klasörüne yükleyin
 * URL: http://siteniz.com/modules/customfields/test_item_customfields.php
 */

include '../../mainfile.php';

echo '<html><head><meta charset="utf-8"><title>Item.php Test</title></head><body>';
echo '<div style="max-width: 1200px; margin: 0 auto; padding: 20px; font-family: Arial;">';
echo '<h1>🔍 Item.php CustomFields Test</h1>';

// Test 1: item.php dosyası var mı?
echo '<div style="background: white; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">';
echo '<h2>1. Dosya Kontrolü</h2>';

$item_php = XOOPS_ROOT_PATH . '/modules/publisher/item.php';

if (!file_exists($item_php)) {
    echo '<p style="color: red; font-size: 18px;">❌ item.php bulunamadı!</p>';
    echo '<p>Beklenen konum: <code>' . $item_php . '</code></p>';
    exit;
}

echo '<p style="color: green; font-size: 18px;">✅ item.php bulundu</p>';
echo '<p>Konum: <code>' . $item_php . '</code></p>';
echo '<p>Boyut: ' . number_format(filesize($item_php)) . ' bytes</p>';
echo '<p>Son değiştirilme: ' . date('Y-m-d H:i:s', filemtime($item_php)) . '</p>';
echo '</div>';

// Test 2: CUSTOMFIELDS kodu var mı?
echo '<div style="background: white; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">';
echo '<h2>2. CUSTOMFIELDS Kodu Kontrolü</h2>';

$content = file_get_contents($item_php);
$lines = file($item_php);

if (strpos($content, 'CUSTOMFIELDS') !== false) {
    echo '<p style="color: green; font-size: 20px; font-weight: bold;">✅ CUSTOMFIELDS KODU BULUNDU!</p>';
    echo '<p style="color: green;">item.php düzeltmesi yapılmış ✓</p>';
    
    // Kodu göster
    echo '<h3>Bulunan Kod:</h3>';
    echo '<div style="background: #f5f5f5; padding: 15px; border-left: 4px solid #28a745; overflow-x: auto;">';
    echo '<pre style="margin: 0; font-size: 13px; line-height: 1.5;">';
    
    foreach ($lines as $num => $line) {
        if (stripos($line, 'CUSTOMFIELDS') !== false) {
            $start = max(0, $num - 3);
            $end = min(count($lines), $num + 12);
            
            for ($i = $start; $i < $end; $i++) {
                $line_num = $i + 1;
                $line_content = $lines[$i];
                
                if ($i == $num) {
                    echo '<span style="background: #d4edda; display: block;">';
                    echo str_pad($line_num, 4, ' ', STR_PAD_LEFT) . ' | ' . htmlspecialchars($line_content);
                    echo '</span>';
                } else {
                    echo str_pad($line_num, 4, ' ', STR_PAD_LEFT) . ' | ' . htmlspecialchars($line_content);
                }
            }
            break;
        }
    }
    
    echo '</pre>';
    echo '</div>';
    
} else {
    echo '<p style="color: red; font-size: 20px; font-weight: bold;">❌ CUSTOMFIELDS KODU BULUNAMADI!</p>';
    echo '<p style="color: red;">item.php düzeltmesi yapılmamış!</p>';
    
    echo '<div style="background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 20px 0;">';
    echo '<h3 style="margin-top: 0;">🔧 ÇÖZÜM:</h3>';
    echo '<p><strong>Seçenek 1: Yeni dosyayı yükleyin</strong></p>';
    echo '<ol>';
    echo '<li>İndirdiğiniz item.php dosyasını FTP ile yükleyin</li>';
    echo '<li>Konum: <code>modules/publisher/item.php</code></li>';
    echo '<li>Bu sayfayı yenileyin</li>';
    echo '</ol>';
    
    echo '<p><strong>Seçenek 2: Manuel düzenleme</strong></p>';
    echo '<ol>';
    echo '<li>item.php dosyasını açın</li>';
    echo '<li>Satır 254 civarını bulun: <code>unset($file, $embededFiles, $filesObj, $fileObj);</code></li>';
    echo '<li>Hemen altına şu kodu ekleyin:</li>';
    echo '</ol>';
    
    echo '<pre style="background: #f8f9fa; padding: 10px; border: 1px solid #dee2e6; overflow-x: auto;">';
    echo htmlspecialchars('
// ============================================================
// CUSTOMFIELDS - İLAVE ALANLAR
// ============================================================
// CustomFields verilerini item dizisine ekle
include_once XOOPS_ROOT_PATH . \'/modules/customfields/include/functions.php\';
if (function_exists(\'customfields_getItemData\')) {
    $item[\'customfields\'] = customfields_getItemData(\'publisher\', $itemObj->itemid());
}
// ============================================================
');
    echo '</pre>';
    echo '</div>';
}
echo '</div>';

// Test 3: Cache kontrolü
echo '<div style="background: white; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">';
echo '<h2>3. Cache Kontrolü</h2>';

$templates_c = XOOPS_ROOT_PATH . '/templates_c';
$cache_dir = XOOPS_ROOT_PATH . '/cache';

$template_files = glob($templates_c . '/*');
$cache_files = glob($cache_dir . '/*');

$template_count = is_array($template_files) ? count($template_files) : 0;
$cache_count = is_array($cache_files) ? count($cache_files) : 0;

echo '<p><strong>templates_c/</strong> dosya sayısı: <span style="font-size: 18px;">' . $template_count . '</span></p>';
echo '<p><strong>cache/</strong> dosya sayısı: <span style="font-size: 18px;">' . $cache_count . '</span></p>';

if ($template_count > 2 || $cache_count > 2) {
    echo '<div style="background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 20px 0;">';
    echo '<p style="color: #856404; font-size: 18px; font-weight: bold;">⚠️ CACHE TEMİZLENMELİ!</p>';
    echo '<p>item.php düzeltmesinin çalışması için cache temizlenmelidir.</p>';
    echo '<p><strong>ÇÖZÜM:</strong></p>';
    echo '<ol>';
    echo '<li><a href="' . XOOPS_URL . '/admin.php" target="_blank">XOOPS Admin Panel</a> > Sistem > Genel Ayarlar</li>';
    echo '<li>"Cache\'i Temizle" butonuna basın</li>';
    echo '<li>Ardından "Template\'leri Güncelle"</li>';
    echo '<li>Bu sayfayı yenileyin</li>';
    echo '</ol>';
    echo '</div>';
} else {
    echo '<p style="color: green; font-size: 18px;">✅ Cache temiz</p>';
}
echo '</div>';

// Test 4: CustomFields fonksiyon test
echo '<div style="background: white; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">';
echo '<h2>4. CustomFields Fonksiyon Testi</h2>';

include_once XOOPS_ROOT_PATH . '/modules/customfields/include/functions.php';

if (function_exists('customfields_getItemData')) {
    echo '<p style="color: green; font-size: 18px;">✅ customfields_getItemData() fonksiyonu mevcut</p>';
    
    // Item 23 için test
    $itemid = 23;
    echo '<p>Item ID ' . $itemid . ' için test ediliyor...</p>';
    
    $data = customfields_getItemData('publisher', $itemid);
    
    if ($data && count($data) > 0) {
        echo '<p style="color: green;">✅ Veri bulundu!</p>';
        echo '<pre style="background: #f8f9fa; padding: 10px; border: 1px solid #dee2e6;">';
        print_r($data);
        echo '</pre>';
    } else {
        echo '<p style="color: orange;">⚠️ Item ' . $itemid . ' için customfields verisi yok</p>';
        echo '<p>Bu makale için ilave alanları doldurun.</p>';
    }
    
} else {
    echo '<p style="color: red;">❌ customfields_getItemData() fonksiyonu bulunamadı!</p>';
}
echo '</div>';

// Test 5: Özet
echo '<div style="background: white; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">';
echo '<h2>📊 ÖZET VE SONUÇ</h2>';

$all_ok = true;
$issues = [];

if (strpos($content, 'CUSTOMFIELDS') === false) {
    $all_ok = false;
    $issues[] = 'item.php düzeltmesi yapılmamış';
}

if ($template_count > 2 || $cache_count > 2) {
    $all_ok = false;
    $issues[] = 'Cache temizlenmemiş';
}

if ($all_ok) {
    echo '<div style="background: #d4edda; padding: 20px; border-left: 4px solid #28a745;">';
    echo '<h3 style="color: #155724; margin-top: 0;">✅ HER ŞEY TAMAM!</h3>';
    echo '<p>item.php düzeltmesi yapılmış ve cache temiz.</p>';
    echo '<p><strong>ŞİMDİ NE YAPACAĞIZ?</strong></p>';
    echo '<ol>';
    echo '<li>Bir makale sayfasını açın (örn: Item ID 23)</li>';
    echo '<li>Debug kutusunda "CustomFields var mı? ✅ VAR" görmelisiniz</li>';
    echo '<li>Eğer hala görmüyorsanız, tarayıcı cache\'ini temizleyin (Ctrl+F5)</li>';
    echo '</ol>';
    echo '</div>';
} else {
    echo '<div style="background: #f8d7da; padding: 20px; border-left: 4px solid #dc3545;">';
    echo '<h3 style="color: #721c24; margin-top: 0;">❌ SORUNLAR VAR!</h3>';
    echo '<ul>';
    foreach ($issues as $issue) {
        echo '<li style="color: #721c24; font-size: 16px;">' . $issue . '</li>';
    }
    echo '</ul>';
    echo '<p><strong>YAPILACAKLAR:</strong></p>';
    echo '<ol>';
    if (strpos($content, 'CUSTOMFIELDS') === false) {
        echo '<li>Yeni item.php dosyasını yükleyin VEYA manuel olarak kodu ekleyin</li>';
    }
    if ($template_count > 2 || $cache_count > 2) {
        echo '<li>XOOPS Admin > Sistem > Cache\'i Temizle</li>';
    }
    echo '<li>Bu sayfayı yenileyin</li>';
    echo '</ol>';
    echo '</div>';
}

echo '</div>';

echo '</div></body></html>';
