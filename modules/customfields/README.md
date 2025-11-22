# XOOPS İlave Alanlar Modülü v1.0.0

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
