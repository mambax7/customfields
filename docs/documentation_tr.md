
# CustomFields Modül Dokümantasyonu (Türkçe)

## 1. Giriş

CustomFields modülü, XOOPS yöneticilerinin herhangi bir modüle dinamik, tipli ve doğrulamalı özel alanlar eklemesini sağlar...

## 2. Özellikler

- Sınırsız özel alan
- 9 alan tipi
- Smarty desteği
- Admin filtreleri
- Dosya ve resim yükleme desteği
- Renderer mimarisi
- Validasyon kuralları
- Güvenlik: XSS, CSRF, MIME doğrulama

## 3. Kurulum

1. `customfields` klasörünü `/modules/` dizinine yükleyin
2. Admin → Modül Yönetimi
3. Modülü kurun
4. `/uploads/customfields/` dizininin yazılabilir olduğundan emin olun (755)

## 4. Admin Arayüzü

### Alan Yönetimi

Admin → İlave Alanlar → Alan Yönetimi

Oluşturabileceğiniz alanlar:
- text
- textarea
- editor
- image
- file
- date
- select
- checkbox
- radio

### Seçenek Yönetimi

Select/checkbox/radio için değer/etiket tanımlanır.

## 5. Entegrasyon

### 5.1 Form Gösterme

```php
include_once XOOPS_ROOT_PATH . '/modules/customfields/include/functions.php';
echo customfields_renderForm('news', $itemid);
```

### 5.2 Veri Kaydetme

```php
customfields_saveData('news', $itemid);
```

### 5.3 Veri Silme

```php
customfields_deleteData('news', $itemid);
```

### 5.4 Template'te Gösterme

```smarty
<{customfields module="news" item_id=$story.id assign=custom_fields}>
```

## 6. Template Örnekleri

### Tüm alanları göster

```smarty
<{foreach from=$custom_fields item=field}>
    <div>
       <strong><{$field.title}>:</strong>
       <{$field.formatted_value}>
    </div>
<{/foreach}>
```

### Tek alan göster

```smarty
<{customfield module="news" item_id=$story.id name=ek_resim}>
```

## 7. API Referansı

### customfields_getFields()
Alan meta bilgilerini döndürür.

### customfields_renderForm()
Admin için HTML formu üretir.

### customfields_saveData()
POST verisini kaydeder.

### customfields_deleteData()
İlgili veriyi siler.

### customfields_getData()
Ham veriyi döndürür.

### customfields_prepareForTemplate()
Template için hazırlar.

## 8. Renderer Sistemi

Her alan tipi özel renderer sınıfıyla üretilir.

## 9. Yapılandırma Sabitleri

```php
define('CUSTOMFIELDS_MAX_UPLOAD_SIZE', 5242880);
define('CUSTOMFIELDS_ALLOWED_MODULES', ['news','publisher']);
define('CUSTOMFIELDS_ALLOW_ANON_SAVE', false);
define('CUSTOMFIELDS_DISPLAY_DATE_FORMAT', 'Y-m-d');
```

## 10. Güvenlik

- CSRF token kullanımı
- XSS kaçışı
- MIME ve uzantı doğrulama
- Klasör korumaları

## 11. Dizin Yapısı

...
