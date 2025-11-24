<?php
define('_MD_CUSTOMFIELDS_NAME', 'Custom Fields');


define('_MD_CUSTOMFIELDS_ONLY_ADMIN', ' This module is used only from the admin panel.');


// Admin/dev test helpers (front scripts) — English
define('_MD_CUSTOMFIELDS_PUB_CHECK_PAGE_TITLE', 'Publisher Check');
define('_MD_CUSTOMFIELDS_PUB_ITEM_CHECK', 'Publisher item.php Check');
define('_MD_CUSTOMFIELDS_FILE_NOT_FOUND', 'File not found!');
define('_MD_CUSTOMFIELDS_FILE_PATH', 'Path');
define('_MD_CUSTOMFIELDS_FILE_FOUND', 'File found');
define('_MD_CUSTOMFIELDS_FILE_LOCATION', 'Location');
define('_MD_CUSTOMFIELDS_FILE_SIZE', 'Size');
define('_MD_CUSTOMFIELDS_FILE_MTIME', 'Last modified');
define('_MD_CUSTOMFIELDS_SECTION_RENDERFORM', '1. customfields_renderForm() Check');
define('_MD_CUSTOMFIELDS_SECTION_SAVEDATA', '2. customfields_saveData() Check');
define('_MD_CUSTOMFIELDS_FOUND_AT_LINE', 'Found at line: %d');
define('_MD_CUSTOMFIELDS_NOT_FOUND', 'NOT FOUND!');
define('_MD_CUSTOMFIELDS_FLOW_ANALYSIS', '3. Code Flow Analysis (IMPORTANT)');
define('_MD_CUSTOMFIELDS_FLOW_STEP1', '1) $itemObj->store() → Line %d');
define('_MD_CUSTOMFIELDS_FLOW_STEP2', '2) customfields_saveData() → Line %d');
define('_MD_CUSTOMFIELDS_FLOW_STEP3', '3) redirect_header() → Line %d');
define('_MD_CUSTOMFIELDS_RESULT', 'RESULT');
define('_MD_CUSTOMFIELDS_RESULT_OK_TITLE', 'OK');
define('_MD_CUSTOMFIELDS_RESULT_OK_MSG', 'customfields_saveData() is called BEFORE redirect_header().');
define('_MD_CUSTOMFIELDS_RESULT_BAD_TITLE', 'PROBLEM');
define('_MD_CUSTOMFIELDS_RESULT_BAD_MSG', 'customfields_saveData() is called AFTER redirect_header()! Move it before redirect.');
define('_MD_CUSTOMFIELDS_NEXT_STEPS', 'Next Steps');
define('_MD_CUSTOMFIELDS_NO_SAVE_FOUND', 'customfields_saveData() after store() not found!');
define('_MD_CUSTOMFIELDS_NO_REDIRECT_FOUND', 'redirect_header() not found!');
define('_MD_CUSTOMFIELDS_CODE_BLOCK_AFTER_STORE', 'Code block after store()');

// Test item customfields
define('_MD_CUSTOMFIELDS_ITEM_TEST_TITLE', 'Item.php CustomFields Test');
define('_MD_CUSTOMFIELDS_FILE_CHECK_TITLE', '1. File Check');
define('_MD_CUSTOMFIELDS_CUSTOMFIELDS_CODE_CHECK', '2. CUSTOMFIELDS Code Check');
define('_MD_CUSTOMFIELDS_CUSTOMFIELDS_FOUND', 'CUSTOMFIELDS CODE FOUND!');
define('_MD_CUSTOMFIELDS_CUSTOMFIELDS_NOT_FOUND', 'CUSTOMFIELDS CODE NOT FOUND!');
define('_MD_CUSTOMFIELDS_SOLUTIONS', 'Solutions');
define('_MD_CUSTOMFIELDS_SOLUTION_UPLOAD', 'Option 1: Upload the new file');
define('_MD_CUSTOMFIELDS_SOLUTION_MANUAL', 'Option 2: Manual edit');
define('_MD_CUSTOMFIELDS_FOUND_CODE', 'Found Code:');
define('_MD_CUSTOMFIELDS_STEPS', 'Steps');

// Test publisher basic
define('_MD_CUSTOMFIELDS_CUSTOMFIELDS_TEST_TITLE', 'CustomFields Test');
define('_MD_CUSTOMFIELDS_PUBLISHER_TEST_TITLE', 'CustomFields Publisher Test');
define('_MD_CUSTOMFIELDS_MODULE_CHECK', '1. Module Check');
define('_MD_CUSTOMFIELDS_MODULE_FOUND', 'CustomFields module found');
define('_MD_CUSTOMFIELDS_MODULE_NOT_FOUND', 'CustomFields module NOT found!');
define('_MD_CUSTOMFIELDS_PUBLISHER_FIELDS', '2. Publisher Fields');
define('_MD_CUSTOMFIELDS_FIELDS_FOUND_N', '%d fields found:');
define('_MD_CUSTOMFIELDS_FIELDS_NOT_FOUND', 'No fields for Publisher! Create them first.');
define('_MD_CUSTOMFIELDS_DB_TABLES', '3. Database Tables');
define('_MD_CUSTOMFIELDS_TABLE_EXISTS', 'Table exists');
define('_MD_CUSTOMFIELDS_TABLE_MISSING', 'Table is MISSING!');
define('_MD_CUSTOMFIELDS_TOTAL_RECORDS', 'Total %d records');
define('_MD_CUSTOMFIELDS_PUBLISHER_RECORDS', '%d records for Publisher');
define('_MD_CUSTOMFIELDS_DB_ERROR', 'Database error: %s');
define('_MD_CUSTOMFIELDS_LAST_RECORDS', '4. Latest Publisher Records');
define('_MD_CUSTOMFIELDS_NO_PUBLISHER_DATA', 'No Publisher data yet');
define('_MD_CUSTOMFIELDS_PROBLEM_NOT_SAVED', 'PROBLEM! Data not being saved when creating an article.');
define('_MD_CUSTOMFIELDS_TABLE_HEADERS', 'ID,Item ID,Field,Value,Date');
define('_MD_CUSTOMFIELDS_WARNING', 'Warning');

// Test publisher save
define('_MD_CUSTOMFIELDS_SAVE_TEST_TITLE', 'Publisher Save Test');
define('_MD_CUSTOMFIELDS_POST_SIM_SECTION', '1. POST Data (Simulation)');
define('_MD_CUSTOMFIELDS_CALL_SAVEDATA', '2. customfields_saveData() Call');
define('_MD_CUSTOMFIELDS_SAVING_FOR', 'Saving: module=publisher, item_id=%d');
define('_MD_CUSTOMFIELDS_RESULT_BOOL', 'Result: %s');
define('_MD_CUSTOMFIELDS_DB_CHECK', '3. Database Check');
define('_MD_CUSTOMFIELDS_SQL', 'SQL');
define('_MD_CUSTOMFIELDS_DATA_FOUND', 'Data found:');
define('_MD_CUSTOMFIELDS_DATA_NOT_SAVED', 'Data could not be saved!');
define('_MD_CUSTOMFIELDS_POSSIBLE_HANDLER_ISSUE', 'Probably a handler or database issue.');
define('_MD_CUSTOMFIELDS_LAST_ERROR_LOGS', '4. Last Error Log Entries');
define('_MD_CUSTOMFIELDS_NO_CF_LOG_ENTRIES', 'No CustomFields related log entries found.');
define('_MD_CUSTOMFIELDS_ERRLOG_NOT_FOUND', 'Error log file not found: %s');
define('_MD_CUSTOMFIELDS_NEXT_STEPS_TITLE', 'NEXT STEPS');
define('_MD_CUSTOMFIELDS_NEXT_STEP_REFRESH', 'Refresh this page several times (F5)');
define('_MD_CUSTOMFIELDS_NEXT_STEP_EXPECT', 'Each time a new record should be added');
define('_MD_CUSTOMFIELDS_NEXT_STEP_LOG', 'You should see "CustomFields saveData called" in error log');
define('_MD_CUSTOMFIELDS_NEXT_STEP_INTEGRATION', 'If it works, check the Publisher integration');
define('_MD_CUSTOMFIELDS_BACK_TO_MAIN', '← Back to Main Test Page');
define('_MD_CUSTOMFIELDS_UPLOAD_INSTR_1', 'Upload the downloaded item.php via FTP');
define('_MD_CUSTOMFIELDS_UPLOAD_INSTR_2', 'Location: modules/publisher/item.php');
define('_MD_CUSTOMFIELDS_UPLOAD_INSTR_3', 'Refresh this page');
define('_MD_CUSTOMFIELDS_MANUAL_INSTR_1', 'Open item.php');
define('_MD_CUSTOMFIELDS_MANUAL_INSTR_2', 'Find around line 254: <code>unset($file, $embededFiles, $filesObj, $fileObj);</code>');
define('_MD_CUSTOMFIELDS_MANUAL_INSTR_3', 'Add the following code right below');
define('_MD_CUSTOMFIELDS_NO_CF_LOGS_LAST100', 'No CustomFields logs found in the last 100 lines.');
define('_MD_CUSTOMFIELDS_PUBLISHER_ADD_HINT', 'This may be normal. Add and save an article in Publisher, then check again.');
define('_MD_CUSTOMFIELDS_LINE', 'Line');

// Publisher debug page (admin-only diagnostics)
define('_MD_CUSTOMFIELDS_PUBDEBUG_PAGE_TITLE', 'Publisher Debug');
define('_MD_CUSTOMFIELDS_PUBDEBUG_SECTION1_TITLE', '1. Log File Check');
define('_MD_CUSTOMFIELDS_PUBDEBUG_FOLDER_WRITABLE', 'Folder is writable, a log file can be created');
define('_MD_CUSTOMFIELDS_PUBDEBUG_FOLDER_NOT_WRITABLE', 'Folder is not writable!');
define('_MD_CUSTOMFIELDS_PUBDEBUG_FIX_PERMS', 'Solution: grant 755 (or 777) permissions to modules/customfields/ folder');
define('_MD_CUSTOMFIELDS_PUBDEBUG_LOG_FILE', 'Log file');
define('_MD_CUSTOMFIELDS_PUBDEBUG_VIEW_LOG', 'View Log File');
define('_MD_CUSTOMFIELDS_PUBDEBUG_SECTION2_TITLE', '2. CustomFields Test');
define('_MD_CUSTOMFIELDS_PUBDEBUG_FOUND_FIELDS', 'Found %d fields for Publisher');
define('_MD_CUSTOMFIELDS_PUBDEBUG_SECTION3_TITLE', '3. Manual Save Test');
define('_MD_CUSTOMFIELDS_PUBDEBUG_TEST_STARTING', 'Starting test...');
define('_MD_CUSTOMFIELDS_PUBDEBUG_SIMULATED_POST', 'Simulated POST data:');
define('_MD_CUSTOMFIELDS_PUBDEBUG_RESULT_OK', 'SUCCESS! Data was saved into the database!');
define('_MD_CUSTOMFIELDS_PUBDEBUG_RESULT_FAIL', 'Data not found in database!');
define('_MD_CUSTOMFIELDS_PUBDEBUG_SECTION5_LOG_CONTENT', '5. Current Log Content');
define('_MD_CUSTOMFIELDS_PUBDEBUG_SECTION6_DB_STATUS', '6. Database Status');
define('_MD_CUSTOMFIELDS_PUBDEBUG_DB_TOTAL', 'Total %d records for Publisher');
define('_MD_CUSTOMFIELDS_PUBDEBUG_DB_LAST5', 'Last 5 records');
define('_MD_CUSTOMFIELDS_PUBDEBUG_RESULT_HEADING', 'Result');
define('_MD_CUSTOMFIELDS_PUBDEBUG_RESULT_HINT_OK', 'If the manual test succeeds: issue is in Publisher integration');
define('_MD_CUSTOMFIELDS_PUBDEBUG_RESULT_HINT_FAIL', 'If the manual test fails: issue is in the CustomFields handler');
define('_MD_CUSTOMFIELDS_PUBDEBUG_RESULT_HINT_ITEM0', 'If you see "Item ID: 0" in the real Publisher test log, that is the problem!');
define('_MD_CUSTOMFIELDS_PUBDEBUG_SECTION4_TITLE', '4. Publisher Real Test (Important!)');
define('_MD_CUSTOMFIELDS_PUBDEBUG_REAL_TEST_INTRO', 'If the manual test above was successful, proceed to the real Publisher test:');
define('_MD_CUSTOMFIELDS_PUBDEBUG_OPEN_ITEMPHP', 'Open <strong>Publisher item.php</strong>');
define('_MD_CUSTOMFIELDS_PUBDEBUG_FIND_LINE127', 'Around <strong>line 127</strong> (before the // SAVE ADDITIONAL FIELDS comment) add the following code:');
define('_MD_CUSTOMFIELDS_PUBDEBUG_VIEW_LOG_NEW', 'View Log File (New Window)');
define('_MD_CUSTOMFIELDS_PUBDEBUG_REFRESH_PAGE', 'Refresh This Page');
define('_MD_CUSTOMFIELDS_PUBDEBUG_DB_YES', 'Data exists in the database!');
define('_MD_CUSTOMFIELDS_PUBDEBUG_DB_NO', 'No Publisher data in the database!');
define('_MD_CUSTOMFIELDS_PUBDEBUG_MANUAL_FORM_DESC', 'This test simulates saving with Publisher Item ID 9999.');
define('_MD_CUSTOMFIELDS_PUBDEBUG_MANUAL_BTN', 'Start Manual Test');
define('_MD_CUSTOMFIELDS_PUBDEBUG_LOG_EMPTY', 'Log file is empty');

// test_publisher.php — Manual test + Error log + Integration check
define('_MD_CUSTOMFIELDS_MANUAL_TEST_TITLE', '5. Manual Save Test');
define('_MD_CUSTOMFIELDS_TEST_STARTING', 'Starting test...');
define('_MD_CUSTOMFIELDS_FIELD_SAVING', 'Saving Field ID %d (%s)... ');
define('_MD_CUSTOMFIELDS_SUCCESS', 'Success');
define('_MD_CUSTOMFIELDS_FAILURE', 'Failed');
define('_MD_CUSTOMFIELDS_RESULT_SUMMARY', 'Result: %d/%d fields saved');
define('_MD_CUSTOMFIELDS_REFRESH_AND_CHECK', 'Refresh and check');
define('_MD_CUSTOMFIELDS_MANUAL_TEST_BTN', 'Run Manual Test (Item ID: 9999)');
define('_MD_CUSTOMFIELDS_MANUAL_TEST_DESC', 'This button saves test data for Item ID 9999. For a full test, use a real article in Publisher.');
define('_MD_CUSTOMFIELDS_ERROR_LOG_TITLE', '6. Error Log Check');
define('_MD_CUSTOMFIELDS_ERROR_LOG_PATH', 'Error log');
define('_MD_CUSTOMFIELDS_SHOW_LAST100', 'Show last 100 lines (unfiltered for CustomFields)');
define('_MD_CUSTOMFIELDS_ERROR_LOG_READ_FAIL', 'Error log could not be read.');
define('_MD_CUSTOMFIELDS_ERROR_LOG_NOT_FOUND', 'Error log not found or not configured');
define('_MD_CUSTOMFIELDS_PHPINI_CHECK', 'Check error_log in php.ini.');
define('_MD_CUSTOMFIELDS_INTEGRATION_CHECK_TITLE', '7. Publisher Integration Check');
define('_MD_CUSTOMFIELDS_ITEMPHP_FOUND', 'Publisher item.php found');
define('_MD_CUSTOMFIELDS_CALL_PRESENT', 'call is present');
define('_MD_CUSTOMFIELDS_CALL_ABSENT', 'call is ABSENT!');
define('_MD_CUSTOMFIELDS_INTEGRATION_MISSING', 'Issue found! Integration code not added to Publisher item.php.');
define('_MD_CUSTOMFIELDS_FORM_CALL_ABSENT', 'customfields_renderForm() call is ABSENT (form may not display)');

// test_item_customfields.php — Cache and function tests + summary
define('_MD_CUSTOMFIELDS_CACHE_CHECK_TITLE', '3. Cache Check');
define('_MD_CUSTOMFIELDS_TEMPLATES_C_COUNT', 'templates_c/ file count');
define('_MD_CUSTOMFIELDS_CACHE_DIR_COUNT', 'cache/ file count');
define('_MD_CUSTOMFIELDS_CACHE_NEEDS_CLEAR', 'CACHE MUST BE CLEARED!');
define('_MD_CUSTOMFIELDS_CACHE_FIX', 'To make the item.php fix work, you must clear the cache.');
define('_MD_CUSTOMFIELDS_SOLUTION', 'Solution');
define('_MD_CUSTOMFIELDS_ADMIN_PANEL_PATH', 'XOOPS Admin Panel');
define('_MD_CUSTOMFIELDS_CLEAR_CACHE_BUTTON', 'Click “Clear Cache”');
define('_MD_CUSTOMFIELDS_UPDATE_TEMPLATES', 'Then click “Update Templates”');
define('_MD_CUSTOMFIELDS_REFRESH_PAGE', 'Refresh this page');
define('_MD_CUSTOMFIELDS_CACHE_CLEAN', 'Cache is clean');
define('_MD_CUSTOMFIELDS_CF_FUNC_TEST_TITLE', '4. CustomFields Function Test');
define('_MD_CUSTOMFIELDS_CF_FUNC_EXISTS', 'customfields_getItemData() function exists');
define('_MD_CUSTOMFIELDS_CF_FUNC_MISSING', 'customfields_getItemData() function not found!');
define('_MD_CUSTOMFIELDS_TESTING_ITEM', 'Testing for Item ID %d...');
define('_MD_CUSTOMFIELDS_NO_DATA_FOR_ITEM', 'No customfields data for item %d');
define('_MD_CUSTOMFIELDS_FILL_FIELDS_FOR_ARTICLE', 'Fill additional fields for this article.');
define('_MD_CUSTOMFIELDS_DATA_FOUND_GENERIC', 'Data found!');
define('_MD_CUSTOMFIELDS_SUMMARY_TITLE', 'Summary and Result');
define('_MD_CUSTOMFIELDS_EVERYTHING_OK', 'ALL GOOD!');
define('_MD_CUSTOMFIELDS_ITEM_FIX_AND_CACHE_OK', 'item.php fix applied and cache is clean.');
define('_MD_CUSTOMFIELDS_WHATS_NEXT', 'What now?');
define('_MD_CUSTOMFIELDS_ISSUES_TITLE', 'Issues found');
define('_MD_CUSTOMFIELDS_TODO_TITLE', 'To do');
define('_MD_CUSTOMFIELDS_ISSUE_ITEM_FIX_MISSING', 'item.php fix not applied');
define('_MD_CUSTOMFIELDS_ISSUE_CACHE_NOT_CLEARED', 'Cache not cleared');
define('_MD_CUSTOMFIELDS_TODO_UPLOAD_OR_MANUAL', 'Upload the new item.php OR add the code manually');
define('_MD_CUSTOMFIELDS_TODO_CLEAR_CACHE', 'Clear cache and update templates');

// Additional next-steps and quick-test guidance (Publisher test page)
define('_MD_CUSTOMFIELDS_NEXT_STEPS_CHECKLIST_TITLE', 'Next Steps');
define('_MD_CUSTOMFIELDS_HANDLER_WORKS_IF_MANUAL_OK', 'If the "Run Manual Test" button succeeds → the handler works');
define('_MD_CUSTOMFIELDS_CODE_IN_RIGHT_PLACE_IF_PRESENT', 'If the integration check shows "present" → the code is in the right place');
define('_MD_CUSTOMFIELDS_ITEM_ZERO_MEANS_NOT_WORKING', 'If Publisher data shows "0" → the code in item.php is not executing');
define('_MD_CUSTOMFIELDS_ADD_ARTICLE_AND_CHECK_LOG', 'Add an article in Publisher → check the error log → look for DEBUG lines');
define('_MD_CUSTOMFIELDS_LOOK_FOR_DEBUG_ITEMID', 'Look for the line <code>DEBUG: Publisher itemid = X</code>');
define('_MD_CUSTOMFIELDS_QUICK_TEST_TITLE', 'Quick Test');
define('_MD_CUSTOMFIELDS_PRESS_MANUAL_TEST', 'Click the "Run Manual Test" button');
define('_MD_CUSTOMFIELDS_REFRESH_PAGE_SHORT', 'Refresh the page');
define('_MD_CUSTOMFIELDS_SHOULD_SEE_ITEM9999', 'You should see Item ID 9999 in the "Latest Publisher Records" section');
define('_MD_CUSTOMFIELDS_IF_SEE_ISSUE_IN_INTEGRATION', 'If you see it → the issue is in the Publisher integration');
define('_MD_CUSTOMFIELDS_IF_NOT_SEE_ISSUE_IN_HANDLER', 'If you do not see it → the issue is in the handler');

// What now steps on item test page
define('_MD_CUSTOMFIELDS_WHATS_NEXT_STEP_OPEN_ARTICLE', 'Open an article page (e.g., Item ID 23)');
define('_MD_CUSTOMFIELDS_WHATS_NEXT_STEP_SEE_CF_PRESENT', 'You should see "CustomFields present? ✅ YES" in the debug box');
define('_MD_CUSTOMFIELDS_WHATS_NEXT_STEP_CLEAR_BROWSER', 'If you still don\'t, clear the browser cache (Ctrl+F5)');



define('_MD_CUSTOMFIELDS_SNIPPET_HEADER', 'EXTRA FIELDS');
define('_MD_CUSTOMFIELDS_SNIPPET_COMMENT', 'Add CustomFields data to the item array');
define('_MD_CUSTOMFIELDS_POST_SAMPLE_TITLE', 'Test Title - %s');
define('_MD_CUSTOMFIELDS_POST_SAMPLE_IMAGE', 'Test Image Data');
