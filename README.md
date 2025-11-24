# XOOPS İlave Alanlar Modülü v1.1.1

XOOPS CMS için geliştirilmiş kapsamlı özel alan yönetim modülü.

## 📋 Özellikler

- ✅ Herhangi bir modüle özel alanlar ekleme
- ✅ 9 farklı alan tipi (metin, textarea, editör, resim, dosya, select, checkbox, radio, tarih)
- ✅ Kolay entegrasyon
- ✅ Smarty template desteği
- ✅ Dosya yükleme desteği
- ✅ Validation kuralları
- ✅ Türkçe dil desteği

## 🚀 Kurulum

1. `customfields` klasörünü `/modules/` dizinine yükleyin
2. XOOPS admin panelden "Modül Yönetimi"ne gidin
3. "İlave Alanlar" modülünü kurun
4. `uploads/customfields/` dizinine yazma izni verin (chmod 755)

## 📖 Kullanım

### 1. Alan Tanımlama

Admin Panel > İlave Alanlar > Alan Yönetimi

Buradan hedef modülü seçin ve yeni alanlar ekleyin.

### 2. Modüle Entegrasyon

#### Form Gösterme (örnek: news/admin/item.php)

```php
<?php
// Form oluşturma kısmında
include_once XOOPS_ROOT_PATH . '/modules/customfields/include/functions.php';
echo customfields_renderForm('news', $storyid);
?>
```

#### Veri Kaydetme

```php
<?php
// Kaydetme işleminden sonra
include_once XOOPS_ROOT_PATH . '/modules/customfields/include/functions.php';

if ($newstoryid = $story_handler->insert($story)) {
    customfields_saveData('news', $newstoryid);
    redirect_header("item.php", 2, "Haber kaydedildi");
}
?>
```

#### Template'te Gösterme (örnek: news/article.tpl)

```smarty
{* Tüm özel alanları göster *}
{customfields module="news" item_id=$story.id assign="custom_fields"}

{if $custom_fields}
<div class="custom-fields-section">
    <h3>Ek Bilgiler</h3>
    {foreach from=$custom_fields item=field}
        <div class="custom-field-item">
            <strong>{$field.title}:</strong>
            <div class="field-value">{$field.formatted_value}</div>
        </div>
    {/foreach}
</div>
{/if}
```

```smarty
{* Sadece belirli bir alanı göster *}
{customfield module="news" item_id=$story.id name="ek_resim"}
```

### 3. Silme İşlemi

```php
<?php
// Öğe silindiğinde özel alanları da sil
include_once XOOPS_ROOT_PATH . '/modules/customfields/include/functions.php';

if ($story_handler->delete($story)) {
    customfields_deleteData('news', $storyid);
    redirect_header("index.php", 2, "Haber silindi");
}
?>
```

## 🎨 Desteklenen Alan Tipleri

| Tip | Açıklama | Kullanım |
|-----|----------|----------|
| text | Tek satır metin | Kısa metinler için |
| textarea | Çok satırlı metin | Uzun açıklamalar |
| editor | HTML editör | Zengin içerik |
| image | Resim yükleme | Görsel dosyalar |
| file | Dosya yükleme | PDF, DOC vb. |
| select | Açılır liste | Tekli seçim |
| checkbox | Çoklu seçim kutusu | Çoklu seçim |
| radio | Radyo buton | Tekli seçim |
| date | Tarih seçici | Tarih bilgisi |

## 🔧 API Fonksiyonları

```php
// Alanları al
$fields = customfields_getFields($module_name, $show_in_form_only = false);

// Veri al
$data = customfields_getData($module_name, $item_id);

// Form render et
$html = customfields_renderForm($module_name, $item_id = 0);

// Veri kaydet
customfields_saveData($module_name, $item_id);

// Veri sil
customfields_deleteData($module_name, $item_id);

// Template için hazırla
$template_data = customfields_prepareForTemplate($module_name, $item_id);
```

## 📁 Dosya Yapısı

```
customfields/
├── admin/              # Admin panel dosyaları
│   ├── index.php       # Genel bakış
│   ├── fields.php      # Alan yönetimi
│   └── menu.php        # Admin menü
├── class/              # Sınıf dosyaları
│   ├── CustomField.php
│   ├── CustomFieldHandler.php
│   └── CustomFieldData.php
├── include/            # Yardımcı dosyalar
│   ├── functions.php   # Ana fonksiyonlar
│   └── install.php     # Kurulum
├── sql/                # Veritabanı
│   └── mysql.sql
├── language/           # Dil dosyaları
│   └── turkish/
├── assets/             # CSS/JS
│   └── css/
└── xoops_version.php   # Modül konfigürasyonu
```

## 💡 Örnekler

### Örnek 1: News Modülü Entegrasyonu

1. Admin'den "news" modülü için "ek_resim" adında bir image alanı ekleyin
2. news/admin/item.php'ye form kodunu ekleyin
3. news/article.tpl'ye görüntüleme kodunu ekleyin

### Örnek 2: Çoklu Seçenek Alanı

Select, checkbox veya radio tipi seçtiğinizde:
- "Seçenekler" bölümünden değer ve etiketleri girin
- Örnek: değer="1", etiket="Evet" / değer="0", etiket="Hayır"

## 🔒 Güvenlik

- Dosya yüklemelerinde tip kontrolü
- SQL injection koruması
- XSS koruması
- Token kontrolü

### Güvenlik ve yapılandırma (ileri seviye)

Aşağıdaki sabitler ile modülün güvenlik davranışlarını yapılandırabilirsiniz. Bu sabitleri XOOPS kurulumunuzun uygun bir bootstrap/config dosyasında tanımlayın (ör. `mainfile.php` veya modülünüzün giriş noktasında):

```php
// Maksimum yükleme boyutu (bayt) – varsayılan: 5 MB
define('CUSTOMFIELDS_MAX_UPLOAD_SIZE', 5 * 1024 * 1024);

// İzin verilen hedef modüller – boşsa tüm modüllere izin verilir
define('CUSTOMFIELDS_ALLOWED_MODULES', ['publisher', 'news']);

// Anonim kullanıcının kaydetmesine izin ver (varsayılan: false)
define('CUSTOMFIELDS_ALLOW_ANON_SAVE', false);

// Yalnızca adminlerin kaydedebileceği modül adları
define('CUSTOMFIELDS_ADMIN_ONLY_MODULES', ['sensitive_module']);

// Tarih alanları için görüntüleme formatı (PHP date() formatı) – varsayılan: 'd.m.Y'
define('CUSTOMFIELDS_DISPLAY_DATE_FORMAT', 'Y-m-d');

// (İsteğe bağlı) İzinli uzantı/MIME listelerini özelleştirme örnekleri
define('CUSTOMFIELDS_ALLOWED_IMAGE_EXT', ['jpg','jpeg','png','gif','webp']);
define('CUSTOMFIELDS_ALLOWED_FILE_EXT', ['pdf','doc','docx','xls','xlsx','zip','rar','7z']);
define('CUSTOMFIELDS_ALLOWED_IMAGE_MIME', ['image/jpeg','image/png','image/gif','image/webp']);
define('CUSTOMFIELDS_ALLOWED_FILE_MIME', ['application/pdf']);
```

Uploads klasörü (Apache) için `.htaccess` zaten eklenmiştir: `uploads/customfields/.htaccess`. Nginx eşdeğeri için şu kuralları sunucu bloğunuza ekleyin:

```nginx
location ^~ /uploads/customfields/ {
    default_type application/octet-stream;
    add_header X-Content-Type-Options nosniff always;

    # PHP, CGI, script çalıştırmayı engelle
    location ~* \.(php|phtml|phps|phar|cgi|pl|asp|aspx)$ {
        return 403;
    }
}
```

## ⚙️ Yapılandırma Erişimcileri (Config accessors)

Modül, sabitleri doğrudan okumak yerine merkezi yardımcı erişimciler kullanır (BC korunur):

```php
\XoopsModules\Customfields\Config::getUploadDir();          // Dosya sistemi yolu (uploads/customfields/)
\XoopsModules\Customfields\Config::getMaxUploadSize();      // Varsayılan 5 MB, CUSTOMFIELDS_MAX_UPLOAD_SIZE ile değiştirilebilir
\XoopsModules\Customfields\Config::getAllowedExtensions($type); // 'image' veya 'file' için uzantılar
\XoopsModules\Customfields\Config::getAllowedMimes($type);      // 'image' veya 'file' için MIME listesi
\XoopsModules\Customfields\Config::getDisplayDateFormat();  // Tarih gösterim biçimi (render sırasında kullanılır)
```

Tarih biçimi `DateRenderer` ve `customfields_formatValue()` içindeki eski yol tarafından kullanılır.

## 🖼️ Renderer Mimarisi

Alan değerlerinin HTML çıktısı tip bazlı renderer sınıflarıyla üretilir (Text, Textarea, Select, Radio, Checkbox, Date, Image, File). Yeni tipler eklemek veya davranışı özelleştirmek için `class/Renderer/` altına yeni bir renderer ekleyebilir ve `RendererFactory` içine yönlendirme ekleyebilirsiniz. Uygun kaçış (`htmlspecialchars`/`customfields_esc`) ve güvenli URL (`customfields_url`) çıktıları varsayılan olarak uygulanır.

## 📑 Admin listeleme, sayfalama ve filtreler

`admin/manage.php` ve `admin/fields.php` üzerinde sayfalama ve filtreleme (modül, tip) desteklenir. Arayüz mevcut filtreleri koruyarak gezinme sağlar ve büyük listelerde performansı iyileştirir.

## ⚙️ Gereksinimler

- XOOPS 2.5.x veya üzeri
- PHP 5.6 veya üzeri
- MySQL 5.5 veya üzeri

## 📝 Lisans

GPL v2.0

## 👨‍💻 Geliştirici

Eren - XOOPS Türkiye

## 🆘 Destek

Sorularınız için XOOPS Türkiye forumu

---

**Not:** Modülü kullanmadan önce test sunucusunda denemenizi öneririz.

---

## 🧪 Test nasıl çalıştırılır

Önkoşullar: XOOPS vendor altında PHPUnit kurulu olmalıdır.

Komutlar:

```
xoops_lib\vendor\bin\phpunit -c modules\customfields\phpunit.xml.dist
```

veya modül dizininden:

```
..\..\xoops_lib\vendor\bin\phpunit -c phpunit.xml.dist
```

Kod stil ve statik analiz araçlarını çalıştırmak için:

```
phpcs -s -p --standard=modules\customfields\phpcs.xml modules\customfields
phpstan analyse -c modules\customfields\phpstan.neon
```

Tipik sorunlar ve ipuçları:
- `tests/bootstrap.php` `XOOPS_ROOT_PATH` sabitini proje köküne işaret edecek şekilde otomatik belirler; ortamınıza göre gerekirse güncelleyin.
- `fileinfo` eklentisi yoksa MIME doğrulaması atlanır, ancak uzantı kontrolü yine de uygulanır.
