<?php

use XoopsModules\Customfields\{
    CustomField,
    CustomFieldHandler,
    CustomFieldData,
    CustomFieldDataHandler,
    Helper
};

require_once __DIR__ . '/admin_header.php';

// Use a Smarty template instead of inline HTML
$GLOBALS['xoopsOption']['template_main'] = 'customfields_admin_guide.tpl';

xoops_cp_header();

/**
 * No Turkish text here; all UI strings are handled via language constants
 * and used directly in the Smarty template.
 */

xoops_cp_footer();
