<link rel="stylesheet" type="text/css"
      href="<{$xoops_url}>/modules/customfields/assets/css/add.css" />

<div class="cf-form-page">

    <!-- Header -->
    <div class="cf-header">
        <h1>
            <{if $is_new}>
                <{$smarty.const._AM_CUSTOMFIELDS_FIELD_FORM_TITLE_NEW}>
            <{else}>
                <{$smarty.const._AM_CUSTOMFIELDS_FIELD_FORM_TITLE_EDIT}>
            <{/if}>
        </h1>
        <p><{$smarty.const._AM_CUSTOMFIELDS_FIELD_FORM_SUBTITLE}></p>
    </div>

    <!-- Breadcrumb -->
    <div class="cf-breadcrumb">
        <a href="index.php"><{$smarty.const._AM_CUSTOMFIELDS_BREADCRUMB_HOME}></a> /
        <a href="manage.php"><{$smarty.const._AM_CUSTOMFIELDS_BREADCRUMB_MANAGE}></a> /
        <strong>
            <{if $is_new}>
                <{$smarty.const._AM_CUSTOMFIELDS_FIELD_FORM_CRUMB_NEW}>
            <{else}>
                <{$smarty.const._AM_CUSTOMFIELDS_FIELD_FORM_CRUMB_EDIT}>
            <{/if}>
        </strong>
    </div>

    <!-- Form -->
    <form method="post" action="add.php" id="field_form">
        <input type="hidden" name="op" value="save" />
        <{$token_html}>
        <{if !$is_new}>
            <input type="hidden" name="field_id" value="<{$field_id}>" />
        <{/if}>

        <div class="cf-form-card">

            <!-- Target module -->
            <div class="cf-form-group">
                <label class="cf-label">
                    <{$smarty.const._AM_CUSTOMFIELDS_TARGET_MODULE_LABEL}>
                    <span class="cf-required">*</span>
                </label>
                <select name="target_module" id="target_module" required
                        class="cf-input cf-select">
                    <option value="">
                        <{$smarty.const._AM_CUSTOMFIELDS_TARGET_MODULE_PLACEHOLDER}>
                    </option>
                    <{foreach from=$modules item=mod}>
                        <option value="<{$mod.dirname|escape}>"
                                <{if $field.target_module == $mod.dirname}>selected="selected"<{/if}>>
                            <{$mod.name|escape}>
                        </option>
                    <{/foreach}>
                </select>
                <span class="cf-help">
                    <{$smarty.const._AM_CUSTOMFIELDS_TARGET_MODULE_HELP}>
                </span>
            </div>

            <!-- Field name -->
            <div class="cf-form-group">
                <label class="cf-label">
                    <{$smarty.const._AM_CUSTOMFIELDS_FIELD_NAME_LABEL}>
                    <span class="cf-required">*</span>
                </label>
                <input type="text"
                       name="field_name"
                       value="<{$field.field_name|escape}>"
                       required
                       class="cf-input"
                       placeholder="<{$smarty.const._AM_CUSTOMFIELDS_FIELD_NAME_PLACEHOLDER}>" />
                <span class="cf-help">
                    <{$smarty.const._AM_CUSTOMFIELDS_FIELD_NAME_HELP}>
                </span>
            </div>

            <!-- Field title -->
            <div class="cf-form-group">
                <label class="cf-label">
                    <{$smarty.const._AM_CUSTOMFIELDS_FIELD_TITLE_LABEL}>
                    <span class="cf-required">*</span>
                </label>
                <input type="text"
                       name="field_title"
                       value="<{$field.field_title|escape}>"
                       required
                       class="cf-input"
                       placeholder="<{$smarty.const._AM_CUSTOMFIELDS_FIELD_TITLE_PLACEHOLDER}>" />
                <span class="cf-help">
                    <{$smarty.const._AM_CUSTOMFIELDS_FIELD_TITLE_HELP}>
                </span>
            </div>

            <!-- Description -->
            <div class="cf-form-group">
                <label class="cf-label">
                    <{$smarty.const._AM_CUSTOMFIELDS_FIELD_DESC_LABEL}>
                </label>
                <textarea name="field_description"
                          rows="3"
                          class="cf-input cf-textarea"
                          placeholder="<{$smarty.const._AM_CUSTOMFIELDS_FIELD_DESC_PLACEHOLDER}>"><{$field.field_description|escape}></textarea>
                <span class="cf-help">
                    <{$smarty.const._AM_CUSTOMFIELDS_FIELD_DESC_HELP}>
                </span>
            </div>

            <!-- Field type + options -->
            <div class="cf-form-group">
                <label class="cf-label">
                    <{$smarty.const._AM_CUSTOMFIELDS_FIELD_TYPE_LABEL}>
                    <span class="cf-required">*</span>
                </label>
                <select name="field_type"
                        id="field_type"
                        required
                        class="cf-input cf-select">
                    <{foreach from=$field_types item=ft}>
                        <option value="<{$ft.value|escape}>"
                                <{if $field.field_type == $ft.value}>selected="selected"<{/if}>>
                            <{$ft.label}>
                        </option>
                    <{/foreach}>
                </select>

                <!-- Options container -->
                <div id="options_container" class="cf-options-container">
                    <table class="cf-options-table">
                        <thead>
                        <tr>
                            <th style="width: 40%;">
                                <{$smarty.const._AM_CUSTOMFIELDS_OPTIONS_TABLE_VALUE}>
                            </th>
                            <th style="width: 40%;">
                                <{$smarty.const._AM_CUSTOMFIELDS_OPTIONS_TABLE_LABEL}>
                            </th>
                            <th style="width: 20%; text-align: center;">
                                <{$smarty.const._AM_CUSTOMFIELDS_OPTIONS_TABLE_ACTION}>
                            </th>
                        </tr>
                        </thead>
                        <tbody id="options_tbody">
                        <{foreach from=$options item=opt}>
                            <tr>
                                <td>
                                    <input type="text"
                                           name="option_values[]"
                                           value="<{$opt.value|escape}>"
                                           placeholder="<{$smarty.const._AM_CUSTOMFIELDS_OPTIONS_VALUE_PLACEHOLDER}>" />
                                </td>
                                <td>
                                    <input type="text"
                                           name="option_labels[]"
                                           value="<{$opt.label|escape}>"
                                           placeholder="<{$smarty.const._AM_CUSTOMFIELDS_OPTIONS_LABEL_PLACEHOLDER}>" />
                                </td>
                                <td style="text-align: center;">
                                    <button type="button"
                                            class="cf-btn cf-btn-danger cf-btn-sm remove-option">
                                        <{$smarty.const._AM_CUSTOMFIELDS_OPTIONS_DELETE_BUTTON}>
                                    </button>
                                </td>
                            </tr>
                        <{/foreach}>
                        </tbody>
                    </table>
                    <button type="button"
                            id="add_option"
                            class="cf-btn cf-btn-success cf-btn-sm">
                        <{$smarty.const._AM_CUSTOMFIELDS_OPTIONS_ADD_BUTTON}>
                    </button>
                </div>
            </div>

            <!-- Order -->
            <div class="cf-form-group">
                <label class="cf-label">
                    <{$smarty.const._AM_CUSTOMFIELDS_FIELD_ORDER_LABEL}>
                </label>
                <input type="number"
                       name="field_order"
                       value="<{$field.field_order}>"
                       min="0"
                       class="cf-input"
                       style="max-width: 150px;" />
                <span class="cf-help">
                    <{$smarty.const._AM_CUSTOMFIELDS_FIELD_ORDER_HELP}>
                </span>
            </div>

            <!-- Flags -->
            <div class="cf-form-group">
                <label class="cf-label">
                    <{$smarty.const._AM_CUSTOMFIELDS_SETTINGS_LABEL}>
                </label>
                <div class="cf-checkbox">
                    <input type="checkbox"
                           name="required"
                           value="1"
                           id="required"
                           <{if $field.required}>checked="checked"<{/if}> />
                    <label for="required">
                        <{$smarty.const._AM_CUSTOMFIELDS_REQUIRED_CHECKBOX}>
                    </label>
                </div>
                <div class="cf-checkbox">
                    <input type="checkbox"
                           name="show_in_form"
                           value="1"
                           id="show_in_form"
                           <{if $field.show_in_form}>checked="checked"<{/if}> />
                    <label for="show_in_form">
                        <{$smarty.const._AM_CUSTOMFIELDS_SHOW_IN_FORM_CHECKBOX}>
                    </label>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="cf-form-actions">
            <button type="button"
                    onclick="history.back()"
                    class="cf-btn cf-btn-secondary">
                <{$smarty.const._AM_CUSTOMFIELDS_BUTTON_BACK}>
            </button>
            <button type="submit"
                    class="cf-btn cf-btn-primary">
                <{$smarty.const._AM_CUSTOMFIELDS_BUTTON_SAVE}>
            </button>
        </div>
    </form>

    <!-- Info Box -->
    <div class="cf-info-box" style="margin-top: 20px;">
        <strong><{$smarty.const._AM_CUSTOMFIELDS_INFOBOX_HINT_LABEL}></strong>
        <{$smarty.const._AM_CUSTOMFIELDS_INFOBOX_HINT_TEXT}>
    </div>
</div>

<script type="text/javascript">
    // Modern JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        const fieldType        = document.getElementById('field_type');
        const optionsContainer = document.getElementById('options_container');
        const addOptionBtn     = document.getElementById('add_option');
        const optionsTbody     = document.getElementById('options_tbody');

        function needsOptions(type) {
            return ['select', 'checkbox', 'radio'].indexOf(type) !== -1;
        }

        // Show/hide options block
        function toggleOptions() {
            const show = needsOptions(fieldType.value);
            optionsContainer.style.display = show ? 'block' : 'none';
        }

        toggleOptions();
        fieldType.addEventListener('change', toggleOptions);

        // Add new option row
        addOptionBtn.addEventListener('click', function() {
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
            <td>
                <input type="text"
                       name="option_values[]"
                       placeholder="<{$smarty.const._AM_CUSTOMFIELDS_OPTIONS_VALUE_PLACEHOLDER|escape:'html'}>" />
            </td>
            <td>
                <input type="text"
                       name="option_labels[]"
                       placeholder="<{$smarty.const._AM_CUSTOMFIELDS_OPTIONS_LABEL_PLACEHOLDER|escape:'html'}>" />
            </td>
            <td style="text-align: center;">
                <button type="button"
                        class="cf-btn cf-btn-danger cf-btn-sm remove-option">
                    <{$smarty.const._AM_CUSTOMFIELDS_OPTIONS_DELETE_BUTTON|escape:'html'}>
                </button>
            </td>
        `;
            optionsTbody.appendChild(newRow);
        });

        // Remove options
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-option')) {
                const row = e.target.closest('tr');
                if (row) {
                    row.remove();
                }
            }
        });

        // Validation on submit
        document.getElementById('field_form').addEventListener('submit', function(e) {
            if (needsOptions(fieldType.value)) {
                const optionValues = document.querySelectorAll('input[name="option_values[]"]');
                let hasValidOption = false;

                optionValues.forEach(function(input) {
                    if (input.value.trim() !== '') {
                        hasValidOption = true;
                    }
                });

                if (!hasValidOption) {
                    e.preventDefault();
                    alert('<{$smarty.const._AM_CUSTOMFIELDS_FIELD_OPTIONS_ALERT|escape:'javascript'}>');
                    return false;
                }
            }
        });
    });
</script>
