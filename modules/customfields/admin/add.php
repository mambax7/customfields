<?php
include_once '../../../include/cp_header.php';
include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
include_once XOOPS_ROOT_PATH . '/modules/customfields/include/functions.php';

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'form';
$field_id = isset($_REQUEST['field_id']) ? intval($_REQUEST['field_id']) : 0;

$fieldHandler = customfields_getFieldHandler();

switch ($op) {
    case 'save':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('manage.php', 3, 'Token hatası');
        }
        
        if ($field_id > 0) {
            $field = $fieldHandler->get($field_id);
        } else {
            $field = $fieldHandler->create();
            $field->setVar('created', time());
        }
        
        $field->setVar('target_module', $_POST['target_module']);
        $field->setVar('field_name', $_POST['field_name']);
        $field->setVar('field_title', $_POST['field_title']);
        $field->setVar('field_description', isset($_POST['field_description']) ? $_POST['field_description'] : '');
        $field->setVar('field_type', $_POST['field_type']);
        $field->setVar('field_order', intval($_POST['field_order']));
        $field->setVar('required', isset($_POST['required']) ? 1 : 0);
        $field->setVar('show_in_form', isset($_POST['show_in_form']) ? 1 : 0);
        $field->setVar('modified', time());
        
        if (in_array($_POST['field_type'], array('select', 'checkbox', 'radio'))) {
            $options = array();
            if (isset($_POST['option_values']) && isset($_POST['option_labels'])) {
                foreach ($_POST['option_values'] as $key => $val) {
                    if (!empty($val) && !empty($_POST['option_labels'][$key])) {
                        $options[$val] = $_POST['option_labels'][$key];
                    }
                }
            }
            $field->setOptions($options);
        }
        
        if ($fieldHandler->insert($field)) {
            redirect_header('manage.php', 2, 'Alan başarıyla kaydedildi');
        } else {
            redirect_header('manage.php', 3, 'Kayıt hatası');
        }
        break;
        
    case 'form':
    default:
        xoops_cp_header();
        
        if ($field_id > 0) {
            $field = $fieldHandler->get($field_id);
            if (!$field) {
                redirect_header('manage.php', 3, 'Alan bulunamadı');
            }
            $page_title = 'Alan Düzenle';
            $page_icon = '✏️';
        } else {
            $field = $fieldHandler->create();
            $page_title = 'Yeni Alan Ekle';
            $page_icon = '➕';
        }
?>

<style>
/* Modern Form Styles */
.cf-form-page {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    background: #f8f9fa;
    padding: 20px;
    margin: -10px;
}

.cf-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    border-radius: 10px;
    margin-bottom: 30px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.cf-header h1 {
    margin: 0 0 5px 0;
    font-size: 24px;
    font-weight: 600;
}

.cf-header p {
    margin: 0;
    opacity: 0.9;
    font-size: 14px;
}

.cf-breadcrumb {
    margin-bottom: 20px;
    font-size: 14px;
    color: #718096;
}

.cf-breadcrumb a {
    color: #667eea;
    text-decoration: none;
}

.cf-breadcrumb a:hover {
    text-decoration: underline;
}

.cf-form-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    overflow: hidden;
}

.cf-form-group {
    padding: 25px;
    border-bottom: 1px solid #e2e8f0;
}

.cf-form-group:last-child {
    border-bottom: none;
}

.cf-label {
    display: block;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 8px;
    font-size: 14px;
}

.cf-required {
    color: #e53e3e;
    margin-left: 4px;
}

.cf-help {
    display: block;
    font-size: 13px;
    color: #718096;
    margin-top: 6px;
    line-height: 1.5;
}

.cf-input {
    width: 100%;
    padding: 10px 14px;
    border: 2px solid #e2e8f0;
    border-radius: 6px;
    font-size: 14px;
    transition: all 0.3s ease;
    font-family: inherit;
}

.cf-input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
}

.cf-textarea {
    resize: vertical;
    min-height: 80px;
}

.cf-select {
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%234a5568' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    padding-right: 40px;
}

.cf-options-container {
    display: none;
    background: #f7fafc;
    padding: 20px;
    border-radius: 6px;
    margin-top: 10px;
}

.cf-options-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 15px;
}

.cf-options-table th {
    text-align: left;
    padding: 10px;
    background: white;
    font-size: 12px;
    font-weight: 600;
    color: #4a5568;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-radius: 4px;
}

.cf-options-table td {
    padding: 8px 10px;
}

.cf-options-table input {
    width: 100%;
    padding: 8px 12px;
    border: 2px solid #e2e8f0;
    border-radius: 4px;
    font-size: 14px;
}

.cf-btn {
    display: inline-block;
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 14px;
}

.cf-btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.cf-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.cf-btn-secondary {
    background: #e2e8f0;
    color: #4a5568;
}

.cf-btn-secondary:hover {
    background: #cbd5e0;
}

.cf-btn-success {
    background: #48bb78;
    color: white;
}

.cf-btn-success:hover {
    background: #38a169;
}

.cf-btn-danger {
    background: #f56565;
    color: white;
}

.cf-btn-danger:hover {
    background: #e53e3e;
}

.cf-btn-sm {
    padding: 6px 12px;
    font-size: 12px;
}

.cf-checkbox {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    background: #f7fafc;
    border-radius: 6px;
    margin-bottom: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.cf-checkbox:hover {
    background: #edf2f7;
}

.cf-checkbox input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.cf-checkbox label {
    margin: 0;
    cursor: pointer;
    font-weight: 500;
    color: #2d3748;
}

.cf-form-actions {
    padding: 20px 25px;
    background: #f7fafc;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.cf-info-box {
    background: #e6f2ff;
    border-left: 4px solid #667eea;
    padding: 15px;
    border-radius: 6px;
    margin-top: 10px;
}

.cf-info-box strong {
    color: #667eea;
}

@media (max-width: 768px) {
    .cf-form-page {
        padding: 10px;
    }
    
    .cf-header {
        padding: 20px;
    }
    
    .cf-form-group {
        padding: 20px;
    }
    
    .cf-form-actions {
        flex-direction: column;
    }
    
    .cf-btn {
        width: 100%;
    }
}
</style>

<div class="cf-form-page">
    <!-- Header -->
    <div class="cf-header">
        <h1><?php echo $page_icon . ' ' . $page_title; ?></h1>
        <p>Alan bilgilerini doldurun ve kaydedin</p>
    </div>
    
    <!-- Breadcrumb -->
    <div class="cf-breadcrumb">
        <a href="index.php">🏠 Ana Sayfa</a> /
        <a href="manage.php">📋 Alan Yönetimi</a> /
        <strong><?php echo $page_title; ?></strong>
    </div>
    
    <!-- Form -->
    <form method="post" action="add.php" id="field_form">
        <input type="hidden" name="op" value="save">
        <?php echo $GLOBALS['xoopsSecurity']->getTokenHTML(); ?>
        <?php if ($field_id > 0): ?>
        <input type="hidden" name="field_id" value="<?php echo $field_id; ?>">
        <?php endif; ?>
        
        <div class="cf-form-card">
            <!-- Hedef Modül -->
            <div class="cf-form-group">
                <label class="cf-label">
                    🎯 Hedef Modül <span class="cf-required">*</span>
                </label>
                <select name="target_module" id="target_module" required class="cf-input cf-select">
                    <option value="">Modül Seçin...</option>
                    <?php
                    $module_handler = xoops_getHandler('module');
                    $criteria = new Criteria('isactive', 1);
                    $modules = $module_handler->getObjects($criteria);
                    foreach ($modules as $module) {
                        $selected = ($field->getVar('target_module') == $module->getVar('dirname')) ? 'selected' : '';
                        echo '<option value="' . $module->getVar('dirname') . '" ' . $selected . '>' . $module->getVar('name') . '</option>';
                    }
                    ?>
                </select>
                <span class="cf-help">Bu alanın hangi modülde kullanılacağını seçin</span>
            </div>
            
            <!-- Alan Adı -->
            <div class="cf-form-group">
                <label class="cf-label">
                    🔤 Alan Adı (değişken) <span class="cf-required">*</span>
                </label>
                <input type="text" name="field_name" value="<?php echo $field->getVar('field_name', 'e'); ?>" 
                       required class="cf-input" placeholder="ornek: ek_resim">
                <span class="cf-help">⚠️ Sadece İngilizce harfler, rakamlar ve alt çizgi kullanın. Örnek: <code>ek_resim</code>, <code>video_url</code></span>
            </div>
            
            <!-- Alan Başlığı -->
            <div class="cf-form-group">
                <label class="cf-label">
                    📝 Alan Başlığı <span class="cf-required">*</span>
                </label>
                <input type="text" name="field_title" value="<?php echo $field->getVar('field_title', 'e'); ?>" 
                       required class="cf-input" placeholder="Kullanıcıya gösterilecek başlık">
                <span class="cf-help">Formda gösterilecek Türkçe başlık. Örnek: <code>Ek Resim</code>, <code>Video Linki</code></span>
            </div>
            
            <!-- Açıklama -->
            <div class="cf-form-group">
                <label class="cf-label">
                    💬 Açıklama
                </label>
                <textarea name="field_description" rows="3" class="cf-input cf-textarea" 
                          placeholder="Kullanıcıya yardımcı olacak açıklama metni"><?php echo $field->getVar('field_description', 'e'); ?></textarea>
                <span class="cf-help">Formda alanın altında gösterilecek yardım metni (isteğe bağlı)</span>
            </div>
            
            <!-- Alan Tipi -->
            <div class="cf-form-group">
                <label class="cf-label">
                    🎨 Alan Tipi <span class="cf-required">*</span>
                </label>
                <select name="field_type" id="field_type" required class="cf-input cf-select">
                    <?php
                    $types = array(
                        'text' => '📄 Metin (Tek Satır)',
                        'textarea' => '📝 Çok Satırlı Metin',
                        'editor' => '✏️ HTML Editör (WYSIWYG)',
                        'image' => '🖼️ Resim Yükleme',
                        'file' => '📎 Dosya Yükleme',
                        'select' => '📋 Açılır Liste',
                        'checkbox' => '☑️ Çoklu Seçim',
                        'radio' => '🔘 Tekli Seçim',
                        'date' => '📅 Tarih Seçici'
                    );
                    
                    foreach ($types as $type_val => $type_label) {
                        $selected = ($field->getVar('field_type') == $type_val) ? 'selected' : '';
                        echo '<option value="' . $type_val . '" ' . $selected . '>' . $type_label . '</option>';
                    }
                    ?>
                </select>
                
                <!-- Seçenekler -->
                <div id="options_container" class="cf-options-container">
                    <table class="cf-options-table">
                        <thead>
                            <tr>
                                <th style="width: 40%;">Değer</th>
                                <th style="width: 40%;">Etiket</th>
                                <th style="width: 20%; text-align: center;">İşlem</th>
                            </tr>
                        </thead>
                        <tbody id="options_tbody">
                            <?php
                            $options = $field->getOptions();
                            if (!empty($options)) {
                                foreach ($options as $opt_val => $opt_label) {
                                    echo '<tr>';
                                    echo '<td><input type="text" name="option_values[]" value="' . htmlspecialchars($opt_val) . '" placeholder="1"></td>';
                                    echo '<td><input type="text" name="option_labels[]" value="' . htmlspecialchars($opt_label) . '" placeholder="Seçenek 1"></td>';
                                    echo '<td style="text-align: center;"><button type="button" class="cf-btn cf-btn-danger cf-btn-sm remove-option">🗑️ Sil</button></td>';
                                    echo '</tr>';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <button type="button" id="add_option" class="cf-btn cf-btn-success cf-btn-sm">➕ Seçenek Ekle</button>
                </div>
            </div>
            
            <!-- Sıralama -->
            <div class="cf-form-group">
                <label class="cf-label">
                    🔢 Sıralama
                </label>
                <input type="number" name="field_order" value="<?php echo $field->getVar('field_order'); ?>" 
                       min="0" class="cf-input" style="max-width: 150px;">
                <span class="cf-help">Formda gösterilme sırası (küçük sayı önce gösterilir)</span>
            </div>
            
            <!-- Seçenekler -->
            <div class="cf-form-group">
                <label class="cf-label">
                    ⚙️ Seçenekler
                </label>
                <div class="cf-checkbox">
                    <input type="checkbox" name="required" value="1" id="required" <?php echo $field->getVar('required') ? 'checked' : ''; ?>>
                    <label for="required">🔒 Zorunlu Alan (Kullanıcı boş bırakamaz)</label>
                </div>
                <div class="cf-checkbox">
                    <input type="checkbox" name="show_in_form" value="1" id="show_in_form" <?php echo $field->getVar('show_in_form') ? 'checked' : ''; ?>>
                    <label for="show_in_form">👁️ Formda Göster (Admin panelde görünür)</label>
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="cf-form-actions">
            <button type="button" onclick="history.back()" class="cf-btn cf-btn-secondary">
                ← Geri Dön
            </button>
            <button type="submit" class="cf-btn cf-btn-primary">
                💾 Kaydet
            </button>
        </div>
    </form>
    
    <!-- Info Box -->
    <div class="cf-info-box" style="margin-top: 20px;">
        <strong>💡 İpucu:</strong> Alan oluşturduktan sonra hedef modülün admin sayfasına entegrasyon kodunu eklemeniz gerekecek.
    </div>
</div>

<script>
// Modern JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const fieldType = document.getElementById('field_type');
    const optionsContainer = document.getElementById('options_container');
    const addOptionBtn = document.getElementById('add_option');
    const optionsTbody = document.getElementById('options_tbody');
    
    // Alan tipi değiştiğinde seçenekleri göster/gizle
    function toggleOptions() {
        const needsOptions = ['select', 'checkbox', 'radio'].includes(fieldType.value);
        optionsContainer.style.display = needsOptions ? 'block' : 'none';
    }
    
    toggleOptions();
    fieldType.addEventListener('change', toggleOptions);
    
    // Yeni seçenek ekle
    addOptionBtn.addEventListener('click', function() {
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td><input type="text" name="option_values[]" placeholder="Değer"></td>
            <td><input type="text" name="option_labels[]" placeholder="Etiket"></td>
            <td style="text-align: center;">
                <button type="button" class="cf-btn cf-btn-danger cf-btn-sm remove-option">🗑️ Sil</button>
            </td>
        `;
        optionsTbody.appendChild(newRow);
    });
    
    // Seçenek sil
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-option')) {
            e.target.closest('tr').remove();
        }
    });
    
    // Form validasyonu
    document.getElementById('field_form').addEventListener('submit', function(e) {
        const needsOptions = ['select', 'checkbox', 'radio'].includes(fieldType.value);
        
        if (needsOptions) {
            const optionValues = document.querySelectorAll('input[name="option_values[]"]');
            let hasValidOption = false;
            
            optionValues.forEach(input => {
                if (input.value.trim() !== '') {
                    hasValidOption = true;
                }
            });
            
            if (!hasValidOption) {
                e.preventDefault();
                alert('⚠️ Lütfen en az bir seçenek ekleyin!');
                return false;
            }
        }
    });
});
</script>

<?php
        xoops_cp_footer();
        break;
}
