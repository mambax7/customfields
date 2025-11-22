<link rel="stylesheet" type="text/css"
      href="<{$xoops_url}>/modules/customfields/assets/css/customfields_admin_fields.css" />

<div class="cf-fields-wrapper">

    <div class="cf-fields-header">
        <h4><{$smarty.const._AM_CUSTOMFIELDS_FIELDS_HEADING}></h4>
        <a href="fields.php?op=add" class="btn btn-primary">
            <{$smarty.const._AM_CUSTOMFIELDS_ADD_FIELD_LINK}>
        </a>
    </div>

    <form method="get" action="fields.php" class="cf-fields-filters">
        <input type="hidden" name="op" value="list" />
        <label>
            <{$smarty.const._AM_CUSTOMFIELDS_FILTER_MODULE}>
            <input type="text" name="filter_module" value="<{$filter_module|escape}>" />
        </label>
        <label>
            <{$smarty.const._AM_CUSTOMFIELDS_FILTER_TYPE}>
            <input type="text" name="filter_type" value="<{$filter_type|escape}>" />
        </label>
        <label>
            <{$smarty.const._AM_CUSTOMFIELDS_FILTER_LIMIT}>
            <input type="number" name="limit"
                   value="<{$smarty.get.limit|default:20|escape}>"
                   min="1" />
        </label>
        <button type="submit" class="btn btn-secondary">
            <{$smarty.const._AM_CUSTOMFIELDS_FILTER_SUBMIT}>
        </button>
        <a href="fields.php?op=list" class="btn btn-light">
            <{$smarty.const._AM_CUSTOMFIELDS_FILTER_RESET}>
        </a>
    </form>

    <br />

    <{if $has_fields}>
        <table class="outer cf-fields-table">
            <tr>
                <th>
                    <a href="fields.php?op=list
                        &sort=field_id
                        &order=<{if $sort == 'field_id' && $order == 'ASC'}>DESC<{else}>ASC<{/if}>
                        &filter_module=<{$filter_module|escape:'url'}>
                        &filter_type=<{$filter_type|escape:'url'}>">
                        <{$smarty.const._AM_CUSTOMFIELDS_TABLE_ID}>
                    </a>
                </th>
                <th>
                    <a href="fields.php?op=list
                        &sort=target_module
                        &order=<{if $sort == 'target_module' && $order == 'ASC'}>DESC<{else}>ASC<{/if}>
                        &filter_module=<{$filter_module|escape:'url'}>
                        &filter_type=<{$filter_type|escape:'url'}>">
                        <{$smarty.const._AM_CUSTOMFIELDS_TABLE_MODULE}>
                    </a>
                </th>
                <th>
                    <a href="fields.php?op=list
                        &sort=field_name
                        &order=<{if $sort == 'field_name' && $order == 'ASC'}>DESC<{else}>ASC<{/if}>
                        &filter_module=<{$filter_module|escape:'url'}>
                        &filter_type=<{$filter_type|escape:'url'}>">
                        <{$smarty.const._AM_CUSTOMFIELDS_TABLE_FIELD_NAME}>
                    </a>
                </th>
                <th>
                    <a href="fields.php?op=list
                        &sort=field_title
                        &order=<{if $sort == 'field_title' && $order == 'ASC'}>DESC<{else}>ASC<{/if}>
                        &filter_module=<{$filter_module|escape:'url'}>
                        &filter_type=<{$filter_type|escape:'url'}>">
                        <{$smarty.const._AM_CUSTOMFIELDS_TABLE_FIELD_TITLE}>
                    </a>
                </th>
                <th>
                    <a href="fields.php?op=list
                        &sort=field_type
                        &order=<{if $sort == 'field_type' && $order == 'ASC'}>DESC<{else}>ASC<{/if}>
                        &filter_module=<{$filter_module|escape:'url'}>
                        &filter_type=<{$filter_type|escape:'url'}>">
                        <{$smarty.const._AM_CUSTOMFIELDS_TABLE_FIELD_TYPE}>
                    </a>
                </th>
                <th><{$smarty.const._AM_CUSTOMFIELDS_TABLE_ACTIONS}></th>
            </tr>

            <{foreach from=$fields item=field}>
                <tr>
                    <td><{$field.field_id}></td>
                    <td><{$field.target_module|escape}></td>
                    <td><{$field.field_name|escape}></td>
                    <td><{$field.field_title|escape}></td>
                    <td><{$field.field_type|escape}></td>
                    <td>
                        <a href="fields.php?op=edit&field_id=<{$field.field_id}>">
                            <{$smarty.const._AM_CUSTOMFIELDS_ACTION_EDIT}>
                        </a>
                        &nbsp;|&nbsp;
                        <form method="post"
                              action="fields.php?op=delete"
                              style="display:inline"
                              onsubmit="return confirm('<{$smarty.const._AM_CUSTOMFIELDS_CONFIRM_DELETE|escape:'javascript'}>');">
                            <input type="hidden" name="field_id" value="<{$field.field_id}>" />
                            <{$token_html}>
                            <button type="submit" class="btn btn-link btn-sm">
                                <{$smarty.const._AM_CUSTOMFIELDS_ACTION_DELETE}>
                            </button>
                        </form>
                    </td>
                </tr>
            <{/foreach}>
        </table>

        <{if $pagenav}>
            <div class="cf-fields-pagenav">
                <{$pagenav}>
            </div>
        <{/if}>
    <{else}>
        <p><{$smarty.const._AM_CUSTOMFIELDS_NO_FIELDS}></p>
    <{/if}>

</div>
