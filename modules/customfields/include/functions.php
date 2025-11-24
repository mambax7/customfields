<?php
/**
 * İlave alanları diğer modüllere entegre etmek için yardımcı fonksiyonlar
 */

use  XoopsModules\Customfields\{
    CustomField,
    CustomFieldHandler,
    CustomFieldData,
    CustomFieldDataHandler,
    Helper,
    FieldTypes,
    Logger
};




if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

// Ensure core utility classes are available when functions.php is included directly (as in unit tests)
if (!class_exists('XoopsModules\\Customfields\\FieldTypes', false)) {
    $cfFieldTypes = XOOPS_ROOT_PATH . '/modules/customfields/class/FieldTypes.php';
    if (file_exists($cfFieldTypes)) {
        require_once $cfFieldTypes;
    }
}

/**
 * Small HTML escape helper to ensure consistent escaping across the module.
 * Keep global function name for BC and ease of use.
 *
 * @param mixed $value
 * @return string
 */
function customfields_esc($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Build a safe absolute URL from a module-relative path (e.g. 'uploads/customfields/file.jpg').
 * Ensures no leading '../' path traversal and normalizes slashes.
 *
 * @param string $relative
 * @return string Absolute URL suitable for href/src attributes (must still be escaped on output)
 */
function customfields_url($relative)
{
    $rel = (string)$relative;
    // Strip any scheme and host if accidentally provided
    $rel = preg_replace('#^[a-z]+://[^/]+#i', '', $rel);
    // Remove leading slashes and any ../ sequences
    $rel = ltrim($rel, "/\\");
    $rel = preg_replace('#\.\./#', '', $rel);
    return rtrim((string)XOOPS_URL, '/') . '/' . $rel;
}

/**
 * Merge provided context with safe defaults. Allows dependency injection while preserving BC.
 * Supported keys:
 *  - 'db'             => XoopsDatabase instance
 *  - 'uploadService'  => \XoopsModules\Customfields\UploadService instance
 *  - 'validator'      => \XoopsModules\Customfields\Validator instance
 *  - 'logger'         => PSR-3 logger (passed to Logger::setLogger externally)
 *
 * @param array $ctx
 * @return array
 */
function customfields_context(array $ctx = array())
{
    global $xoopsDB;
    $out = $ctx;
    if (!isset($out['db'])) {
        $out['db'] = isset($GLOBALS['customfields_db_stub']) && is_object($GLOBALS['customfields_db_stub'])
            ? $GLOBALS['customfields_db_stub']
            : (isset($xoopsDB) ? $xoopsDB : null);
    }
    if (!isset($out['uploadService']) && isset($GLOBALS['customfields_upload_service']) && is_object($GLOBALS['customfields_upload_service'])) {
        $out['uploadService'] = $GLOBALS['customfields_upload_service'];
    }
    if (!isset($out['validator']) && isset($GLOBALS['customfields_validator']) && is_object($GLOBALS['customfields_validator'])) {
        $out['validator'] = $GLOBALS['customfields_validator'];
    }
    return $out;
}

/**
 * Database helper: execute a write query with basic error logging via Logger.
 * Returns the raw driver result or false.
 *
 * @param string $sql
 * @return mixed
 */
function customfields_dbExec($sql)
{
    global $xoopsDB;
    if (!is_object($xoopsDB) || !method_exists($xoopsDB, 'queryF')) {
        Logger::error('DB exec failed: xoopsDB not available');
        return false;
    }
    $res = $xoopsDB->queryF($sql);
    if ($res === false) {
        Logger::error('DB exec error for SQL: ' . $sql);
    }
    return $res;
}

/**
 * Best-effort transaction helpers. They try common dialects and return bool.
 */
function customfields_dbBegin()
{
    global $xoopsDB;
    if (!is_object($xoopsDB) || !method_exists($xoopsDB, 'queryF')) {
        return false;
    }
    return (bool)($xoopsDB->queryF('START TRANSACTION') || $xoopsDB->queryF('BEGIN'));
}

function customfields_dbCommit()
{
    global $xoopsDB;
    if (!is_object($xoopsDB) || !method_exists($xoopsDB, 'queryF')) {
        return false;
    }
    return (bool)$xoopsDB->queryF('COMMIT');
}

function customfields_dbRollback()
{
    global $xoopsDB;
    if (!is_object($xoopsDB) || !method_exists($xoopsDB, 'queryF')) {
        return false;
    }
    return (bool)$xoopsDB->queryF('ROLLBACK');
}

/**
 * Handler sınıflarını yükle
 */
function customfields_loadHandlers()
{
    static $loaded = false;
    
    if (!$loaded) {
        require_once XOOPS_ROOT_PATH . '/modules/customfields/class/CustomField.php';
        require_once XOOPS_ROOT_PATH . '/modules/customfields/class/CustomFieldHandler.php';
        require_once XOOPS_ROOT_PATH . '/modules/customfields/class/CustomFieldData.php';
        // Utility classes (loaded on demand below as well)
        $validatorPath = XOOPS_ROOT_PATH . '/modules/customfields/class/Validator.php';
        if (file_exists($validatorPath)) {
            require_once $validatorPath;
        }
        $uploadServicePath = XOOPS_ROOT_PATH . '/modules/customfields/class/UploadService.php';
        if (file_exists($uploadServicePath)) {
            require_once $uploadServicePath;
        }
        $configPath = XOOPS_ROOT_PATH . '/modules/customfields/class/Config.php';
        if (file_exists($configPath)) {
            require_once $configPath;
        }
        $fieldTypesPath = XOOPS_ROOT_PATH . '/modules/customfields/class/FieldTypes.php';
        if (file_exists($fieldTypesPath)) {
            require_once $fieldTypesPath;
        }
        $loggerPath = XOOPS_ROOT_PATH . '/modules/customfields/class/Logger.php';
        if (file_exists($loggerPath)) {
            require_once $loggerPath;
        }
        // Renderer classes (optional, for decoupled formatting)
        $rendererBase = XOOPS_ROOT_PATH . '/modules/customfields/class/Renderer/';
        foreach (array(
            'RendererFactory.php',
            'TextRenderer.php',
            'TextareaRenderer.php',
            'SelectRenderer.php',
            'RadioRenderer.php',
            'CheckboxRenderer.php',
            'DateRenderer.php',
            'ImageRenderer.php',
            'FileRenderer.php'
        ) as $rf) {
            $p = $rendererBase . $rf;
            if (file_exists($p)) {
                require_once $p;
            }
        }
        $loaded = true;
    }
}

/**
 * CustomFieldHandler'ı al
 */
function customfields_getFieldHandler(array $ctx = array())
{
    // Allow unit tests to inject a stub handler via global for isolation
    if (isset($GLOBALS['customfields_fieldHandler_stub']) && is_object($GLOBALS['customfields_fieldHandler_stub'])) {
        return $GLOBALS['customfields_fieldHandler_stub'];
    }
    $ctx = customfields_context($ctx);
    customfields_loadHandlers();
    return new CustomFieldHandler($ctx['db']);
}

/**
 * CustomFieldDataHandler'ı al
 */
function customfields_getDataHandler(array $ctx = array())
{
    // Allow unit tests to inject a stub handler via global for isolation
    if (isset($GLOBALS['customfields_dataHandler_stub']) && is_object($GLOBALS['customfields_dataHandler_stub'])) {
        return $GLOBALS['customfields_dataHandler_stub'];
    }
    $ctx = customfields_context($ctx);
    customfields_loadHandlers();
    return new CustomFieldDataHandler($ctx['db']);
}

function customfields_getFields($module_name, $show_in_form_only = false, array $ctx = array())
{
    $fieldHandler = customfields_getFieldHandler($ctx);
    return $fieldHandler->getFieldsByModule($module_name, $show_in_form_only);
}

function customfields_getData($module_name, $item_id, array $ctx = array())
{
    $dataHandler = customfields_getDataHandler($ctx);
    return $dataHandler->getItemData($module_name, $item_id);
}

function customfields_renderForm($module_name, $item_id = 0, array $ctx = array())
{
    $html = '';
    $fields = customfields_getFields($module_name, true, $ctx);
    
    if (count($fields) == 0) {
        return $html;
    }
    
    $data = array();
    if ($item_id > 0) {
        $data = customfields_getData($module_name, $item_id, $ctx);
    }
    
    $fieldHandler = customfields_getFieldHandler($ctx);
    
    $html .= '<div class="customfields-container">';
    $html .= '<fieldset><legend>İlave Bilgiler</legend>';

    // CSRF token (XOOPS Security if available)
    if (isset($GLOBALS['xoopsSecurity']) && is_object($GLOBALS['xoopsSecurity'])) {
        $token = $GLOBALS['xoopsSecurity']->createToken();
        $html .= '<input type="hidden" name="XOOPS_TOKEN_REQUEST" value="' . customfields_esc($token) . '">';
    }
    
    foreach ($fields as $field) {
        $field_id = $field->getVar('field_id');
        $value = isset($data[$field_id]) ? $data[$field_id] : '';
        
        $html .= '<div class="form-group customfield-item">';
        $html .= '<label for="customfield_' . (int)$field_id . '">';
        $html .= customfields_esc($field->getVar('field_title'));
        if ($field->getVar('required')) {
            $html .= ' <span class="required">*</span>';
        }
        $html .= '</label>';
        
        if ($field->getVar('field_description')) {
            $html .= '<p class="help-block">' . customfields_esc($field->getVar('field_description')) . '</p>';
        }
        
        $html .= $fieldHandler->renderField($field, $value);
        $html .= '</div>';
    }
    
    $html .= '</fieldset>';
    $html .= '</div>';
    
    return $html;
}

function customfields_saveData($module_name, $item_id, array $ctx = array())
{
    global $xoopsDB;
    $ctx = customfields_context($ctx);
    
    // Basic sanitization/normalization
    $module_name = preg_replace('/[^a-z0-9_]/i', '', (string)$module_name);
    $item_id = (int)$item_id;

    // Optional whitelist of allowed module names (set via define('CUSTOMFIELDS_ALLOWED_MODULES', ['publisher', ...]))
    if (defined('CUSTOMFIELDS_ALLOWED_MODULES') && is_array(CUSTOMFIELDS_ALLOWED_MODULES) && !empty(CUSTOMFIELDS_ALLOWED_MODULES)) {
        if (!in_array($module_name, CUSTOMFIELDS_ALLOWED_MODULES, true)) {
            error_log('CustomFields saveData blocked for non-whitelisted module: ' . $module_name);
            return false;
        }
    }

    // CSRF validation if XOOPS Security is available
    if (isset($GLOBALS['xoopsSecurity']) && is_object($GLOBALS['xoopsSecurity'])) {
        if (!$GLOBALS['xoopsSecurity']->check()) {
            error_log('CustomFields saveData CSRF check failed.');
            return false;
        }
    }

    // Permission checks (conservative default: require logged-in user unless explicitly allowed)
    // You can override defaults via:
    //   define('CUSTOMFIELDS_ALLOW_ANON_SAVE', true);
    //   define('CUSTOMFIELDS_ADMIN_ONLY_MODULES', ['sensitive_module']);
    $isAnonAllowed = defined('CUSTOMFIELDS_ALLOW_ANON_SAVE') && CUSTOMFIELDS_ALLOW_ANON_SAVE === true;
    $adminOnlyModules = defined('CUSTOMFIELDS_ADMIN_ONLY_MODULES') && is_array(CUSTOMFIELDS_ADMIN_ONLY_MODULES)
        ? CUSTOMFIELDS_ADMIN_ONLY_MODULES : [];

    $xoopsUser = isset($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser'] : null;
    if (!$isAnonAllowed && (!is_object($xoopsUser))) {
        error_log('CustomFields saveData blocked: anonymous user not permitted.');
        return false;
    }
    if (in_array($module_name, $adminOnlyModules, true)) {
        if (!is_object($xoopsUser) || !defined('XOOPS_GROUP_ADMIN') || !in_array(XOOPS_GROUP_ADMIN, $xoopsUser->getGroups(), true)) {
            error_log('CustomFields saveData blocked: admin-only module and user is not admin.');
            return false;
        }
    }

    // Ensure Logger is available (lazy-load for unit tests without full autoloader)
    if (!class_exists('XoopsModules\\Customfields\\Logger', false)) {
        $loggerPath = XOOPS_ROOT_PATH . '/modules/customfields/class/Logger.php';
        if (file_exists($loggerPath)) {
            require_once $loggerPath;
        }
    }

    // Debug log
    Logger::info("CustomFields saveData called: module=$module_name, item_id=$item_id");
    Logger::info("POST data: " . print_r($_POST, true));
    
    $fields = customfields_getFields($module_name, false, $ctx);
    $dataHandler = customfields_getDataHandler($ctx);
    
    Logger::info("Found " . count($fields) . " fields for module $module_name");
    
    // Lazy load validator if present
    if (!class_exists('XoopsModules\\Customfields\\Validator', false)) {
        $validatorFile = XOOPS_ROOT_PATH . '/modules/customfields/class/Validator.php';
        if (file_exists($validatorFile)) {
            require_once $validatorFile;
        }
    }

    // Create or get validator instance if available
    $validator = customfields_getValidator($ctx);

    // Begin transaction if supported (best-effort)
    $inTransaction = customfields_dbBegin();

    $allOk = true;
    foreach ($fields as $field) {
        $field_id = $field->getVar('field_id');
        $field_name = 'customfield_' . $field_id;
        
        Logger::info("Processing field: $field_name (ID: $field_id)");
        
        if (isset($_POST[$field_name])) {
            $value = $_POST[$field_name];
            
            Logger::info("Field $field_name has value: $value");
            
            if (in_array($field->getVar('field_type'), array(FieldTypes::IMAGE, FieldTypes::FILE), true) && isset($_FILES[$field_name])) {
                $value = customfields_handleFileUpload($field_name, $field->getVar('field_type'), $ctx);
                Logger::info("File upload result: $value");
            } else {
                // Apply validation/normalization per field type when validator is available
                if ($validator) {
                    $type = (string)$field->getVar('field_type');
                    switch ($type) {
                        case FieldTypes::TEXT:
                            $value = $validator->validateText((string)$value, 255);
                            break;
                        case FieldTypes::TEXTAREA:
                            $value = $validator->validateText((string)$value, 5000);
                            break;
                        case FieldTypes::DATE:
                            $canon = $validator->validateDate((string)$value, 'Y-m-d');
                            $value = $canon !== null ? $canon : '';
                            break;
                        case FieldTypes::SELECT:
                            $opts = (array)$field->getOptions();
                            $keys = array_keys($opts);
                            $value = $validator->validateOption((string)$value, $keys);
                            break;
                        case FieldTypes::CHECKBOX:
                            $opts = (array)$field->getOptions();
                            $keys = array_keys($opts);
                            $value = $validator->normalizeCheckbox((string)$value, $keys);
                            break;
                        case FieldTypes::EDITOR:
                            $value = $validator->sanitizeEditor((string)$value);
                            break;
                        case FieldTypes::RADIO:
                            $opts = (array)$field->getOptions();
                            $keys = array_keys($opts);
                            $value = $validator->validateOption((string)$value, $keys);
                            break;
                        default:
                            // leave as-is for unknown types (BC)
                            $value = (string)$value;
                    }
                } else {
                    // Fallback basic normalization without validator
                    $value = is_array($value) ? implode(',', $value) : (string)$value;
                }
            }
            
            $result = $dataHandler->saveItemData($module_name, $item_id, $field_id, $value);
            Logger::info("Save result for field $field_id: " . ($result ? 'SUCCESS' : 'FAILED'));
            if (!$result) {
                $allOk = false;
                break;
            }
        } else {
            Logger::info("Field $field_name NOT in POST data");
        }
    }

    if ($inTransaction) {
        if ($allOk) {
            customfields_dbCommit();
        } else {
            customfields_dbRollback();
        }
    }
    
    return $allOk;
}

/**
 * File upload handling with injectable service (via context or global stub).
 * @param string $field_name
 * @param string $field_type
 * @param array  $ctx
 * @return string
 */
function customfields_handleFileUpload($field_name, $field_type, array $ctx = array())
{
    // Delegate to the UploadService for unified handling
    if (!class_exists('XoopsModules\\Customfields\\UploadService', false)) {
        $svcFile = XOOPS_ROOT_PATH . '/modules/customfields/class/UploadService.php';
        if (file_exists($svcFile)) {
            require_once $svcFile;
        }
    }
    if (class_exists('XoopsModules\\Customfields\\UploadService')) {
        $svc = customfields_getUploadService($ctx);
        return $svc->handle($field_name, $field_type);
    }
    // Fallback: no service available
    return '';
}

function customfields_deleteData($module_name, $item_id)
{
    global $xoopsDB;
    
    $module = is_object($xoopsDB) && method_exists($xoopsDB, 'quoteString')
        ? $xoopsDB->quoteString($module_name)
        : ("'" . addslashes((string)$module_name) . "'");
    $id = (int)$item_id;
    $sql = "DELETE FROM " . $xoopsDB->prefix('customfields_data') . "
            WHERE target_module = " . $module . "
            AND item_id = " . $id;
    return customfields_dbExec($sql);
}

function customfields_prepareForTemplate($module_name, $item_id)
{
    $fields = customfields_getFields($module_name);
    $data = customfields_getData($module_name, $item_id);
    
    $result = array();
    
    foreach ($fields as $field) {
        $field_id = $field->getVar('field_id');
        $value = isset($data[$field_id]) ? $data[$field_id] : '';
        
        $field_data = array(
            'id' => $field_id,
            'name' => $field->getVar('field_name'),
            'title' => $field->getVar('field_title'),
            'type' => $field->getVar('field_type'),
            'value' => $value,
            'formatted_value' => customfields_formatValue($field, $value)
        );
        
        $result[$field->getVar('field_name')] = $field_data;
    }
    
    return $result;
}

function customfields_formatValue($field, $value)
{
    if (empty($value)) {
        return '';
    }
    
    $type = $field->getVar('field_type');

    // Try decoupled renderer first (for supported types)
    if (class_exists('XoopsModules\\Customfields\\Renderer\\RendererFactory')) {
        $rendered = \XoopsModules\Customfields\Renderer\RendererFactory::render($field, $value);
        if ($rendered !== null) {
            return $rendered;
        }
    }
    
    switch ($type) {
        case FieldTypes::IMAGE:
            $src = customfields_url($value);
            $alt = customfields_esc($field->getVar('field_title'));
            return '<img src="' . customfields_esc($src) . '" alt="' . $alt . '" title="' . $alt . '" class="customfield-image" loading="lazy">';
            
        case FieldTypes::FILE:
            $href = customfields_url($value);
            $label = customfields_esc(basename((string)$value));
            return '<a href="' . customfields_esc($href) . '" target="_blank" rel="noopener">' . $label . '</a>';
            
        case FieldTypes::DATE:
            $ts = @strtotime((string)$value);
            if ($ts === false) {
                return '';
            }
            // Use module-configured display format
            if (!class_exists('XoopsModules\\Customfields\\Config', false)) {
                $cfg = XOOPS_ROOT_PATH . '/modules/customfields/class/Config.php';
                if (file_exists($cfg)) {
                    require_once $cfg;
                }
            }
            $fmt = class_exists('XoopsModules\\Customfields\\Config', false)
                ? \XoopsModules\Customfields\Config::getDisplayDateFormat()
                : 'd.m.Y';
            return date($fmt, $ts);
            
        case FieldTypes::CHECKBOX:
            $values = explode(',', $value);
            $options = $field->getOptions();
            $labels = array();
            foreach ($values as $val) {
                if (isset($options[$val])) {
                    $labels[] = customfields_esc($options[$val]);
                }
            }
            return implode(', ', $labels);
            
        case FieldTypes::SELECT:
        case FieldTypes::RADIO:
            $options = $field->getOptions();
            return isset($options[$value]) ? customfields_esc($options[$value]) : customfields_esc($value);
            
        case FieldTypes::TEXTAREA:
            return nl2br(htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
            
        case FieldTypes::EDITOR:
            return $value;
            
        default:
            return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

/**
 * Return UploadService instance, allowing injection through context or a global stub.
 * @param array $ctx
 * @return \XoopsModules\Customfields\UploadService
 */
function customfields_getUploadService(array $ctx = array())
{
    if (isset($ctx['uploadService']) && is_object($ctx['uploadService'])) {
        return $ctx['uploadService'];
    }
    if (isset($GLOBALS['customfields_upload_service']) && is_object($GLOBALS['customfields_upload_service'])) {
        return $GLOBALS['customfields_upload_service'];
    }
    return new \XoopsModules\Customfields\UploadService();
}

/**
 * Return Validator instance if available, allowing injection through context or global stub.
 * @param array $ctx
 * @return \XoopsModules\Customfields\Validator|null
 */
function customfields_getValidator(array $ctx = array())
{
    if (isset($ctx['validator']) && is_object($ctx['validator'])) {
        return $ctx['validator'];
    }
    if (isset($GLOBALS['customfields_validator']) && is_object($GLOBALS['customfields_validator'])) {
        return $GLOBALS['customfields_validator'];
    }
    if (class_exists('XoopsModules\\Customfields\\Validator')) {
        return new \XoopsModules\Customfields\Validator();
    }
    return null;
}

/**
 * Bir item için tüm customfields verilerini al
 * 
 * @param string $module_name Modül adı (örn: 'publisher')
 * @param int $item_id Item ID
 * @return array Alan adı => değer dizisi
 */
function customfields_getItemData($module_name, $item_id)
{
    if (empty($module_name) || empty($item_id)) {
        return array();
    }
    
    $dataHandler = customfields_getDataHandler();
    return $dataHandler->getItemDataArray($module_name, $item_id);
}
