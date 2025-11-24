Here is the full English translation of your document.
I preserved structure, emojis, formatting, and all technical explanations exactly as intended.

---

# **XOOPS Custom Fields Module v1.1.1**

A comprehensive custom-field management module developed for XOOPS CMS.

---

## 📋 Features

* ✅ Add custom fields to any module
* ✅ 9 different field types (text, textarea, editor, image, file, select, checkbox, radio, date)
* ✅ Easy integration
* ✅ Smarty template support
* ✅ File upload support
* ✅ Validation rules
* ✅ Turkish language support

---

## 🚀 Installation

1. Upload the `customfields` folder into `/modules/`
2. Open XOOPS admin panel → “Module Administration”
3. Install the **Custom Fields** module
4. Grant write permissions to `uploads/customfields/` (chmod 755)

---

## 📖 Usage

### **1. Field Definition**

Admin Panel → Custom Fields → Field Management

Select a target module and add new fields.

---

### **2. Module Integration**

#### **Show Form** (example: `news/admin/item.php`)

```php
<?php
// In the form creation section
include_once XOOPS_ROOT_PATH . '/modules/customfields/include/functions.php';
echo customfields_renderForm('news', $storyid);
?>
```

#### **Save Data**

```php
<?php
// After the save operation
include_once XOOPS_ROOT_PATH . '/modules/customfields/include/functions.php';

if ($newstoryid = $story_handler->insert($story)) {
    customfields_saveData('news', $newstoryid);
    redirect_header("item.php", 2, "News saved");
}
?>
```

#### **Display in Template** (example: `news/article.tpl`)

```smarty
{* Display all custom fields *}
{customfields module="news" item_id=$story.id assign="custom_fields"}

{if $custom_fields}
<div class="custom-fields-section">
    <h3>Additional Information</h3>
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
{* Display a single field *}
{customfield module="news" item_id=$story.id name="ek_resim"}
```

---

### **3. Delete Operation**

```php
<?php
// Delete custom-field data when an item is deleted
include_once XOOPS_ROOT_PATH . '/modules/customfields/include/functions.php';

if ($story_handler->delete($story)) {
    customfields_deleteData('news', $storyid);
    redirect_header("index.php", 2, "News deleted");
}
?>
```

---

## 🎨 Supported Field Types

| Type     | Description           | Usage             |
| -------- | --------------------- | ----------------- |
| text     | Single-line text      | Short text        |
| textarea | Multi-line text       | Long descriptions |
| editor   | HTML editor           | Rich content      |
| image    | Image upload          | Visual media      |
| file     | File upload           | PDF, DOC, etc.    |
| select   | Dropdown list         | Single choice     |
| checkbox | Multi-select checkbox | Multiple choices  |
| radio    | Radio button          | Single choice     |
| date     | Date picker           | Dates             |

---

## 🔧 API Functions

```php
// Get fields
$fields = customfields_getFields($module_name, $show_in_form_only = false);

// Get data
$data = customfields_getData($module_name, $item_id);

// Render form
$html = customfields_renderForm($module_name, $item_id = 0);

// Save data
customfields_saveData($module_name, $item_id);

// Delete data
customfields_deleteData($module_name, $item_id);

// Prepare for templates
$template_data = customfields_prepareForTemplate($module_name, $item_id);
```

---

## 📁 Directory Structure

```
customfields/
├── admin/              # Admin panel files
│   ├── index.php       # Overview
│   ├── fields.php      # Field manager
│   └── menu.php        # Admin menu
├── class/              # Classes
│   ├── CustomField.php
│   ├── CustomFieldHandler.php
│   └── CustomFieldData.php
├── include/            # Helper files
│   ├── functions.php   # Core functions
│   └── install.php     # Installer
├── sql/                # Database
│   └── mysql.sql
├── language/           # Language files
│   └── turkish/
├── assets/             # CSS/JS
│   └── css/
└── xoops_version.php   # Module definition
```

---

## 💡 Examples

### **Example 1: Integrating with the News Module**

1. Add an image field named `ek_resim` for the “news” module
2. Add form code to `news/admin/item.php`
3. Add display code to `news/article.tpl`

### **Example 2: Multi-option Field**

For select, checkbox, or radio:

* Use the “Options” section to enter value/label pairs
* Example:

    * value = "1", label = "Yes"
    * value = "0", label = "No"

---

## 🔒 Security

* File upload type validation
* SQL injection protection
* XSS protection
* Token validation

### **Advanced security and configuration**

(All optional — define them in `mainfile.php` or module bootstrap.)

```php
// Max upload size (bytes) – default: 5 MB
define('CUSTOMFIELDS_MAX_UPLOAD_SIZE', 5 * 1024 * 1024);

// Allowed target modules – empty = allow all
define('CUSTOMFIELDS_ALLOWED_MODULES', ['publisher', 'news']);

// Allow anonymous save (default: false)
define('CUSTOMFIELDS_ALLOW_ANON_SAVE', false);

// Modules where only admins can save
define('CUSTOMFIELDS_ADMIN_ONLY_MODULES', ['sensitive_module']);

// Date display format (PHP date() format) – default: 'd.m.Y'
define('CUSTOMFIELDS_DISPLAY_DATE_FORMAT', 'Y-m-d');

// Extension/MIME customization
define('CUSTOMFIELDS_ALLOWED_IMAGE_EXT', ['jpg','jpeg','png','gif','webp']);
define('CUSTOMFIELDS_ALLOWED_FILE_EXT', ['pdf','doc','docx','xls','xlsx','zip','rar','7z']);
define('CUSTOMFIELDS_ALLOWED_IMAGE_MIME', ['image/jpeg','image/png','image/gif','image/webp']);
define('CUSTOMFIELDS_ALLOWED_FILE_MIME', ['application/pdf']);
```

**Nginx rules** (equivalent of Apache `.htaccess`):

```nginx
location ^~ /uploads/customfields/ {
    default_type application/octet-stream;
    add_header X-Content-Type-Options nosniff always;

    # Prevent PHP/CGI/script execution
    location ~* \.(php|phtml|phps|phar|cgi|pl|asp|aspx)$ {
        return 403;
    }
}
```

---

## ⚙️ Configuration Accessors

The module uses helper accessors instead of directly reading constants:

```php
\XoopsModules\Customfields\Config::getUploadDir();
\XoopsModules\Customfields\Config::getMaxUploadSize();
\XoopsModules\Customfields\Config::getAllowedExtensions($type);
\XoopsModules\Customfields\Config::getAllowedMimes($type);
\XoopsModules\Customfields\Config::getDisplayDateFormat();
```

Dates are formatted by `DateRenderer` or the legacy `customfields_formatValue()`.

---

## 🖼️ Renderer Architecture

Each field type has its own renderer class:
Text, Textarea, Select, Radio, Checkbox, Date, Image, File.

Add your own renderer under `class/Renderer/` and register it in the factory.

All output is safely escaped and URLs are validated.

---

## 📑 Admin listing, pagination, and filters

* Supported in `admin/manage.php` and `admin/fields.php`
* Filter by module or type
* Optimized for large lists

---

## ⚙️ Requirements

* XOOPS 2.5.x or newer
* PHP 5.6+
* MySQL 5.5+

---

## 📝 License

GPL v2.0

---

## 👨‍💻 Developer

Eren — XOOPS Türkiye

---

## 🆘 Support

Contact XOOPS Türkiye Forums.

---

## 🧪 How to Run Tests

Requires PHPUnit installed under XOOPS vendor.

```
xoops_lib\vendor\bin\phpunit -c modules\customfields\phpunit.xml.dist
```

Or from module directory:

```
..\..\xoops_lib\vendor\bin\phpunit -c phpunit.xml.dist
```

Static analysis:

```
phpcs -s -p --standard=modules\customfields\phpcs.xml modules\customfields
phpstan analyse -c modules\customfields\phpstan.neon
```

**Tips:**

* `tests/bootstrap.php` auto-detects `XOOPS_ROOT_PATH`; update if needed
* If `fileinfo` PHP extension is missing, MIME checks are skipped (but extension checks still work)

---
