<?php

// Admin menu
define('_AM_CUSTOMFIELDS_OVERVIEW', 'Overview');
define('_AM_CUSTOMFIELDS_FIELDS', 'Field Management');
define('_AM_CUSTOMFIELDS_FIELD_SAVED', 'Field saved successfully');
define('_AM_CUSTOMFIELDS_FIELD_DELETED', 'Field deleted');
define('_AM_CUSTOMFIELDS_ERROR', 'An error occurred');
define('_AM_CUSTOMFIELDS_FIELD_NOT_FOUND', 'Field not found');

define('_AM_CUSTOMFIELDS_GUIDE_TITLE', '📚 Usage Guide');
define('_AM_CUSTOMFIELDS_GUIDE_SUBTITLE', 'Learn how to integrate custom fields into your modules');

define('_AM_CUSTOMFIELDS_QUICK_ACCESS', '⚡ Quick Access');
define('_AM_CUSTOMFIELDS_ADD_FIELD', '➕ Add New Field');
define('_AM_CUSTOMFIELDS_MANAGE_FIELDS', '📋 Manage Fields');

define('_AM_CUSTOMFIELDS_NEWS_INTEGRATION', '🗞️ News Module Integration');

define('_AM_CUSTOMFIELDS_STEP1_FORM_ADD_ADMIN', 'Add Form (Admin Panel)');
define('_AM_CUSTOMFIELDS_STEP1_DESC', 'In the News module admin panel, add custom fields to the add/edit news form.');

define('_AM_CUSTOMFIELDS_STEP2_SAVE_DATA', 'Save Data');
define('_AM_CUSTOMFIELDS_STEP2_DESC', 'When a news item is saved, save the custom field data as well.');
define('_AM_CUSTOMFIELDS_NEWS_SAVED', 'News saved');

define('_AM_CUSTOMFIELDS_STEP3_DELETE_OPTIONAL', 'Delete Operation (Optional)');
define('_AM_CUSTOMFIELDS_STEP3_DESC', 'When a news item is deleted, delete the related custom field data as well.');
define('_AM_CUSTOMFIELDS_NEWS_DELETED', 'News deleted');

define('_AM_CUSTOMFIELDS_STEP4_DISPLAY_TEMPLATE', 'Display in Template');
define('_AM_CUSTOMFIELDS_STEP4_DESC', 'Display custom fields on the news view page.');

define('_AM_CUSTOMFIELDS_EXTRA_INFO', 'Additional Info');

define('_AM_CUSTOMFIELDS_OTHER_MODULES', '🔌 Other Modules');
define('_AM_CUSTOMFIELDS_OTHER_MODULES_DESC', 'You can integrate it into any XOOPS module using the same approach:');

define('_AM_CUSTOMFIELDS_TIP_LABEL', '💡 Tip:');
define('_AM_CUSTOMFIELDS_TIP_TEXT', 'Simply replace places where you see \'news\' with your target module name. For example: \'publisher\', \'content\', \'articles\'.');

define('_AM_CUSTOMFIELDS_GENERAL_STEPS', '📋 General Steps:');
define('_AM_CUSTOMFIELDS_STEP_GENERAL_DEFINE_FIELDS', 'Define fields for the target module in the Custom Fields module.');
define('_AM_CUSTOMFIELDS_STEP_GENERAL_ADD_RENDERFORM', 'Add customfields_renderForm() to the target module\'s admin form page.');
define('_AM_CUSTOMFIELDS_STEP_GENERAL_ADD_SAVEDATA', 'Add customfields_saveData() to the save operation.');
define('_AM_CUSTOMFIELDS_STEP_GENERAL_ADD_SMARTY', 'Add the Smarty function to the template.');

define('_AM_CUSTOMFIELDS_TIPS_TITLE', '🎯 Tips');
define('_AM_CUSTOMFIELDS_WARNING_LABEL', '⚠️ Warning:');

define('_AM_CUSTOMFIELDS_TIP_NO_TR_CHARS', 'Do not use Turkish characters in field names.');
define('_AM_CUSTOMFIELDS_TIP_UPLOAD_WRITABLE', 'The uploads/customfields/ directory must be writable for file uploads.');
define('_AM_CUSTOMFIELDS_TIP_CLEAR_CACHE', 'Clear the cache after template changes.');

define('_AM_CUSTOMFIELDS_BEST_PRACTICES_TITLE', '✅ Best Practices:');
define('_AM_CUSTOMFIELDS_BP_MEANINGFUL_SHORT_NAMES', 'Keep field names meaningful and short (e.g. ek_resim).');
define('_AM_CUSTOMFIELDS_BP_TURKISH_TITLES_OK', 'You can use Turkish in labels (e.g. "Ek Resim").');
define('_AM_CUSTOMFIELDS_BP_REQUIRED_FIELDS', 'Choose required fields carefully.');
define('_AM_CUSTOMFIELDS_BP_ADD_DESCRIPTIONS', 'Provide descriptions to help users.');

define('_AM_CUSTOMFIELDS_API_FUNCTIONS_TITLE', '🔧 API Functions');



// Fields admin listing
define('_AM_CUSTOMFIELDS_TOKEN_ERROR', 'Security token error');
define('_AM_CUSTOMFIELDS_FIELD_SAVED_SUCCESS', 'Field saved successfully');
define('_AM_CUSTOMFIELDS_SAVE_ERROR', 'Error while saving the field');
//define('_AM_CUSTOMFIELDS_FIELD_DELETED', 'Field deleted');
define('_AM_CUSTOMFIELDS_DELETE_ERROR', 'Error while deleting the field');

define('_AM_CUSTOMFIELDS_FIELDS_HEADING', 'Custom Fields');
define('_AM_CUSTOMFIELDS_ADD_FIELD_LINK', 'Add New Field');

define('_AM_CUSTOMFIELDS_TABLE_ID', 'ID');
define('_AM_CUSTOMFIELDS_TABLE_MODULE', 'Module');
define('_AM_CUSTOMFIELDS_TABLE_FIELD_NAME', 'Field Name');
define('_AM_CUSTOMFIELDS_TABLE_FIELD_TITLE', 'Title');
define('_AM_CUSTOMFIELDS_TABLE_FIELD_TYPE', 'Type');
define('_AM_CUSTOMFIELDS_TABLE_ACTIONS', 'Actions');

define('_AM_CUSTOMFIELDS_ACTION_DELETE', 'Delete');
define('_AM_CUSTOMFIELDS_CONFIRM_DELETE', 'Are you sure you want to delete this field?');

define('_AM_CUSTOMFIELDS_NO_FIELDS', 'No fields have been added yet.');

// List filters and actions
define('_AM_CUSTOMFIELDS_FILTER_MODULE', 'Module filter:');
define('_AM_CUSTOMFIELDS_FILTER_TYPE', 'Type filter:');
define('_AM_CUSTOMFIELDS_FILTER_LIMIT', 'Items per page:');
define('_AM_CUSTOMFIELDS_FILTER_SUBMIT', 'Filter');
define('_AM_CUSTOMFIELDS_FILTER_RESET', 'Reset');

define('_AM_CUSTOMFIELDS_ACTION_EDIT', 'Edit');

// Field form
define('_AM_CUSTOMFIELDS_FIELD_FORM_HEADING_NEW', 'Add New Custom Field');
define('_AM_CUSTOMFIELDS_FIELD_FORM_HEADING_EDIT', 'Edit Custom Field');

define('_AM_CUSTOMFIELDS_FIELD_TARGET_MODULE', 'Target module');
define('_AM_CUSTOMFIELDS_FIELD_TARGET_MODULE_HELP', 'Module directory name (e.g. news, publisher, content).');

define('_AM_CUSTOMFIELDS_FIELD_NAME', 'Field name');
define('_AM_CUSTOMFIELDS_FIELD_NAME_HELP', 'Internal field identifier, no spaces or special characters.');

define('_AM_CUSTOMFIELDS_FIELD_TITLE', 'Title');
define('_AM_CUSTOMFIELDS_FIELD_TITLE_HELP', 'Label shown to users in forms.');

define('_AM_CUSTOMFIELDS_FIELD_DESCRIPTION', 'Description');
define('_AM_CUSTOMFIELDS_FIELD_DESCRIPTION_HELP', 'Optional help text shown to users.');

define('_AM_CUSTOMFIELDS_FIELD_TYPE', 'Field type');
define('_AM_CUSTOMFIELDS_FIELD_TYPE_HELP', 'Choose how the field will be rendered.');

define('_AM_CUSTOMFIELDS_FIELD_ORDER', 'Display order');

define('_AM_CUSTOMFIELDS_FIELD_REQUIRED', 'Required');
define('_AM_CUSTOMFIELDS_FIELD_REQUIRED_LABEL', 'This field is required.');

define('_AM_CUSTOMFIELDS_FIELD_SHOW_IN_FORM', 'Show in form');
define('_AM_CUSTOMFIELDS_FIELD_SHOW_IN_FORM_LABEL', 'Display this field in the module form.');

define('_AM_CUSTOMFIELDS_FIELD_OPTIONS', 'Options');
define('_AM_CUSTOMFIELDS_FIELD_OPTIONS_HELP', 'For select, checkbox and radio fields: define value/label pairs. Leave empty if not needed.');
define('_AM_CUSTOMFIELDS_FIELD_OPTION_VALUE', 'Option value');
define('_AM_CUSTOMFIELDS_FIELD_OPTION_LABEL', 'Option label');

define('_AM_CUSTOMFIELDS_SAVE_BUTTON', 'Save');
define('_AM_CUSTOMFIELDS_CANCEL_BUTTON', 'Cancel');


// Dashboard
define('_AM_CUSTOMFIELDS_DASHBOARD_TITLE', '🎨 Custom Fields Module');
define('_AM_CUSTOMFIELDS_DASHBOARD_SUBTITLE', 'Extend your modules with dynamic custom fields');

define('_AM_CUSTOMFIELDS_DASHBOARD_TOTAL_FIELDS_LABEL', 'Total Fields');
define('_AM_CUSTOMFIELDS_DASHBOARD_TOTAL_DATA_LABEL', 'Stored Records');
define('_AM_CUSTOMFIELDS_DASHBOARD_TOTAL_MODULES_LABEL', 'Integrated Modules');

define('_AM_CUSTOMFIELDS_DASHBOARD_QUICK_ACTIONS', 'Quick Actions');
define('_AM_CUSTOMFIELDS_DASHBOARD_ADD_FIELD_BTN', '➕ Add New Field');

define('_AM_CUSTOMFIELDS_DASHBOARD_MODULE_STATS_TITLE', '📊 Module Distribution');
define('_AM_CUSTOMFIELDS_DASHBOARD_TABLE_MODULE', 'Module');
define('_AM_CUSTOMFIELDS_DASHBOARD_TABLE_FIELD_COUNT', 'Field Count');
define('_AM_CUSTOMFIELDS_DASHBOARD_TABLE_ACTIONS', 'Actions');
define('_AM_CUSTOMFIELDS_DASHBOARD_FIELD_COUNT_SUFFIX', 'fields');

define('_AM_CUSTOMFIELDS_DASHBOARD_VIEW_BUTTON', 'View →');

define('_AM_CUSTOMFIELDS_DASHBOARD_EMPTY_TITLE', 'No Fields Defined Yet');
define('_AM_CUSTOMFIELDS_DASHBOARD_EMPTY_MESSAGE', 'Click the "Add New Field" button above to get started.');

define('_AM_CUSTOMFIELDS_DASHBOARD_QUICK_GUIDE_TITLE', '🚀 Quick Start Guide');

define('_AM_CUSTOMFIELDS_DASHBOARD_STEP1_TITLE', 'Define field:');
define('_AM_CUSTOMFIELDS_DASHBOARD_STEP1_DESC', 'Create a new field using the "Add New Field" button, for example ');

define('_AM_CUSTOMFIELDS_DASHBOARD_STEP2_TITLE', 'Integrate:');
define('_AM_CUSTOMFIELDS_DASHBOARD_STEP2_DESC', 'Add the integration code to the target module’s form file.');

define('_AM_CUSTOMFIELDS_DASHBOARD_STEP3_TITLE', 'Save:');
define('_AM_CUSTOMFIELDS_DASHBOARD_STEP3_DESC', 'Add the function below to the data save routine: ');

define('_AM_CUSTOMFIELDS_DASHBOARD_STEP4_TITLE', 'Display:');
define('_AM_CUSTOMFIELDS_DASHBOARD_STEP4_DESC', 'Add the display code to the template.');

define('_AM_CUSTOMFIELDS_DASHBOARD_DOC_LINK_TEXT', 'Click here for detailed documentation →');


// Manage fields (manage.php)
define('_AM_CUSTOMFIELDS_MANAGE_TITLE', '📋 Field Management');
define('_AM_CUSTOMFIELDS_MANAGE_SUBTITLE', 'View and manage defined fields');

define('_AM_CUSTOMFIELDS_MANAGE_ADD_FIELD_BTN', '➕ Add New Field');

define('_AM_CUSTOMFIELDS_MANAGE_FILTER_LABEL', '🔍 Filter:');
define('_AM_CUSTOMFIELDS_MANAGE_FILTER_ALL_MODULES', 'All modules');

define('_AM_CUSTOMFIELDS_MANAGE_TABLE_ORDER', 'Order');
define('_AM_CUSTOMFIELDS_MANAGE_TABLE_REQUIRED', 'Required');
define('_AM_CUSTOMFIELDS_MANAGE_TABLE_IN_FORM', 'In Form');

define('_AM_CUSTOMFIELDS_BADGE_YES', '✓ Yes');
define('_AM_CUSTOMFIELDS_BADGE_NO', '✗ No');

define('_AM_CUSTOMFIELDS_CONFIRM_DELETE_MANAGE',
    "Are you sure you want to delete this field?\n\nAll related data will be deleted as well!");

define('_AM_CUSTOMFIELDS_MANAGE_FOOTER_TOTAL_PREFIX', '📊 Total');
define('_AM_CUSTOMFIELDS_MANAGE_FOOTER_TOTAL_SUFFIX', 'fields shown');
define('_AM_CUSTOMFIELDS_MANAGE_FOOTER_FILTER_SUFFIX', 'module filtered');

define('_AM_CUSTOMFIELDS_MANAGE_EMPTY_TITLE', 'No Fields Defined Yet');
define('_AM_CUSTOMFIELDS_MANAGE_EMPTY_MESSAGE',
    'Click the "Add New Field" button to create your first field.');
define('_AM_CUSTOMFIELDS_MANAGE_EMPTY_CREATE_BTN', '➕ Create First Field');


// Generic messages reused
//define('_AM_CUSTOMFIELDS_TOKEN_ERROR', 'Security token error');
//define('_AM_CUSTOMFIELDS_FIELD_SAVED', 'Field saved successfully');
//define('_AM_CUSTOMFIELDS_SAVE_ERROR', 'Error while saving the field');
//define('_AM_CUSTOMFIELDS_FIELD_NOT_FOUND', 'Field not found');

// Add/Edit field form (add.php)
define('_AM_CUSTOMFIELDS_FIELD_FORM_TITLE_NEW', '➕ Add New Field');
define('_AM_CUSTOMFIELDS_FIELD_FORM_TITLE_EDIT', '✏️ Edit Field');
define('_AM_CUSTOMFIELDS_FIELD_FORM_SUBTITLE', 'Fill in the field details and save');

define('_AM_CUSTOMFIELDS_BREADCRUMB_HOME', '🏠 Home');
define('_AM_CUSTOMFIELDS_BREADCRUMB_MANAGE', '📋 Field Management');
define('_AM_CUSTOMFIELDS_FIELD_FORM_CRUMB_NEW', 'Add New Field');
define('_AM_CUSTOMFIELDS_FIELD_FORM_CRUMB_EDIT', 'Edit Field');

// Target module
define('_AM_CUSTOMFIELDS_TARGET_MODULE_LABEL', '🎯 Target Module');
define('_AM_CUSTOMFIELDS_TARGET_MODULE_PLACEHOLDER', 'Select a module...');
define('_AM_CUSTOMFIELDS_TARGET_MODULE_HELP', 'Select which module this field will be used in.');

// Field name
define('_AM_CUSTOMFIELDS_FIELD_NAME_LABEL', '🔤 Field Name (variable)');
define('_AM_CUSTOMFIELDS_FIELD_NAME_PLACEHOLDER', 'e.g. extra_image');
define('_AM_CUSTOMFIELDS_FIELD_NAME_HELP',
    'Use only English letters, numbers, and underscores. Examples: extra_image, video_url.');

// Field title
define('_AM_CUSTOMFIELDS_FIELD_TITLE_LABEL', '📝 Field Title');
define('_AM_CUSTOMFIELDS_FIELD_TITLE_PLACEHOLDER', 'Title shown to the user');
define('_AM_CUSTOMFIELDS_FIELD_TITLE_HELP',
    'Title displayed in the form. Examples: "Extra Image", "Video Link".');

// Description
define('_AM_CUSTOMFIELDS_FIELD_DESC_LABEL', '💬 Description');
define('_AM_CUSTOMFIELDS_FIELD_DESC_PLACEHOLDER', 'Help text that will assist the user');
define('_AM_CUSTOMFIELDS_FIELD_DESC_HELP',
    'Optional help text shown below the field in the form.');

// Field type + labels
define('_AM_CUSTOMFIELDS_FIELD_TYPE_LABEL', '🎨 Field Type');

define('_AM_CUSTOMFIELDS_FIELD_TYPE_TEXT', '📄 Text (Single Line)');
define('_AM_CUSTOMFIELDS_FIELD_TYPE_TEXTAREA', '📝 Textarea (Multi-line)');
define('_AM_CUSTOMFIELDS_FIELD_TYPE_EDITOR', '✏️ HTML Editor (WYSIWYG)');
define('_AM_CUSTOMFIELDS_FIELD_TYPE_IMAGE', '🖼️ Image Upload');
define('_AM_CUSTOMFIELDS_FIELD_TYPE_FILE', '📎 File Upload');
define('_AM_CUSTOMFIELDS_FIELD_TYPE_SELECT', '📋 Select (Dropdown)');
define('_AM_CUSTOMFIELDS_FIELD_TYPE_CHECKBOX', '☑️ Multiple Choice (Checkbox)');
define('_AM_CUSTOMFIELDS_FIELD_TYPE_RADIO', '🔘 Single Choice (Radio)');
define('_AM_CUSTOMFIELDS_FIELD_TYPE_DATE', '📅 Date Picker');

// Options section
define('_AM_CUSTOMFIELDS_OPTIONS_TABLE_VALUE', 'Value');
define('_AM_CUSTOMFIELDS_OPTIONS_TABLE_LABEL', 'Label');
define('_AM_CUSTOMFIELDS_OPTIONS_TABLE_ACTION', 'Action');

define('_AM_CUSTOMFIELDS_OPTIONS_VALUE_PLACEHOLDER', '1');
define('_AM_CUSTOMFIELDS_OPTIONS_LABEL_PLACEHOLDER', 'Option 1');
define('_AM_CUSTOMFIELDS_OPTIONS_DELETE_BUTTON', '🗑️ Delete');
define('_AM_CUSTOMFIELDS_OPTIONS_ADD_BUTTON', '➕ Add Option');

define('_AM_CUSTOMFIELDS_FIELD_OPTIONS_ALERT', 'Please add at least one option.');

// Order
define('_AM_CUSTOMFIELDS_FIELD_ORDER_LABEL', '🔢 Order');
define('_AM_CUSTOMFIELDS_FIELD_ORDER_HELP',
    'Display order in the form (smaller numbers are shown first).');

// Flags
define('_AM_CUSTOMFIELDS_SETTINGS_LABEL', '⚙️ Settings');
define('_AM_CUSTOMFIELDS_REQUIRED_CHECKBOX', '🔒 Required field (user cannot leave empty)');
define('_AM_CUSTOMFIELDS_SHOW_IN_FORM_CHECKBOX', '👁️ Show in form (visible in admin form)');

// Buttons
define('_AM_CUSTOMFIELDS_BUTTON_BACK', '← Back');
define('_AM_CUSTOMFIELDS_BUTTON_SAVE', '💾 Save');

// Info box
define('_AM_CUSTOMFIELDS_INFOBOX_HINT_LABEL', '💡 Tip:');
define('_AM_CUSTOMFIELDS_INFOBOX_HINT_TEXT',
    'After creating a field, you need to add the integration code to the target module’s admin page.');
