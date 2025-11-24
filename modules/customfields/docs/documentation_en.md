# CustomFields Module Documentation (English)

---

# XOOPS CustomFields Module v1.1.1

Complete Module Documentation**

---

# **1. Introduction**

**CustomFields** is a powerful extension module for **XOOPS CMS** that lets administrators add **dynamic, configurable custom fields** to any XOOPS module, without modifying core code.

It is ideal for:

* News/article metadata
* Product attributes
* User-submitted content
* SEO fields
* Flexible form extensions

The module requires no PHP changes in most cases—only two integration calls added to the target module.

---

# **2. Features Overview**

### ✔ Administrator Features

* Add unlimited custom fields to any module
* 9 field types:

    * Text
    * Textarea
    * Editor (HTML/WYSIWYG)
    * Image upload
    * File upload
    * Select
    * Checkbox
    * Radio
    * Date picker
* Module-based field assignment
* Ordering and visibility controls
* Integrated file upload manager
* Validation support
* Built-in test utilities

### ✔ Developer Features

* Simple two-function API:
  `customfields_renderForm()` and `customfields_saveData()`
* Robust data retrieval API for templates and display
* Dedicated field renderers (HTML, safe escaping, URLs)
* Middleware-style value formatting
* Configurable date formats
* Customizable upload rules
* Strong security context (XSS, MIME checks, safe paths)

### ✔ Smrty Template Features

* `{customfields ...}` to load all fields
* `{customfield ...}` to load a single field
* Supports assign, loops, and conditional rendering

---

# **3. Installation**

### **3.1 Requirements**

* XOOPS 2.5.x or later
* PHP 7.4+ (recommended: PHP 8.x)
* MySQL 5.7+
* Write permissions to:

    * `/uploads/customfields/`
    * `/uploads/customfields/images/` (auto-created)

### **3.2 Steps**

1. Upload the `customfields` folder into `/modules/`
2. In XOOPS Admin → **Modules**, install **Custom Fields**
3. Ensure write permissions:

```
uploads/customfields/    chmod 755
uploads/customfields/images/   chmod 755
```

4. (Optional) Configure advanced settings via constants (see section 11)

---

# **4. Administrator Guide**

## **4.1 Field Management**

Path:

```
Admin → Custom Fields → Field Management
```

Actions allowed:

| Action                | Description                               |
| --------------------- | ----------------------------------------- |
| **Add field**         | Create a new field for a module           |
| **Edit field**        | Modify label, description, type, ordering |
| **Delete field**      | Remove field and associated data          |
| **Sort fields**       | Controls display order                    |
| **Configure options** | Used for select/checkbox/radio            |

Each field includes:

* Field Name (internal variable)
* Field Title (user-visible)
* Description
* Type
* Required toggle
* Show-in-form toggle
* Order

---

# **5. Developer Integration Guide**

Integration requires **only three places**:

---

## **5.1 Displaying the Form (Admin-side)**

Add to a module's admin form file, e.g. `news/admin/item.php`:

```php
include_once XOOPS_ROOT_PATH . '/modules/customfields/include/functions.php';
echo customfields_renderForm('news', $storyid);
```

---

## **5.2 Saving the Field Data**

Immediately after inserting/updating an item:

```php
include_once XOOPS_ROOT_PATH . '/modules/customfields/include/functions.php';

if ($newstoryid = $story_handler->insert($story)) {
    customfields_saveData('news', $newstoryid);
    redirect_header("item.php", 2, "Saved successfully");
}
```

🟦 **Important:**
`customfields_saveData()` must be called **before redirect_header()**.

---

## **5.3 Display in Front-End Templates**

### **Option 1 — Display all fields**

```smarty
<{customfields module="news" item_id=$story.id assign="custom_fields"}>

<{if $custom_fields}>
<div class="custom-fields">
    <{foreach from=$custom_fields item=field}>
        <div class="custom-field-item">
            <strong><{$field.title}>:</strong>
            <div class="cf-value"><{$field.formatted_value}></div>
        </div>
    <{/foreach}>
</div>
<{/if}>
```

### **Option 2 — Display a specific field**

```smarty
<{customfield module="news" item_id=$story.id name="ek_resim"}>
```

---

# **6. Data Model**

### **6.1 Tables**

#### **customfields_fields**

Stores field definitions.

#### **customfields_data**

Stores item-specific field values:

| Column        | Description                 |
| ------------- | --------------------------- |
| data_id       | Primary key                 |
| field_id      | ID from customfields_fields |
| item_id       | ID of target module item    |
| target_module | Module dirname              |
| field_value   | Stored value                |
| created       | Timestamp                   |

---

# **7. Renderer Architecture**

Every field type uses a dedicated renderer:

```
class/Renderer/
  ├── TextRenderer.php
  ├── TextareaRenderer.php
  ├── EditorRenderer.php
  ├── SelectRenderer.php
  ├── CheckboxRenderer.php
  ├── RadioRenderer.php
  ├── DateRenderer.php
  ├── ImageRenderer.php
  └── FileRenderer.php
```

### Responsibilities:

* HTML-safe escaping
* Secure URLs
* Type-based formatting
* Optional date formatting
* Optional file-size formatting

Custom renderers can be added.

---

# **8. API Reference**

All functions reside in:
`modules/customfields/include/functions.php`

---

## **8.1 Get Fields**

```php
$fields = customfields_getFields($module, $show_in_form_only = false);
```

---

## **8.2 Get Data**

```php
$data = customfields_getData($module, $item_id);
```

---

## **8.3 Render Form**

```php
echo customfields_renderForm($module, $item_id = 0);
```

---

## **8.4 Save Data**

```php
customfields_saveData($module, $item_id);
```

---

## **8.5 Delete Data**

```php
customfields_deleteData($module, $item_id);
```

---

## **8.6 Prepare for Template**

```php
$template_data = customfields_prepareForTemplate($module, $item_id);
```

Returns fully formatted fields with renderer output.

---

# **9. Smarty Plugin Reference**

CustomFields installs two template plugins:

| Plugin         | Purpose              |
| -------------- | -------------------- |
| `customfields` | Fetch all fields     |
| `customfield`  | Fetch a single field |

---

### Example: All fields

```smarty
<{customfields module="publisher" item_id=$item.id assign="cf"}>
```

### Example: Single field

```smarty
<{customfield module="publisher" item_id=$item.id name="video_url"}>
```

---

# **10. Security Guide**

## **10.1 File Upload Security**

* MIME detection (via `fileinfo`)
* Extension whitelist
* Path sanitization
* Randomized filenames
* Forbidden script execution in uploads

The included `.htaccess` blocks scripts:

```
<FilesMatch "\.(php|phar|cgi|pl|asp)$">
    deny from all
</FilesMatch>
```

Nginx equivalent included in documentation.

---

## **10.2 Form Security**

* XOOPS Security Token (`$xoopsSecurity`)
* Input sanitization
* SQL injection protection via `$xoopsDB->quoteString()`
* Safe escaping in renderer

---

# **11. Configuration Constants**

Define these in:

* `mainfile.php`
* A module bootstrap
* A XOOPS preload

---

### **Upload limits**

```php
define('CUSTOMFIELDS_MAX_UPLOAD_SIZE', 5 * 1024 * 1024);
```

---

### **Allowed modules**

```php
define('CUSTOMFIELDS_ALLOWED_MODULES', ['publisher', 'news']);
```

---

### **Anonymous save**

```php
define('CUSTOMFIELDS_ALLOW_ANON_SAVE', false);
```

---

### **Admin-only modules**

```php
define('CUSTOMFIELDS_ADMIN_ONLY_MODULES', ['sensitive_module']);
```

---

### **Date display format**

```php
define('CUSTOMFIELDS_DISPLAY_DATE_FORMAT', 'Y-m-d');
```

---

### **Allowed file extensions and MIME types**

```php
define('CUSTOMFIELDS_ALLOWED_IMAGE_EXT', [...]);
define('CUSTOMFIELDS_ALLOWED_FILE_EXT', [...]);
define('CUSTOMFIELDS_ALLOWED_IMAGE_MIME', [...]);
define('CUSTOMFIELDS_ALLOWED_FILE_MIME', [...]);
```

---

# **12. Troubleshooting & Diagnostics**

CustomFields includes three diagnostic scripts:

| Script                       | Purpose                                   |
| ---------------------------- | ----------------------------------------- |
| `test_item_customfields.php` | Tests integration with Publisher item.php |
| `test_publisher.php`         | Tests presence of module and data         |
| `test_publisher_save.php`    | Performs manual save and DB check         |

These help identify:

* Missing integration
* Incorrect save order
* Cache issues
* Renderer problems
* Missing field definitions

---

# **13. Clearing Cache**

If item.php was recently updated:

Steps:

1. Admin → System Module → General Settings
2. Click “Clear Cache”
3. Click “Update Templates”
4. Refresh test page

---

# **14. Developer Tools: PHPUnit & Static Analysis**

### **Run tests**

```
xoops_lib/vendor/bin/phpunit -c modules/customfields/phpunit.xml.dist
```

### **Run PHPCS**

```
phpcs -s -p --standard=modules/customfields/phpcs.xml modules/customfields
```

### **Run PHPStan**

```
phpstan analyse -c modules/customfields/phpstan.neon
```

---

# **15. Directory Structure**

```
customfields/
├── admin/                 
├── class/
│   ├── Renderer/
│   ├── CustomField.php
│   ├── CustomFieldHandler.php
│   └── CustomFieldData.php
├── include/
│   ├── functions.php
│   └── install.php
├── language/
│   ├── english/
│   └── turkish/
├── sql/
├── assets/
│   └── css/
└── xoops_version.php
```

---

# **16. Versioning & Backward Compatibility**

* All existing fields continue to work after upgrade
* Renderer architecture is extendable
* Config constants override defaults safely
* Compatible with PHP 5.6 → PHP 8.4
* Compatible with all XOOPS 2.5.x versions

---

# **17. License**

GPL v2.0

---

# **18. Developer**

**Eren – XOOPS Türkiye**

---

# **19. Support**

XOOPS Türkiye Forum
XOOPS.org Support Forum

---

# **20. Appendix: Quick Reference**

## **Quick Integration Checklist**

* [ ] Add form integration
* [ ] Add save integration
* [ ] Add delete integration
* [ ] Clear cache
* [ ] Confirm via test pages

## **Quick Template Snippet**

```smarty
<{customfields module=$module item_id=$id assign="cf"}>
```

---

If you want, I can now:

📄 Generate individual Markdown files
📁 Package them into `/docs/`
📄 Produce a PDF manual
📘 Generate English & Turkish editions
📦 Create a ZIP file with full documentation

Just tell me what format you prefer.
