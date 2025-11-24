<link rel="stylesheet" type="text/css"
      href="<{$xoops_url}>/modules/customfields/assets/css/manage.css" />

<div class="cf-card">
    <div class="cf-card-header">
        <h3>
            <{if isset($tests_title) && $tests_title != ''}>
                <{$tests_title|escape}>
            <{else}>
                <{$smarty.const._AM_CUSTOMFIELDS_TESTS_TITLE|default:'Tests'}>
            <{/if}>
        </h3>
    </div>
    <div class="cf-card-body">
        <p>
            <{if isset($tests_desc) && $tests_desc != ''}>
                <{$tests_desc|escape}>
            <{else}>
                <{$smarty.const._AM_CUSTOMFIELDS_TESTS_DESC|default:'Developer test helpers for CustomFields integration. Use on non-production systems.'}>
            <{/if}>
            <br><br>
        </p>

        <table class="cf-table">
            <thead>
            <tr>
                <th><{$smarty.const._AM_CUSTOMFIELDS_TESTS_TH_SCRIPT|default:'Script'}></th>
                <th><{$smarty.const._AM_CUSTOMFIELDS_TESTS_TH_DESC|default:'Description'}></th>
                <th style="text-align:right;"><{$smarty.const._AM_CUSTOMFIELDS_TESTS_TH_ACTION|default:'Action'}></th>
            </tr>
            </thead>
            <tbody>
            <{foreach from=$test_links item=lnk}>
                <tr>
                    <td><code><{$lnk.label|escape}></code></td>
                    <td><{$lnk.desc|escape}></td>
                    <td style="text-align:right;">
                        <a class="cf-btn cf-btn-primary"
                           href="<{$lnk.href|escape}>" target="_blank" rel="noopener">
                            <{$smarty.const._AM_CUSTOMFIELDS_TESTS_OPEN_BTN|default:'Open'}>
                        </a>
                    </td>
                </tr>
            <{/foreach}>
            </tbody>
        </table>

        <div class="cf-empty" style="margin-top:10px;">
            <p style="color:#9a6b00;">
                <{$smarty.const._AM_CUSTOMFIELDS_TESTS_NOTE|default:'Note: Some scripts may expose diagnostic information. Keep them admin-only and avoid use on production.'}>
            </p>
        </div>
    </div>
</div>
