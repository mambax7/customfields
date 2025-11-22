<link rel="stylesheet" type="text/css"
      href="<{$xoops_url}>/modules/customfields/assets/css/customfields_admin_fields.css" />

<div class="cf-fields-wrapper">

    <div class="cf-fields-header">
        <h4>
            <{if $is_new}>
                <{$smarty.const._AM_CUSTOMFIELDS_FIELD_FORM_HEADING_NEW}>
            <{else}>
                <{$smarty.const._AM_CUSTOMFIELDS_FIELD_FORM_HEADING_EDIT}>
            <{/if}>
        </h4>
    </div>

    <form method="post" action="fields.php">
        <input type="hidden" name="op" value="save" />
        <{if !$is_new}>
            <input type="hidden" name="field_id" value="<{$field.field_id}>" />
        <{/if}>

        <table class="outer cf-fields-table">
            <tr>
                <th colspan="2">
                    <{if $is_new}>
                        <{$smarty.const._AM_CUSTOMFIELDS_FIELD_FORM_HEADING_NEW}>
                    <{else}>
                        <{$smarty.const._AM_CUSTOMFIELDS_FIELD_FORM_HEADING_EDIT}>
                    <{/if}>
                </th>
            </tr>

            <tr>
                <td class="head">
                    <label for="target_module">
                        <{$smarty.const._AM_CUSTOMFIELDS_FIELD_TARGET_MODULE}>
                    </label>
                </td>
                <td class="even">
                    <input type="text" name="target_module" id="target_module"
                           value="<{$field.target_module|escape}>" />
                    <br />
                    <span class="xoops-form-element-help">
                        <{$smarty.const._AM_CUSTOMFIELDS_FIELD_TARGET_MODULE_HELP}>
                    </span>
                </td>
            </tr>

            <tr>
                <td class="head">
                    <label for="field_name">
                        <{$smarty.const._AM_CUSTOMFIELDS_FIELD_NAME}>
                    </label>
                </td>
                <td class="even">
                    <input type="text" name="field_name" id="field_name"
                           value="<{$field.field_name|escape}>" />
                    <br />
                    <span class="xoops-form-element-help">
                        <{$smarty.const._AM_CUSTOMFIELDS_FIELD_NAME_HELP}>
                    </span>
                </td>
            </tr>

            <tr>
                <td class="head">
                    <label for="field_title">
                        <{$smarty.const._AM_CUSTOMFIELDS_FIELD_TITLE}>
                    </label>
                </td>
                <td class="even">
                    <input type="text" name="field_title" id="field_title"
                           value="<{$field.field_title|escape}>" />
                    <br />
                    <span class="xoops-form-element-help">
                        <{$smarty.const._AM_CUSTOMFIELDS_FIELD_TITLE_HELP}>
                    </span>
                </td>
            </tr>

            <tr>
                <td class="head">
                    <label for="field_description">
                        <{$smarty.const._AM_CUSTOMFIELDS_FIELD_DESCRIPTION}>
                    </label>
                </td>
                <td class="even">
                    <textarea name="field_description" id="field_description" rows="4" cols="50"><{$field.field_description|escape}></textarea>
                    <br />
                    <span class="xoops-form-element-help">
                        <{$smarty.const._AM_CUSTOMFIELDS_FIELD_DESCRIPTION_HELP}>
                    </span>
                </td>
            </tr>

            <tr>
                <td class="head">
                    <label for="field_type">
                        <{$smarty.const._AM_CUSTOMFIELDS_FIELD_TYPE}>
                    </label>
                </td>
                <td class="even">
                    <select name="field_type" id="field_type">
                        <{foreach from=$field_types key=ftype item=label}>
                            <option value="<{$ftype|escape}>"
                                    <{if $field.field_type == $ftype}>selected="selected"<{/if}>>
                                <{$label}>
                            </option>
                        <{/foreach}>
                    </select>
                    <br />
                    <span class="xoops-form-element-help">
                        <{$smarty.const._AM_CUSTOMFIELDS_FIELD_TYPE_HELP}>
                    </span>
                </td>
            </tr>

            <tr>
                <td class="head">
                    <label for="field_order">
                        <{$smarty.const._AM_CUSTOMFIELDS_FIELD_ORDER}>
                    </label>
                </td>
                <td class="even">
                    <input type="number" name="field_order" id="field_order"
                           value="<{$field.field_order|escape}>" />
                </td>
            </tr>

            <tr>
                <td class="head">
                    <{$smarty.const._AM_CUSTOMFIELDS_FIELD_REQUIRED}>
                </td>
                <td class="even">
                    <label>
                        <input type="checkbox" name="required" value="1"
                               <{if $field.required}>checked="checked"<{/if}> />
                        <{$smarty.const._AM_CUSTOMFIELDS_FIELD_REQUIRED_LABEL}>
                    </label>
                </td>
            </tr>

            <tr>
                <td class="head">
                    <{$smarty.const._AM_CUSTOMFIELDS_FIELD_SHOW_IN_FORM}>
                </td>
                <td class="even">
                    <label>
                        <input type="checkbox" name="show_in_form" value="1"
                               <{if $field.show_in_form}>checked="checked"<{/if}> />
                        <{$smarty.const._AM_CUSTOMFIELDS_FIELD_SHOW_IN_FORM_LABEL}>
                    </label>
                </td>
            </tr>

            <tr>
                <td class="head">
                    <{$smarty.const._AM_CUSTOMFIELDS_FIELD_OPTIONS}>
                </td>
                <td class="even">
                    <p>
                        <{$smarty.const._AM_CUSTOMFIELDS_FIELD_OPTIONS_HELP}>
                    </p>
                    <table class="outer">
                        <tr>
                            <th><{$smarty.const._AM_CUSTOMFIELDS_FIELD_OPTION_VALUE}></th>
                            <th><{$smarty.const._AM_CUSTOMFIELDS_FIELD_OPTION_LABEL}></th>
                        </tr>
                        <{foreach from=$field.options item=opt name=optloop}>
                            <tr>
                                <td>
                                    <input type="text" name="option_values[]" value="<{$opt.value|escape}>" />
                                </td>
                                <td>
                                    <input type="text" name="option_labels[]" value="<{$opt.label|escape}>" />
                                </td>
                            </tr>
                        <{/foreach}>
                        <!-- Extra empty row -->
                        <tr>
                            <td>
                                <input type="text" name="option_values[]" value="" />
                            </td>
                            <td>
                                <input type="text" name="option_labels[]" value="" />
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td class="head">&nbsp;</td>
                <td class="even">
                    <{$token_html}>
                    <button type="submit" class="btn btn-primary">
                        <{$smarty.const._AM_CUSTOMFIELDS_SAVE_BUTTON}>
                    </button>
                    <a href="fields.php?op=list" class="btn btn-secondary">
                        <{$smarty.const._AM_CUSTOMFIELDS_CANCEL_BUTTON}>
                    </a>
                </td>
            </tr>
        </table>
    </form>

</div>
