<?php
include_once '../../../include/cp_header.php';
include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
include_once XOOPS_ROOT_PATH . '/modules/customfields/include/functions.php';

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'list';
$field_id = isset($_REQUEST['field_id']) ? intval($_REQUEST['field_id']) : 0;

$fieldHandler = customfields_getFieldHandler();

switch ($op) {
    case 'save':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('fields.php', 3, 'Token hatası');
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
        $field->setVar('field_description', $_POST['field_description']);
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
            redirect_header('fields.php', 2, 'Alan başarıyla kaydedildi');
        } else {
            redirect_header('fields.php', 3, 'Kayıt hatası');
        }
        break;
        
    case 'delete':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('fields.php', 3, 'Token hatası');
        }
        
        $field = $fieldHandler->get($field_id);
        if ($field && $fieldHandler->delete($field)) {
            redirect_header('fields.php', 2, 'Alan silindi');
        } else {
            redirect_header('fields.php', 3, 'Silme hatası');
        }
        break;
        
    default:
        xoops_cp_header();
        
        echo '<h4>İlave Alanlar</h4>';
        echo '<a href="fields.php?op=add" class="btn btn-primary">Yeni Alan Ekle</a><br><br>';
        
        $fields = $fieldHandler->getAll();
        
        if (count($fields) > 0) {
            echo '<table class="outer" style="width:100%">';
            echo '<tr><th>ID</th><th>Modül</th><th>Alan Adı</th><th>Başlık</th><th>Tip</th><th>İşlemler</th></tr>';
            
            foreach ($fields as $field) {
                echo '<tr>';
                echo '<td>' . $field->getVar('field_id') . '</td>';
                echo '<td>' . $field->getVar('target_module') . '</td>';
                echo '<td>' . $field->getVar('field_name') . '</td>';
                echo '<td>' . $field->getVar('field_title') . '</td>';
                echo '<td>' . $field->getVar('field_type') . '</td>';
                echo '<td>';
                echo '<a href="fields.php?op=delete&field_id=' . $field->getVar('field_id') . '&' . $GLOBALS['xoopsSecurity']->getTokenHTML() . '" onclick="return confirm(\'Silmek istediğinizden emin misiniz?\')">Sil</a>';
                echo '</td>';
                echo '</tr>';
            }
            
            echo '</table>';
        } else {
            echo '<p>Henüz alan eklenmemiş.</p>';
        }
        
        xoops_cp_footer();
        break;
}
