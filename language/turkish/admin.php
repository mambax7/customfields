<?php

// Admin language constants for Tests tab (Turkish)
if (!defined('_AM_CUSTOMFIELDS_TESTS_TITLE')) {
    define('_AM_CUSTOMFIELDS_TESTS_TITLE', 'Testler');
}
if (!defined('_AM_CUSTOMFIELDS_TESTS_DESC')) {
    define('_AM_CUSTOMFIELDS_TESTS_DESC', 'CustomFields entegrasyonu için geliştirici test yardımcıları. Üretim ortamlarında kullanmayın.');
}
if (!defined('_AM_CUSTOMFIELDS_TESTS_TH_SCRIPT')) {
    define('_AM_CUSTOMFIELDS_TESTS_TH_SCRIPT', 'Betik');
}
if (!defined('_AM_CUSTOMFIELDS_TESTS_TH_DESC')) {
    define('_AM_CUSTOMFIELDS_TESTS_TH_DESC', 'Açıklama');
}
if (!defined('_AM_CUSTOMFIELDS_TESTS_TH_ACTION')) {
    define('_AM_CUSTOMFIELDS_TESTS_TH_ACTION', 'İşlem');
}
if (!defined('_AM_CUSTOMFIELDS_TESTS_OPEN_BTN')) {
    define('_AM_CUSTOMFIELDS_TESTS_OPEN_BTN', 'Aç');
}
if (!defined('_AM_CUSTOMFIELDS_TESTS_NOTE')) {
    define('_AM_CUSTOMFIELDS_TESTS_NOTE', 'Not: Bazı betikler tanılama bilgileri gösterebilir. Yalnızca admin erişimine açık tutun ve üretim ortamında kullanmaktan kaçının.');
}

// Admin menü
define('_AM_CUSTOMFIELDS_OVERVIEW', 'Genel Bakış');
define('_AM_CUSTOMFIELDS_FIELDS', 'Alan Yönetimi');
define('_AM_CUSTOMFIELDS_FIELD_SAVED', 'Alan başarıyla kaydedildi');
define('_AM_CUSTOMFIELDS_FIELD_DELETED', 'Alan silindi');
define('_AM_CUSTOMFIELDS_ERROR', 'Hata oluştu');
define('_AM_CUSTOMFIELDS_FIELD_NOT_FOUND', 'Field not found');

define('_AM_CUSTOMFIELDS_GUIDE_TITLE', '📚 Kullanım Rehberi');
define('_AM_CUSTOMFIELDS_GUIDE_SUBTITLE', 'İlave alanları modüllerinize nasıl entegre edeceğinizi öğrenin');

define('_AM_CUSTOMFIELDS_QUICK_ACCESS', '⚡ Hızlı Erişim');
define('_AM_CUSTOMFIELDS_ADD_FIELD', '➕ Yeni Alan Ekle');
define('_AM_CUSTOMFIELDS_MANAGE_FIELDS', '📋 Alanları Yönet');

define('_AM_CUSTOMFIELDS_NEWS_INTEGRATION', '🗞️ News Modülü Entegrasyonu');

define('_AM_CUSTOMFIELDS_STEP1_FORM_ADD_ADMIN', 'Form Ekleme (Admin Panel)');
define('_AM_CUSTOMFIELDS_STEP1_DESC', 'News modülünün admin panelinde haber ekleme/düzenleme formuna ilave alanları ekleyin.');

define('_AM_CUSTOMFIELDS_STEP2_SAVE_DATA', 'Veri Kaydetme');
define('_AM_CUSTOMFIELDS_STEP2_DESC', 'Haber kaydedildiğinde ilave alan verilerini de kaydedin.');
define('_AM_CUSTOMFIELDS_NEWS_SAVED', 'Haber kaydedildi');

define('_AM_CUSTOMFIELDS_STEP3_DELETE_OPTIONAL', 'Silme İşlemi (İsteğe Bağlı)');
define('_AM_CUSTOMFIELDS_STEP3_DESC', 'Haber silindiğinde ilave alan verilerini de silin.');
define('_AM_CUSTOMFIELDS_NEWS_DELETED', 'Haber silindi');

define('_AM_CUSTOMFIELDS_STEP4_DISPLAY_TEMPLATE', 'Template\'te Gösterme');
define('_AM_CUSTOMFIELDS_STEP4_DESC', 'Haber görüntüleme sayfasında ilave alanları gösterin.');

define('_AM_CUSTOMFIELDS_EXTRA_INFO', 'Ek Bilgiler');

define('_AM_CUSTOMFIELDS_OTHER_MODULES', '🔌 Diğer Modüller');
define('_AM_CUSTOMFIELDS_OTHER_MODULES_DESC', 'Aynı mantıkla herhangi bir XOOPS modülüne entegre edebilirsiniz:');

define('_AM_CUSTOMFIELDS_TIP_LABEL', '💡 İpucu:');
define('_AM_CUSTOMFIELDS_TIP_TEXT', 'Sadece \'news\' yazan yerleri hedef modül adınız ile değiştirin. Örneğin: \'publisher\', \'content\', \'articles\'.');

define('_AM_CUSTOMFIELDS_GENERAL_STEPS', '📋 Genel Adımlar:');
define('_AM_CUSTOMFIELDS_STEP_GENERAL_DEFINE_FIELDS', 'İlave Alanlar modülünden hedef modül için alan tanımlayın.');
define('_AM_CUSTOMFIELDS_STEP_GENERAL_ADD_RENDERFORM', 'Hedef modülün admin form sayfasına customfields_renderForm() ekleyin.');
define('_AM_CUSTOMFIELDS_STEP_GENERAL_ADD_SAVEDATA', 'Kaydetme işlemine customfields_saveData() ekleyin.');
define('_AM_CUSTOMFIELDS_STEP_GENERAL_ADD_SMARTY', 'Template\'e Smarty fonksiyonu ekleyin.');

define('_AM_CUSTOMFIELDS_TIPS_TITLE', '🎯 İpuçları');
define('_AM_CUSTOMFIELDS_WARNING_LABEL', '⚠️ Dikkat:');

define('_AM_CUSTOMFIELDS_TIP_NO_TR_CHARS', 'Alan adlarında Türkçe karakter kullanmayın.');
define('_AM_CUSTOMFIELDS_TIP_UPLOAD_WRITABLE', 'Dosya yüklemeleri için uploads/customfields/ dizini yazılabilir olmalı.');
define('_AM_CUSTOMFIELDS_TIP_CLEAR_CACHE', 'Template değişikliklerinden sonra cache\'i temizleyin.');

define('_AM_CUSTOMFIELDS_BEST_PRACTICES_TITLE', '✅ En İyi Uygulamalar:');
define('_AM_CUSTOMFIELDS_BP_MEANINGFUL_SHORT_NAMES', 'Alan adlarını anlamlı ve kısa tutun (örn: ek_resim).');
define('_AM_CUSTOMFIELDS_BP_TURKISH_TITLES_OK', 'Başlıklarda Türkçe kullanabilirsiniz (örn: "Ek Resim").');
define('_AM_CUSTOMFIELDS_BP_REQUIRED_FIELDS', 'Zorunlu alanları dikkatli seçin.');
define('_AM_CUSTOMFIELDS_BP_ADD_DESCRIPTIONS', 'Açıklama ekleyerek kullanıcılara yardımcı olun.');

define('_AM_CUSTOMFIELDS_API_FUNCTIONS_TITLE', '🔧 API Fonksiyonları');


// Fields admin listing
define('_AM_CUSTOMFIELDS_TOKEN_ERROR', 'Token hatası');
define('_AM_CUSTOMFIELDS_FIELD_SAVED_SUCCESS', 'Alan başarıyla kaydedildi');
define('_AM_CUSTOMFIELDS_SAVE_ERROR', 'Kayıt hatası');
define('_AM_CUSTOMFIELDS_FIELD_DELETED', 'Alan silindi');
define('_AM_CUSTOMFIELDS_DELETE_ERROR', 'Silme hatası');

define('_AM_CUSTOMFIELDS_FIELDS_HEADING', 'İlave Alanlar');
define('_AM_CUSTOMFIELDS_ADD_FIELD_LINK', 'Yeni Alan Ekle');

define('_AM_CUSTOMFIELDS_TABLE_ID', 'ID');
define('_AM_CUSTOMFIELDS_TABLE_MODULE', 'Modül');
define('_AM_CUSTOMFIELDS_TABLE_FIELD_NAME', 'Alan Adı');
define('_AM_CUSTOMFIELDS_TABLE_FIELD_TITLE', 'Başlık');
define('_AM_CUSTOMFIELDS_TABLE_FIELD_TYPE', 'Tip');
define('_AM_CUSTOMFIELDS_TABLE_ACTIONS', 'İşlemler');

define('_AM_CUSTOMFIELDS_ACTION_DELETE', 'Sil');
define('_AM_CUSTOMFIELDS_CONFIRM_DELETE', 'Silmek istediğinizden emin misiniz?');

define('_AM_CUSTOMFIELDS_NO_FIELDS', 'Henüz alan eklenmemiş.');

// List filters and actions
define('_AM_CUSTOMFIELDS_FILTER_MODULE', 'Modül filtresi:');
define('_AM_CUSTOMFIELDS_FILTER_TYPE', 'Tip filtresi:');
define('_AM_CUSTOMFIELDS_FILTER_LIMIT', 'Sayfa başına kayıt:');
define('_AM_CUSTOMFIELDS_FILTER_SUBMIT', 'Filtrele');
define('_AM_CUSTOMFIELDS_FILTER_RESET', 'Sıfırla');

define('_AM_CUSTOMFIELDS_ACTION_EDIT', 'Düzenle');

// Field form
define('_AM_CUSTOMFIELDS_FIELD_FORM_HEADING_NEW', 'Yeni İlave Alan Ekle');
define('_AM_CUSTOMFIELDS_FIELD_FORM_HEADING_EDIT', 'İlave Alanı Düzenle');

define('_AM_CUSTOMFIELDS_FIELD_TARGET_MODULE', 'Hedef modül');
define('_AM_CUSTOMFIELDS_FIELD_TARGET_MODULE_HELP', 'Modül klasör adı (ör. news, publisher, content).');

define('_AM_CUSTOMFIELDS_FIELD_NAME', 'Alan adı');
//define('_AM_CUSTOMFIELDS_FIELD_NAME_HELP', 'İç alan tanımı, boşluk ve özel karakter kullanmayın.');

define('_AM_CUSTOMFIELDS_FIELD_TITLE', 'Başlık');
define('_AM_CUSTOMFIELDS_FIELD_TITLE_HELP', 'Formlarda kullanıcılara gösterilen etiket.');

define('_AM_CUSTOMFIELDS_FIELD_DESCRIPTION', 'Açıklama');
define('_AM_CUSTOMFIELDS_FIELD_DESCRIPTION_HELP', 'Kullanıcılara gösterilen isteğe bağlı yardım metni.');

define('_AM_CUSTOMFIELDS_FIELD_TYPE', 'Alan tipi');
define('_AM_CUSTOMFIELDS_FIELD_TYPE_HELP', 'Alan nasıl görüntülenecek, buradan seçin.');

define('_AM_CUSTOMFIELDS_FIELD_ORDER', 'Görüntüleme sırası');

define('_AM_CUSTOMFIELDS_FIELD_REQUIRED', 'Zorunlu');
define('_AM_CUSTOMFIELDS_FIELD_REQUIRED_LABEL', 'Bu alan zorunludur.');

define('_AM_CUSTOMFIELDS_FIELD_SHOW_IN_FORM', 'Formda göster');
define('_AM_CUSTOMFIELDS_FIELD_SHOW_IN_FORM_LABEL', 'Bu alanı modül formunda göster.');

define('_AM_CUSTOMFIELDS_FIELD_OPTIONS', 'Seçenekler');
define('_AM_CUSTOMFIELDS_FIELD_OPTIONS_HELP', 'Select, checkbox ve radio alanları için değer/etiket çiftleri tanımlayın. Gerek yoksa boş bırakın.');
define('_AM_CUSTOMFIELDS_FIELD_OPTION_VALUE', 'Seçenek değeri');
define('_AM_CUSTOMFIELDS_FIELD_OPTION_LABEL', 'Seçenek etiketi');

define('_AM_CUSTOMFIELDS_SAVE_BUTTON', 'Kaydet');
define('_AM_CUSTOMFIELDS_CANCEL_BUTTON', 'İptal');


// Dashboard
define('_AM_CUSTOMFIELDS_DASHBOARD_TITLE', '🎨 İlave Alanlar Modülü');
define('_AM_CUSTOMFIELDS_DASHBOARD_SUBTITLE', 'Dinamik özel alanlar ile modüllerinizi genişletin');

define('_AM_CUSTOMFIELDS_DASHBOARD_TOTAL_FIELDS_LABEL', 'Toplam Alan');
define('_AM_CUSTOMFIELDS_DASHBOARD_TOTAL_DATA_LABEL', 'Kayıtlı Veri');
define('_AM_CUSTOMFIELDS_DASHBOARD_TOTAL_MODULES_LABEL', 'Entegre Modül');

define('_AM_CUSTOMFIELDS_DASHBOARD_QUICK_ACTIONS', 'Hızlı İşlemler');
define('_AM_CUSTOMFIELDS_DASHBOARD_ADD_FIELD_BTN', '➕ Yeni Alan Ekle');

define('_AM_CUSTOMFIELDS_DASHBOARD_MODULE_STATS_TITLE', '📊 Modül Bazında Dağılım');
define('_AM_CUSTOMFIELDS_DASHBOARD_TABLE_MODULE', 'Modül');
define('_AM_CUSTOMFIELDS_DASHBOARD_TABLE_FIELD_COUNT', 'Alan Sayısı');
define('_AM_CUSTOMFIELDS_DASHBOARD_TABLE_ACTIONS', 'İşlemler');
define('_AM_CUSTOMFIELDS_DASHBOARD_FIELD_COUNT_SUFFIX', 'alan');

define('_AM_CUSTOMFIELDS_DASHBOARD_VIEW_BUTTON', 'Görüntüle →');

define('_AM_CUSTOMFIELDS_DASHBOARD_EMPTY_TITLE', 'Henüz Alan Eklenmemiş');
define('_AM_CUSTOMFIELDS_DASHBOARD_EMPTY_MESSAGE', 'Başlamak için yukarıdaki "Yeni Alan Ekle" butonuna tıklayın.');

define('_AM_CUSTOMFIELDS_DASHBOARD_QUICK_GUIDE_TITLE', '🚀 Hızlı Başlangıç Rehberi');

define('_AM_CUSTOMFIELDS_DASHBOARD_STEP1_TITLE', 'Alan Tanımla:');
define('_AM_CUSTOMFIELDS_DASHBOARD_STEP1_DESC', '"Yeni Alan Ekle" ile bir alan oluşturun, örn. ');

define('_AM_CUSTOMFIELDS_DASHBOARD_STEP2_TITLE', 'Entegre Et:');
define('_AM_CUSTOMFIELDS_DASHBOARD_STEP2_DESC', 'Hedef modülün form dosyasına entegrasyon kodunu ekleyin.');

define('_AM_CUSTOMFIELDS_DASHBOARD_STEP3_TITLE', 'Kaydet:');
define('_AM_CUSTOMFIELDS_DASHBOARD_STEP3_DESC', 'Veri kaydetme işlemine aşağıdaki fonksiyonu ekleyin: ');

define('_AM_CUSTOMFIELDS_DASHBOARD_STEP4_TITLE', 'Göster:');
define('_AM_CUSTOMFIELDS_DASHBOARD_STEP4_DESC', 'Template’e görüntüleme kodunu ekleyin.');

define('_AM_CUSTOMFIELDS_DASHBOARD_DOC_LINK_TEXT', 'Detaylı dokümantasyon için tıklayın →');


// Manage fields (manage.php)
define('_AM_CUSTOMFIELDS_MANAGE_TITLE', '📋 Alan Yönetimi');
define('_AM_CUSTOMFIELDS_MANAGE_SUBTITLE', 'Tanımlı alanları görüntüleyin ve yönetin');

define('_AM_CUSTOMFIELDS_MANAGE_ADD_FIELD_BTN', '➕ Yeni Alan Ekle');

define('_AM_CUSTOMFIELDS_MANAGE_FILTER_LABEL', '🔍 Filtrele:');
define('_AM_CUSTOMFIELDS_MANAGE_FILTER_ALL_MODULES', 'Tüm modüller');

define('_AM_CUSTOMFIELDS_MANAGE_TABLE_ORDER', 'Sıra');
define('_AM_CUSTOMFIELDS_MANAGE_TABLE_REQUIRED', 'Zorunlu');
define('_AM_CUSTOMFIELDS_MANAGE_TABLE_IN_FORM', 'Formda');

define('_AM_CUSTOMFIELDS_BADGE_YES', '✓ Evet');
define('_AM_CUSTOMFIELDS_BADGE_NO', '✗ Hayır');

define('_AM_CUSTOMFIELDS_CONFIRM_DELETE_MANAGE',
    "Bu alanı silmek istediğinizden emin misiniz?\n\nİlgili tüm veriler de silinecektir!");

define('_AM_CUSTOMFIELDS_MANAGE_FOOTER_TOTAL_PREFIX', '📊 Toplam');
define('_AM_CUSTOMFIELDS_MANAGE_FOOTER_TOTAL_SUFFIX', 'alan gösteriliyor');
define('_AM_CUSTOMFIELDS_MANAGE_FOOTER_FILTER_SUFFIX', 'modülü için filtrelendi');

define('_AM_CUSTOMFIELDS_MANAGE_EMPTY_TITLE', 'Henüz Alan Eklenmemiş');
define('_AM_CUSTOMFIELDS_MANAGE_EMPTY_MESSAGE',
    'Başlamak için "Yeni Alan Ekle" butonuna tıklayın.');
define('_AM_CUSTOMFIELDS_MANAGE_EMPTY_CREATE_BTN', '➕ İlk Alanı Oluştur');

// Genel mesajlar (tekrar kullanılabilir)
//define('_AM_CUSTOMFIELDS_TOKEN_ERROR', 'Token hatası');
//define('_AM_CUSTOMFIELDS_FIELD_SAVED', 'Alan başarıyla kaydedildi');
//define('_AM_CUSTOMFIELDS_SAVE_ERROR', 'Kayıt hatası');
//define('_AM_CUSTOMFIELDS_FIELD_NOT_FOUND', 'Alan bulunamadı');

// Alan ekleme/düzenleme formu (add.php)
define('_AM_CUSTOMFIELDS_FIELD_FORM_TITLE_NEW', '➕ Yeni Alan Ekle');
define('_AM_CUSTOMFIELDS_FIELD_FORM_TITLE_EDIT', '✏️ Alan Düzenle');
define('_AM_CUSTOMFIELDS_FIELD_FORM_SUBTITLE', 'Alan bilgilerini doldurun ve kaydedin');

define('_AM_CUSTOMFIELDS_BREADCRUMB_HOME', '🏠 Ana Sayfa');
define('_AM_CUSTOMFIELDS_BREADCRUMB_MANAGE', '📋 Alan Yönetimi');
define('_AM_CUSTOMFIELDS_FIELD_FORM_CRUMB_NEW', 'Yeni Alan Ekle');
define('_AM_CUSTOMFIELDS_FIELD_FORM_CRUMB_EDIT', 'Alan Düzenle');

// Hedef modül
define('_AM_CUSTOMFIELDS_TARGET_MODULE_LABEL', '🎯 Hedef Modül');
define('_AM_CUSTOMFIELDS_TARGET_MODULE_PLACEHOLDER', 'Modül Seçin...');
define('_AM_CUSTOMFIELDS_TARGET_MODULE_HELP',
    'Bu alanın hangi modülde kullanılacağını seçin.');

// Alan adı
define('_AM_CUSTOMFIELDS_FIELD_NAME_LABEL', '🔤 Alan Adı (değişken)');
define('_AM_CUSTOMFIELDS_FIELD_NAME_PLACEHOLDER', 'örnek: ek_resim');
define('_AM_CUSTOMFIELDS_FIELD_NAME_HELP2', '⚠️ Sadece İngilizce harfler, rakamlar ve alt çizgi kullanın. Örnek: ek_resim, video_url.');

// Alan başlığı
define('_AM_CUSTOMFIELDS_FIELD_TITLE_LABEL', '📝 Alan Başlığı');
define('_AM_CUSTOMFIELDS_FIELD_TITLE_PLACEHOLDER', 'Kullanıcıya gösterilecek başlık');
define('_AM_CUSTOMFIELDS_FIELD_TITLE_HELP2', 'Formda gösterilecek Türkçe başlık. Örnek: "Ek Resim", "Video Linki".');

// Açıklama
define('_AM_CUSTOMFIELDS_FIELD_DESC_LABEL', '💬 Açıklama');
define('_AM_CUSTOMFIELDS_FIELD_DESC_PLACEHOLDER',
    'Kullanıcıya yardımcı olacak açıklama metni');
define('_AM_CUSTOMFIELDS_FIELD_DESC_HELP',
    'Formda alanın altında gösterilecek yardım metni (isteğe bağlı).');

// Alan tipi + etiketler
define('_AM_CUSTOMFIELDS_FIELD_TYPE_LABEL', '🎨 Alan Tipi');

define('_AM_CUSTOMFIELDS_FIELD_TYPE_TEXT', '📄 Metin (Tek Satır)');
define('_AM_CUSTOMFIELDS_FIELD_TYPE_TEXTAREA', '📝 Çok Satırlı Metin');
define('_AM_CUSTOMFIELDS_FIELD_TYPE_EDITOR', '✏️ HTML Editör (WYSIWYG)');
define('_AM_CUSTOMFIELDS_FIELD_TYPE_IMAGE', '🖼️ Resim Yükleme');
define('_AM_CUSTOMFIELDS_FIELD_TYPE_FILE', '📎 Dosya Yükleme');
define('_AM_CUSTOMFIELDS_FIELD_TYPE_SELECT', '📋 Açılır Liste');
define('_AM_CUSTOMFIELDS_FIELD_TYPE_CHECKBOX', '☑️ Çoklu Seçim');
define('_AM_CUSTOMFIELDS_FIELD_TYPE_RADIO', '🔘 Tekli Seçim');
define('_AM_CUSTOMFIELDS_FIELD_TYPE_DATE', '📅 Tarih Seçici');

// Seçenekler bölümü
define('_AM_CUSTOMFIELDS_OPTIONS_TABLE_VALUE', 'Değer');
define('_AM_CUSTOMFIELDS_OPTIONS_TABLE_LABEL', 'Etiket');
define('_AM_CUSTOMFIELDS_OPTIONS_TABLE_ACTION', 'İşlem');

define('_AM_CUSTOMFIELDS_OPTIONS_VALUE_PLACEHOLDER', '1');
define('_AM_CUSTOMFIELDS_OPTIONS_LABEL_PLACEHOLDER', 'Seçenek 1');
define('_AM_CUSTOMFIELDS_OPTIONS_DELETE_BUTTON', '🗑️ Sil');
define('_AM_CUSTOMFIELDS_OPTIONS_ADD_BUTTON', '➕ Seçenek Ekle');

define('_AM_CUSTOMFIELDS_FIELD_OPTIONS_ALERT', '⚠️ Lütfen en az bir seçenek ekleyin!');

// Sıralama
define('_AM_CUSTOMFIELDS_FIELD_ORDER_LABEL', '🔢 Sıralama');
define('_AM_CUSTOMFIELDS_FIELD_ORDER_HELP',
    'Formda gösterilme sırası (küçük sayı önce gösterilir).');

// Seçenekler (bayraklar)
define('_AM_CUSTOMFIELDS_SETTINGS_LABEL', '⚙️ Seçenekler');
define('_AM_CUSTOMFIELDS_REQUIRED_CHECKBOX',
    '🔒 Zorunlu Alan (Kullanıcı boş bırakamaz)');
define('_AM_CUSTOMFIELDS_SHOW_IN_FORM_CHECKBOX',
    '👁️ Formda Göster (Admin panelde görünür)');

// Butonlar
define('_AM_CUSTOMFIELDS_BUTTON_BACK', '← Geri Dön');
define('_AM_CUSTOMFIELDS_BUTTON_SAVE', '💾 Kaydet');

// Bilgi kutusu
define('_AM_CUSTOMFIELDS_INFOBOX_HINT_LABEL', '💡 İpucu:');
define('_AM_CUSTOMFIELDS_INFOBOX_HINT_TEXT',
    'Alan oluşturduktan sonra hedef modülün admin sayfasına entegrasyon kodunu eklemeniz gerekecek.');
