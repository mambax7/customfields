<link rel="stylesheet" type="text/css"
      href="<{$xoops_url}>/modules/customfields/assets/css/manage.css" />

<div class="cf-card">
    <div class="cf-card-header">
        <h3>
            <{$smarty.const._AM_CUSTOMFIELDS_TESTS_TITLE|default:'Admin Diagnostics'}>
        </h3>
    </div>
    <div class="cf-card-body">
        <p>
            <{$smarty.const._AM_CUSTOMFIELDS_TESTS_DESC|default:'Safe, admin-only diagnostics for CustomFields. Use only on development systems.'}>
        </p>

        <div class="cf-tabs" style="margin:10px 0;">
            <a class="cf-btn cf-btn-primary <{if $tab=='overview'}>cf-btn-primary<{/if}>" href="?tab=overview&target_module=<{$target_module|escape}>">
                <{$smarty.const._AM_CUSTOMFIELDS_TAB_OVERVIEW|default:'Overview'}>
            </a>
            <a class="cf-btn cf-btn-primary <{if $tab=='flow'}>cf-btn-primary<{/if}>" href="?tab=flow&target_module=<{$target_module|escape}>">
                <{$smarty.const._AM_CUSTOMFIELDS_TAB_FLOW|default:'Publisher Flow Analyzer'}>
            </a>
        </div>

        <{if $tab=='overview'}>
        <div class="cf-grid" style="margin-top:10px;">
            <div class="cf-grid-col">
                <div class="cf-box">
                    <h4><{$smarty.const._AM_CUSTOMFIELDS_ENVIRONMENT|default:'Environment'}></h4>
                    <p>
                        <strong><{$smarty.const._AM_CUSTOMFIELDS_LOG_PATH|default:'Log path'}>:</strong>
                        <code><{$log_file|escape}></code><br>
                        <strong><{$smarty.const._AM_CUSTOMFIELDS_DIR_WRITABLE|default:'Directory writable'}>:</strong>
                        <{if $dir_writable}>
                            <span style="color: #2e7d32;"><{$smarty.const._AM_CUSTOMFIELDS_YES|default:'Yes'}></span>
                        <{else}>
                            <span style="color: #c62828;"><{$smarty.const._AM_CUSTOMFIELDS_NO|default:'No'}></span>
                        <{/if}>
                    </p>
                </div>
            </div>
            <div class="cf-grid-col">
                <div class="cf-box">
                    <h4><{$smarty.const._AM_CUSTOMFIELDS_TARGET_MODULE|default:'Target module'}></h4>
                    <p>
                        <strong><{$smarty.const._AM_CUSTOMFIELDS_TARGET_MODULE|default:'Target module'}>:</strong> <code><{$target_module|escape}></code>
                        <br>
                        <small><{$smarty.const._AM_CUSTOMFIELDS_CHANGE_TARGET_HINT|default:'You can change `target_module` via the query string, e.g. ?target_module=publisher'}>.</small>
                    </p>
                </div>
            </div>
        </div>

        <div class="cf-box" style="margin-top:10px;">
<{*            <h4><{$smarty.const._AM_CUSTOMFIELDS_AVAILABLE_FIELDS|default:'Available fields'}> (<{$has_fields ? count($fields) : 0}>)</h4>*}>
            <h4><{$smarty.const._AM_CUSTOMFIELDS_AVAILABLE_FIELDS|default:'Available fields'}>  (<{$fields|@count|default:0}>)</h4>

            <{if $has_fields}>
                <table class="cf-table">
                    <thead>
                    <tr>
                        <th><{$smarty.const._AM_CUSTOMFIELDS_COL_ID|default:'ID'}></th>
                        <th><{$smarty.const._AM_CUSTOMFIELDS_COL_NAME|default:'Name'}></th>
                        <th><{$smarty.const._AM_CUSTOMFIELDS_COL_TITLE|default:'Title'}></th>
                        <th><{$smarty.const._AM_CUSTOMFIELDS_COL_TYPE|default:'Type'}></th>
                    </tr>
                    </thead>
                    <tbody>
                    <{foreach from=$fields item=f}>
                        <tr>
                            <td><{assign var=fid value=$f->getVar('field_id')}><{$fid|escape}></td>
                            <td><{$f->getVar('field_name')|escape}></td>
                            <td><{$f->getVar('field_title')|escape}></td>
                            <td><{$f->getVar('field_type')|escape}></td>
                        </tr>
                    <{/foreach}>
                    </tbody>
                </table>
            <{else}>
                <div class="cf-empty"><{$smarty.const._AM_CUSTOMFIELDS_NO_FIELDS_FOUND|default:'No fields found for this module.'}></div>
            <{/if}>
        </div>

        <div class="cf-box" style="margin-top:10px;">
            <h4><{$smarty.const._AM_CUSTOMFIELDS_MANUAL_SAVE|default:'Manual save simulation'}></h4>
            <p><{$smarty.const._AM_CUSTOMFIELDS_MANUAL_SAVE_DESC|default:'This POST-only action calls customfields_saveData() for a demo item ID (9999) using sample values. A valid XOOPS security token is required.'}></p>
            <form method="post">
                <{$token_html|default:''}>
                <label style="display:block; margin:6px 0;">
                    <input type="checkbox" name="simulate_error" value="1" />
                    <{$smarty.const._AM_CUSTOMFIELDS_SIMULATE_ERROR|default:'Simulate error (force rollback)'}>
                </label>
                <button type="submit" name="manual_test" value="1" class="cf-btn cf-btn-primary"><{$smarty.const._AM_CUSTOMFIELDS_RUN_MANUAL_TEST|default:'Run manual test'}></button>
            </form>

            <{if isset($action) && $action.ran}>
                <div class="cf-alert" style="margin-top:10px;">
                    <{if $action.ok}>
                        <div style="padding:8px; border-left:4px solid #2e7d32; background:#e8f5e9;">
                            ✅ <{$smarty.const._AM_CUSTOMFIELDS_SAVE_COMPLETED|default:'Save completed for item'}> <strong><{$action.item_id}></strong>.
                            <br><{$smarty.const._AM_CUSTOMFIELDS_ROWS_FOUND|default:'Rows found'}>: <strong><{$action.rows}></strong>
                        </div>
                    <{else}>
                        <div style="padding:8px; border-left:4px solid #c62828; background:#ffebee;">
                            ❌ <{$smarty.const._AM_CUSTOMFIELDS_OPERATION_FAILED|default:'Operation failed.'}>
                            <{if isset($action.message)}>
                                <br><em><{$action.message|escape}></em>
                            <{/if}>
                            <{if isset($action.simulated) && $action.simulated}>
                                <br><small><em><{$smarty.const._AM_CUSTOMFIELDS_ROLLBACK_OCCURRED|default:'A simulated error forced a rollback. This is expected for the test.'}></em></small>
                            <{/if}>
                        </div>
                    <{/if}>
                </div>
            <{/if}>
        </div>

        <div class="cf-box" style="margin-top:10px;">
            <h4><{$smarty.const._AM_CUSTOMFIELDS_LOGS_TITLE|default:'Recent log entries'}></h4>
            <div class="cf-grid">
                <div class="cf-grid-col">
                    <h5 style="margin:6px 0;">🔧 <{$smarty.const._AM_CUSTOMFIELDS_CF_LOG|default:'CustomFields log'}></h5>
                    <{if $cf_log_entries}>
                        <pre style="background:#f5f5f5; padding:10px; max-height:260px; overflow:auto;">
<{$cf_log_entries|escape}>
    </pre>
                    <{else}>
                        <div class="cf-empty"><{$smarty.const._AM_CUSTOMFIELDS_NO_CF_LOG_ENTRIES|default:'No CustomFields-related entries were found.'}></div>
                    <{/if}>
                </div>
                <div class="cf-grid-col">
                    <h5 style="margin:6px 0;">📝 <{$smarty.const._AM_CUSTOMFIELDS_PHP_ERROR_LOG|default:'PHP error_log'}> <small>(<{$php_error_log|escape}>)</small></h5>
                    <{if $php_log_entries}>
                        <pre style="background:#f5f5f5; padding:10px; max-height:260px; overflow:auto;">
<{$php_log_entries|escape}>
    </pre>
                    <{else}>
                        <div class="cf-empty"><{$smarty.const._AM_CUSTOMFIELDS_NO_CF_LOG_ENTRIES|default:'No CustomFields-related entries were found.'}></div>
                    <{/if}>
                </div>
            </div>
        </div>
        <{/if}>

        <{if $tab=='flow'}>
            <div class="cf-box" style="margin-top:10px;">
                <h4>🔬 <{$smarty.const._AM_CUSTOMFIELDS_FLOW_TITLE|default:'Publisher item.php Flow Analysis'}></h4>
                <p><code><{$flow.file_path|escape}></code> —
                    <{if $flow.file_exists}>
                        <span style="color:#2e7d32;">✅ <{$smarty.const._AM_CUSTOMFIELDS_FILE_FOUND|default:'File found'}></span>
                    <{else}>
                        <span style="color:#c62828;">❌ <{$smarty.const._AM_CUSTOMFIELDS_FILE_NOT_FOUND|default:'File not found'}></span>
                    <{/if}>
                </p>

                <{if $flow.file_exists}>
                    <h5 style="margin-top:10px;">1) <{$smarty.const._AM_CUSTOMFIELDS_RENDERFORM_SECTION|default:'Check for customfields_renderForm'}></h5>
                    <{if $flow.render_form.found}>
                        <div style="padding:8px; background:#e8f5e9; border-left:4px solid #2e7d32;">
                            ✅ <{$smarty.const._AM_CUSTOMFIELDS_FOUND_AT_LINE|default:'Found at line %d'|sprintf:$flow.render_form.line}>
                            <pre style="background:#f8f9fa; padding:8px; overflow:auto;">
<{$flow.render_form.snippet_text|escape}>
                            </pre>
                        </div>
                    <{else}>
                        <div style="padding:8px; background:#ffebee; border-left:4px solid #c62828;">❌ customfields_renderForm not found</div>
                    <{/if}>

                    <h5 style="margin-top:10px;">2) <{$smarty.const._AM_CUSTOMFIELDS_SAVEDATA_SECTION|default:'Check for customfields_saveData'}></h5>
                    <{if $flow.save_data.found}>
                        <div style="padding:8px; background:#e8f5e9; border-left:4px solid #2e7d32;">
                            ✅ <{$smarty.const._AM_CUSTOMFIELDS_FOUND_AT_LINE|default:'Found at line %d'|sprintf:$flow.save_data.line}>
                            <pre style="background:#f8f9fa; padding:8px; overflow:auto;">
<{$flow.save_data.snippet_text|escape}>
                            </pre>
                        </div>
                    <{else}>
                        <div style="padding:8px; background:#ffebee; border-left:4px solid #c62828;">❌ customfields_saveData not found</div>
                    <{/if}>

                    <h5 style="margin-top:10px;">3) <{$smarty.const._AM_CUSTOMFIELDS_FLOW_SECTION|default:'Flow ordering after itemObj->store()'}></h5>
                    <div style="background:#f0f0f0; padding:10px; border-left:4px solid #2196F3;">
                        1️⃣ <{$smarty.const._AM_CUSTOMFIELDS_FLOW_STEP1|default:'Store() appears around line %d'|sprintf:$flow.store_line}><br>
                        2️⃣ <{$smarty.const._AM_CUSTOMFIELDS_FLOW_STEP2|default:'customfields_saveData() appears at line %d'|sprintf:$flow.saveData_after_store}><br>
                        3️⃣ <{$smarty.const._AM_CUSTOMFIELDS_FLOW_STEP3|default:'redirect_header() appears at line %d'|sprintf:$flow.first_redirect_after_store}>
                    </div>

                    <div style="margin-top:10px;">
                        <{if $flow.saveData_after_store > 0 && $flow.first_redirect_after_store > 0}>
                            <{if $flow.saveData_after_store < $flow.first_redirect_after_store}>
                                <div style="background:#e8f5e9; padding:12px; border-left:5px solid #2e7d32;">
                                    <strong>✅ <{$smarty.const._AM_CUSTOMFIELDS_RESULT_OK_TITLE|default:'Looks good'}></strong>
                                    <div><{$smarty.const._AM_CUSTOMFIELDS_RESULT_OK_MSG|default:'customfields_saveData() is called before the first redirect after store().'}></div>
                                </div>
                            <{else}>
                                <div style="background:#ffebee; padding:12px; border-left:5px solid #c62828;">
                                    <strong>❌ <{$smarty.const._AM_CUSTOMFIELDS_RESULT_BAD_TITLE|default:'Potential issue'}></strong>
                                    <div><{$smarty.const._AM_CUSTOMFIELDS_RESULT_BAD_MSG|default:'A redirect header happens before customfields_saveData(). Ensure saving occurs before any redirect.'}></div>
                                </div>
                            <{/if}>
                        <{else}>
                            <div style="background:#fff3cd; padding:12px; border-left:5px solid #ffc107;">
                                <strong>⚠️ Result</strong>
                                <{if $flow.saveData_after_store == 0}>
                                    <div><{$smarty.const._AM_CUSTOMFIELDS_NO_SAVE_FOUND|default:'customfields_saveData() was not found near the store() block.'}></div>
                                <{/if}>
                                <{if $flow.first_redirect_after_store == 0}>
                                    <div><{$smarty.const._AM_CUSTOMFIELDS_NO_REDIRECT_FOUND|default:'redirect_header() was not found near the store() block.'}></div>
                                <{/if}>
                            </div>
                        <{/if}>
                    </div>

                    <h5 style="margin-top:10px;">6) <{$smarty.const._AM_CUSTOMFIELDS_CODE_BLOCK|default:'Code block after store()'}></h5>
                    <pre style="background:#f5f5f5; padding:10px; border:1px solid #ddd; overflow:auto; font-size:12px; line-height:1.4;">
<{$flow.code_block_text|escape}>
                    </pre>
                <{/if}>
            </div>
        <{/if}>
    </div>
</div>
