<link rel="stylesheet" type="text/css"
      href="<{$xoops_url}>/modules/customfields/assets/css/manage.css" />

<div class="cf-manage">

    <!-- Header -->
    <div class="cf-header">
        <div class="cf-header-content">
            <h1><{$smarty.const._AM_CUSTOMFIELDS_MANAGE_TITLE}></h1>
            <p><{$smarty.const._AM_CUSTOMFIELDS_MANAGE_SUBTITLE}></p>
        </div>
        <a href="add.php" class="cf-btn cf-btn-white">
            <{$smarty.const._AM_CUSTOMFIELDS_MANAGE_ADD_FIELD_BTN}>
        </a>
    </div>

    <!-- Main Card -->
    <div class="cf-card">
        <!-- Filter Bar -->
        <div class="cf-filter-bar">
            <span class="cf-filter-label">
                <{$smarty.const._AM_CUSTOMFIELDS_MANAGE_FILTER_LABEL}>
            </span>
            <form method="get" action="manage.php"
                  style="display: flex; gap: 10px; align-items: center;">
                <select name="module" class="cf-select" onchange="this.form.submit()">
                    <option value="">
                        <{$smarty.const._AM_CUSTOMFIELDS_MANAGE_FILTER_ALL_MODULES}>
                    </option>
                    <{foreach from=$xmodules item=mod}>
                        <option value="<{$mod.dirname|escape}>"
                                <{if $module_filter == $mod.dirname}>selected="selected"<{/if}>>
                            <{$mod.name|escape}>
                        </option>
                    <{/foreach}>
                </select>
            </form>
        </div>

        <!-- Table / Empty -->
        <{if $has_fields}>
            <table class="cf-table">
                <thead>
                <tr>
                    <th><{$smarty.const._AM_CUSTOMFIELDS_TABLE_ID}></th>
                    <th><{$smarty.const._AM_CUSTOMFIELDS_TABLE_MODULE}></th>
                    <th><{$smarty.const._AM_CUSTOMFIELDS_TABLE_FIELD_NAME}></th>
                    <th><{$smarty.const._AM_CUSTOMFIELDS_TABLE_FIELD_TITLE}></th>
                    <th><{$smarty.const._AM_CUSTOMFIELDS_TABLE_FIELD_TYPE}></th>
                    <th style="text-align: center;">
                        <{$smarty.const._AM_CUSTOMFIELDS_MANAGE_TABLE_ORDER}>
                    </th>
                    <th style="text-align: center;">
                        <{$smarty.const._AM_CUSTOMFIELDS_MANAGE_TABLE_REQUIRED}>
                    </th>
                    <th style="text-align: center;">
                        <{$smarty.const._AM_CUSTOMFIELDS_MANAGE_TABLE_IN_FORM}>
                    </th>
                    <th style="text-align: right;">
                        <{$smarty.const._AM_CUSTOMFIELDS_TABLE_ACTIONS}>
                    </th>
                </tr>
                </thead>
                <tbody>
                <{foreach from=$fields item=field}>
                    <tr>
                        <td>
                            <strong>#<{$field.field_id}></strong>
                        </td>
                        <td>
                            <{$field.target_module|escape|capitalize}>
                        </td>
                        <td>
                            <code class="cf-code"><{$field.field_name|escape}></code>
                        </td>
                        <td>
                            <{$field.field_title|escape}>
                        </td>
                        <td>
                                <span class="cf-badge cf-badge-type">
                                    <{$field.field_type|escape}>
                                </span>
                        </td>
                        <td style="text-align: center;">
                            <{$field.field_order}>
                        </td>
                        <td style="text-align: center;">
                                <span class="cf-badge
                                    <{if $field.required}>cf-badge-yes<{else}>cf-badge-no<{/if}>">
                                    <{if $field.required}>
                                        <{$smarty.const._AM_CUSTOMFIELDS_BADGE_YES}>
                                    <{else}>
                                        <{$smarty.const._AM_CUSTOMFIELDS_BADGE_NO}>
                                    <{/if}>
                                </span>
                        </td>
                        <td style="text-align: center;">
                                <span class="cf-badge
                                    <{if $field.show_in_form}>cf-badge-yes<{else}>cf-badge-no<{/if}>">
                                    <{if $field.show_in_form}>
                                        <{$smarty.const._AM_CUSTOMFIELDS_BADGE_YES}>
                                    <{else}>
                                        <{$smarty.const._AM_CUSTOMFIELDS_BADGE_NO}>
                                    <{/if}>
                                </span>
                        </td>
                        <td style="text-align: right;">
                            <div class="cf-actions">
                                <a href="add.php?field_id=<{$field.field_id}>"
                                   class="cf-btn-sm cf-btn-edit">
                                    ✏️ <{$smarty.const._AM_CUSTOMFIELDS_ACTION_EDIT}>
                                </a>
                                <form method="post" action="manage.php"
                                      style="display: inline; margin: 0;"
                                      onsubmit="return confirm('<{$smarty.const._AM_CUSTOMFIELDS_CONFIRM_DELETE_MANAGE|escape:'javascript'}>');">
                                    <input type="hidden" name="op" value="delete" />
                                    <input type="hidden" name="field_id"
                                           value="<{$field.field_id}>" />
                                    <{$token_html}>
                                    <button type="submit" class="cf-btn-sm cf-btn-delete">
                                        🗑️ <{$smarty.const._AM_CUSTOMFIELDS_ACTION_DELETE}>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <{/foreach}>
                </tbody>
            </table>

            <div class="cf-footer">
                <{$smarty.const._AM_CUSTOMFIELDS_MANAGE_FOOTER_TOTAL_PREFIX}>
                <strong><{$total_fields}></strong>
                <{$smarty.const._AM_CUSTOMFIELDS_MANAGE_FOOTER_TOTAL_SUFFIX}>
                <{if $module_filter != ''}>
                    |
                    <strong><{$module_filter|escape|capitalize}></strong>
                    <{$smarty.const._AM_CUSTOMFIELDS_MANAGE_FOOTER_FILTER_SUFFIX}>
                <{/if}>
            </div>

        <{else}>
            <div class="cf-empty">
                <div class="cf-empty-icon">📭</div>
                <h3><{$smarty.const._AM_CUSTOMFIELDS_MANAGE_EMPTY_TITLE}></h3>
                <p><{$smarty.const._AM_CUSTOMFIELDS_MANAGE_EMPTY_MESSAGE}></p>
                <br />
                <a href="add.php"
                   class="cf-btn cf-btn-white"
                   style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <{$smarty.const._AM_CUSTOMFIELDS_MANAGE_EMPTY_CREATE_BTN}>
                </a>
            </div>
        <{/if}>
    </div>
</div>
