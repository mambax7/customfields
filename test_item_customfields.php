<?php
/**
 * Item.php CustomFields Test
 * Bu dosyayı modules/customfields/ klasörüne yükleyin
 * URL: http://siteniz.com/modules/customfields/test_item_customfields.php
 */

require __DIR__ . '/header.php';

echo '<html><head><meta charset="utf-8"><title>' . _MD_CUSTOMFIELDS_ITEM_TEST_TITLE . '</title></head><body>';
echo '<div style="max-width: 1200px; margin: 0 auto; padding: 20px; font-family: Arial;">';
echo '<h1>🔍 ' . _MD_CUSTOMFIELDS_ITEM_TEST_TITLE . '</h1>';

// Test 1: item.php dosyası var mı?
echo '<div style="background: white; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">';
echo '<h2>' . _MD_CUSTOMFIELDS_FILE_CHECK_TITLE . '</h2>';

$item_php = XOOPS_ROOT_PATH . '/modules/publisher/item.php';

if (!file_exists($item_php)) {
    echo '<p style="color: red; font-size: 18px;">❌ ' . _MD_CUSTOMFIELDS_FILE_NOT_FOUND . '</p>';
    echo '<p>' . _MD_CUSTOMFIELDS_FILE_LOCATION . ': <code>' . htmlspecialchars($item_php, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</code></p>';
    exit;
}

echo '<p style="color: green; font-size: 18px;">✅ ' . _MD_CUSTOMFIELDS_FILE_FOUND . '</p>';
echo '<p>' . _MD_CUSTOMFIELDS_FILE_LOCATION . ': <code>' . htmlspecialchars($item_php, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</code></p>';
echo '<p>' . _MD_CUSTOMFIELDS_FILE_SIZE . ': ' . number_format(filesize($item_php)) . ' bytes</p>';
echo '<p>' . _MD_CUSTOMFIELDS_FILE_MTIME . ': ' . date('Y-m-d H:i:s', filemtime($item_php)) . '</p>';
echo '</div>';

// Test 2: CUSTOMFIELDS kodu var mı?
echo '<div style="background: white; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">';
echo '<h2>' . _MD_CUSTOMFIELDS_CUSTOMFIELDS_CODE_CHECK . '</h2>';

$content = file_get_contents($item_php);
$lines   = file($item_php);

if (strpos($content, 'CUSTOMFIELDS') !== false) {
    echo '<p style="color: green; font-size: 20px; font-weight: bold;">✅ ' . _MD_CUSTOMFIELDS_CUSTOMFIELDS_FOUND . '</p>';
    echo '<p style="color: green;">' . _MD_CUSTOMFIELDS_MODULE_FOUND . ' ✓</p>';

    // Kodu göster
    echo '<h3>' . _MD_CUSTOMFIELDS_FOUND_CODE . '</h3>';
    echo '<div style="background: #f5f5f5; padding: 15px; border-left: 4px solid #28a745; overflow-x: auto;">';
    echo '<pre style="margin: 0; font-size: 13px; line-height: 1.5;">';

    foreach ($lines as $num => $line) {
        if (stripos($line, 'CUSTOMFIELDS') !== false) {
            $start = max(0, $num - 3);
            $end   = min(count($lines), $num + 12);

            for ($i = $start; $i < $end; $i++) {
                $line_num     = $i + 1;
                $line_content = $lines[$i];

                if ($i == $num) {
                    echo '<span style="background: #d4edda; display: block;">';
                    echo str_pad($line_num, 4, ' ', STR_PAD_LEFT) . ' | ' . htmlspecialchars($line_content, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    echo '</span>';
                } else {
                    echo str_pad($line_num, 4, ' ', STR_PAD_LEFT) . ' | ' . htmlspecialchars($line_content, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                }
            }
            break;
        }
    }

    echo '</pre>';
    echo '</div>';

} else {
    echo '<p style="color: red; font-size: 20px; font-weight: bold;">❌ ' . _MD_CUSTOMFIELDS_CUSTOMFIELDS_NOT_FOUND . '</p>';
    echo '<p style="color: red;">' . _MD_CUSTOMFIELDS_MODULE_NOT_FOUND . '</p>';

    echo '<div style="background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 20px 0;">';
    echo '<h3 style="margin-top: 0;">🔧 ' . _MD_CUSTOMFIELDS_SOLUTIONS . ':</h3>';
    echo '<p><strong>' . _MD_CUSTOMFIELDS_SOLUTION_UPLOAD . '</strong></p>';
    echo '<ol>';
    echo '<li>' . _MD_CUSTOMFIELDS_UPLOAD_INSTR_1 . '</li>';
    echo '<li>' . _MD_CUSTOMFIELDS_UPLOAD_INSTR_2 . '</li>';
    echo '<li>' . _MD_CUSTOMFIELDS_UPLOAD_INSTR_3 . '</li>';
    echo '</ol>';

    echo '<p><strong>' . _MD_CUSTOMFIELDS_SOLUTION_MANUAL . '</strong></p>';
    echo '<ol>';
    echo '<li>' . _MD_CUSTOMFIELDS_MANUAL_INSTR_1 . '</li>';
    echo '<li>' . _MD_CUSTOMFIELDS_MANUAL_INSTR_2 . '</li>';
    echo '<li>' . _MD_CUSTOMFIELDS_MANUAL_INSTR_3 . '</li>';
    echo '</ol>';

    // Kod örneği – metinler dil sabitlerinden
    $snippet = '
// ============================================================
// CUSTOMFIELDS - ' . _MD_CUSTOMFIELDS_SNIPPET_HEADER . '
// ============================================================
// ' . _MD_CUSTOMFIELDS_SNIPPET_COMMENT . '
include_once XOOPS_ROOT_PATH . \'/modules/customfields/include/functions.php\';
if (function_exists(\'customfields_getItemData\')) {
    $item[\'customfields\'] = customfields_getItemData(\'publisher\', $itemObj->itemid());
}
// ============================================================
';
    echo '<pre style="background: #f8f9fa; padding: 10px; border: 1px solid #dee2e6; overflow-x: auto;">';
    echo htmlspecialchars($snippet, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    echo '</pre>';
    echo '</div>';
}
echo '</div>';

// Test 3: Cache kontrolü
echo '<div style="background: white; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">';
echo '<h2>' . _MD_CUSTOMFIELDS_CACHE_CHECK_TITLE . '</h2>';

$templates_c = XOOPS_ROOT_PATH . '/templates_c';
$cache_dir   = XOOPS_ROOT_PATH . '/cache';

$template_files = glob($templates_c . '/*');
$cache_files    = glob($cache_dir . '/*');

$template_count = is_array($template_files) ? count($template_files) : 0;
$cache_count    = is_array($cache_files) ? count($cache_files) : 0;

// Use your existing label constants
echo '<p><strong>' . _MD_CUSTOMFIELDS_TEMPLATES_C_COUNT . '</strong>: <span style="font-size: 18px;">' . $template_count . '</span></p>';
echo '<p><strong>' . _MD_CUSTOMFIELDS_CACHE_DIR_COUNT . '</strong>: <span style="font-size: 18px;">' . $cache_count . '</span></p>';

if ($template_count > 2 || $cache_count > 2) {
    echo '<div style="background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 20px 0;">';
    echo '<p style="color: #856404; font-size: 18px; font-weight: bold;">⚠️ ' . _MD_CUSTOMFIELDS_CACHE_NEEDS_CLEAR . '</p>';
    echo '<p>' . _MD_CUSTOMFIELDS_CACHE_FIX . '</p>';
    echo '<p><strong>' . _MD_CUSTOMFIELDS_SOLUTION . '</strong></p>';
    echo '<ol>';
    echo '<li><a href="' . XOOPS_URL . '/admin.php" target="_blank">' . _MD_CUSTOMFIELDS_ADMIN_PANEL_PATH . '</a></li>';
    echo '<li>' . _MD_CUSTOMFIELDS_CLEAR_CACHE_BUTTON . '</li>';
    echo '<li>' . _MD_CUSTOMFIELDS_UPDATE_TEMPLATES . '</li>';
    echo '<li>' . _MD_CUSTOMFIELDS_REFRESH_PAGE . '</li>';
    echo '</ol>';
    echo '</div>';
} else {
    echo '<p style="color: green; font-size: 18px;">✅ ' . _MD_CUSTOMFIELDS_CACHE_CLEAN . '</p>';
}
echo '</div>';

// Test 4: CustomFields fonksiyon test
echo '<div style="background: white; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">';
echo '<h2>' . _MD_CUSTOMFIELDS_CF_FUNC_TEST_TITLE . '</h2>';

include_once XOOPS_ROOT_PATH . '/modules/customfields/include/functions.php';

if (function_exists('customfields_getItemData')) {
    echo '<p style="color: green; font-size: 18px;">✅ ' . _MD_CUSTOMFIELDS_CF_FUNC_EXISTS . '</p>';

    // Item 23 için test
    $itemid = 23;
    echo '<p>' . sprintf(_MD_CUSTOMFIELDS_TESTING_ITEM, $itemid) . '</p>';

    $data = customfields_getItemData('publisher', $itemid);

    if ($data && count($data) > 0) {
        echo '<p style="color: green;">✅ ' . _MD_CUSTOMFIELDS_DATA_FOUND_GENERIC . '</p>';
        echo '<pre style="background: #f8f9fa; padding: 10px; border: 1px solid #dee2e6;">';
        print_r($data);
        echo '</pre>';
    } else {
        echo '<p style="color: orange;">⚠️ ' . sprintf(_MD_CUSTOMFIELDS_NO_DATA_FOR_ITEM, $itemid) . '</p>';
        echo '<p>' . _MD_CUSTOMFIELDS_FILL_FIELDS_FOR_ARTICLE . '</p>';
    }

} else {
    echo '<p style="color: red;">❌ ' . _MD_CUSTOMFIELDS_CF_FUNC_MISSING . '</p>';
}
echo '</div>';

// Test 5: Özet
echo '<div style="background: white; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">';
echo '<h2>' . _MD_CUSTOMFIELDS_SUMMARY_TITLE . '</h2>';

$all_ok = true;
$issues = [];

if (strpos($content, 'CUSTOMFIELDS') === false) {
    $all_ok   = false;
    $issues[] = _MD_CUSTOMFIELDS_ISSUE_ITEM_FIX_MISSING;
}

if ($template_count > 2 || $cache_count > 2) {
    $all_ok   = false;
    $issues[] = _MD_CUSTOMFIELDS_ISSUE_CACHE_NOT_CLEARED;
}

if ($all_ok) {
    echo '<div style="background: #d4edda; padding: 20px; border-left: 4px solid #28a745;">';
    echo '<h3 style="color: #155724; margin-top: 0;">✅ ' . _MD_CUSTOMFIELDS_EVERYTHING_OK . '</h3>';
    echo '<p>' . _MD_CUSTOMFIELDS_ITEM_FIX_AND_CACHE_OK . '</p>';
    echo '<p><strong>' . _MD_CUSTOMFIELDS_WHATS_NEXT . '</strong></p>';
    echo '<ol>';
    echo '<li>' . _MD_CUSTOMFIELDS_WHATS_NEXT_STEP_OPEN_ARTICLE . '</li>';
    echo '<li>' . _MD_CUSTOMFIELDS_WHATS_NEXT_STEP_SEE_CF_PRESENT . '</li>';
    echo '<li>' . _MD_CUSTOMFIELDS_WHATS_NEXT_STEP_CLEAR_BROWSER . '</li>';
    echo '</ol>';
    echo '</div>';
} else {
    echo '<div style="background: #f8d7da; padding: 20px; border-left: 4px solid #dc3545;">';
    echo '<h3 style="color: #721c24; margin-top: 0;">❌ ' . _MD_CUSTOMFIELDS_ISSUES_TITLE . '</h3>';
    echo '<ul>';
    foreach ($issues as $issue) {
        echo '<li style="color: #721c24; font-size: 16px;">' . htmlspecialchars($issue, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</li>';
    }
    echo '</ul>';
    echo '<p><strong>' . _MD_CUSTOMFIELDS_TODO_TITLE . ':</strong></p>';
    echo '<ol>';
    if (strpos($content, 'CUSTOMFIELDS') === false) {
        echo '<li>' . _MD_CUSTOMFIELDS_TODO_UPLOAD_OR_MANUAL . '</li>';
    }
    if ($template_count > 2 || $cache_count > 2) {
        echo '<li>' . _MD_CUSTOMFIELDS_TODO_CLEAR_CACHE . '</li>';
    }
    echo '<li>' . _MD_CUSTOMFIELDS_REFRESH_PAGE . '</li>';
    echo '</ol>';
    echo '</div>';
}

echo '</div>';

echo '</div></body></html>';
