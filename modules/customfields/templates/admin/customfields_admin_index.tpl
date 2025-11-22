<link rel="stylesheet" type="text/css"
      href="<{$xoops_url}>/modules/customfields/assets/css/index.css" />

<div class="cf-dashboard">

    <!-- Header -->
    <div class="cf-header">
        <h1><{$smarty.const._AM_CUSTOMFIELDS_DASHBOARD_TITLE}></h1>
        <p><{$smarty.const._AM_CUSTOMFIELDS_DASHBOARD_SUBTITLE}></p>
    </div>

    <!-- Stats Cards -->
    <div class="cf-stats">
        <div class="cf-stat-card">
            <div class="cf-stat-icon">📋</div>
            <div class="cf-stat-number"><{$total_fields}></div>
            <div class="cf-stat-label">
                <{$smarty.const._AM_CUSTOMFIELDS_DASHBOARD_TOTAL_FIELDS_LABEL}>
            </div>
        </div>

        <div class="cf-stat-card">
            <div class="cf-stat-icon">💾</div>
            <div class="cf-stat-number"><{$total_data}></div>
            <div class="cf-stat-label">
                <{$smarty.const._AM_CUSTOMFIELDS_DASHBOARD_TOTAL_DATA_LABEL}>
            </div>
        </div>

        <div class="cf-stat-card">
            <div class="cf-stat-icon">🔌</div>
            <div class="cf-stat-number"><{$total_modules}></div>
            <div class="cf-stat-label">
                <{$smarty.const._AM_CUSTOMFIELDS_DASHBOARD_TOTAL_MODULES_LABEL}>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="cf-card">
        <div class="cf-card-header">
            <h3><{$smarty.const._AM_CUSTOMFIELDS_DASHBOARD_QUICK_ACTIONS}></h3>
            <a href="add.php" class="cf-btn cf-btn-primary">
                <{$smarty.const._AM_CUSTOMFIELDS_DASHBOARD_ADD_FIELD_BTN}>
            </a>
        </div>
    </div>

    <!-- Module Stats -->
    <div class="cf-card">
        <div class="cf-card-header">
            <h3><{$smarty.const._AM_CUSTOMFIELDS_DASHBOARD_MODULE_STATS_TITLE}></h3>
        </div>
        <div class="cf-card-body">
            <{if $has_module_stats}>
                <table class="cf-table">
                    <thead>
                    <tr>
                        <th><{$smarty.const._AM_CUSTOMFIELDS_DASHBOARD_TABLE_MODULE}></th>
                        <th style="text-align: center;">
                            <{$smarty.const._AM_CUSTOMFIELDS_DASHBOARD_TABLE_FIELD_COUNT}>
                        </th>
                        <th style="text-align: right;">
                            <{$smarty.const._AM_CUSTOMFIELDS_DASHBOARD_TABLE_ACTIONS}>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <{foreach from=$module_stats item=mod}>
                        <tr>
                            <td>
                                <strong><{$mod.module|escape}></strong>
                            </td>
                            <td style="text-align: center;">
                                    <span class="cf-badge cf-badge-primary">
                                        <{$mod.count}>
                                        <{$smarty.const._AM_CUSTOMFIELDS_DASHBOARD_FIELD_COUNT_SUFFIX}>
                                    </span>
                            </td>
                            <td style="text-align: right;">
                                <a href="manage.php?module=<{$mod.module|escape:'url'}>"
                                   class="cf-btn cf-btn-sm cf-btn-primary">
                                    <{$smarty.const._AM_CUSTOMFIELDS_DASHBOARD_VIEW_BUTTON}>
                                </a>
                            </td>
                        </tr>
                    <{/foreach}>
                    </tbody>
                </table>
            <{else}>
                <div class="cf-empty-state">
                    <div class="cf-empty-icon">📭</div>
                    <h3><{$smarty.const._AM_CUSTOMFIELDS_DASHBOARD_EMPTY_TITLE}></h3>
                    <p><{$smarty.const._AM_CUSTOMFIELDS_DASHBOARD_EMPTY_MESSAGE}></p>
                </div>
            <{/if}>
        </div>
    </div>

    <!-- Quick Guide -->
    <div class="cf-guide">
        <h4><{$smarty.const._AM_CUSTOMFIELDS_DASHBOARD_QUICK_GUIDE_TITLE}></h4>
        <ol>
            <li>
                <strong><{$smarty.const._AM_CUSTOMFIELDS_DASHBOARD_STEP1_TITLE}></strong>
                <{$smarty.const._AM_CUSTOMFIELDS_DASHBOARD_STEP1_DESC}>
                <code>ek_resim</code>
            </li>
            <li>
                <strong><{$smarty.const._AM_CUSTOMFIELDS_DASHBOARD_STEP2_TITLE}></strong>
                <{$smarty.const._AM_CUSTOMFIELDS_DASHBOARD_STEP2_DESC}>
            </li>
            <li>
                <strong><{$smarty.const._AM_CUSTOMFIELDS_DASHBOARD_STEP3_TITLE}></strong>
                <{$smarty.const._AM_CUSTOMFIELDS_DASHBOARD_STEP3_DESC}>
                <code>customfields_saveData()</code>
            </li>
            <li>
                <strong><{$smarty.const._AM_CUSTOMFIELDS_DASHBOARD_STEP4_TITLE}></strong>
                <{$smarty.const._AM_CUSTOMFIELDS_DASHBOARD_STEP4_DESC}>
            </li>
        </ol>
        <p>
            <a href="manage.php" style="color: #d97706; font-weight: 600;">
                <{$smarty.const._AM_CUSTOMFIELDS_DASHBOARD_DOC_LINK_TEXT}>
            </a>
        </p>
    </div>

</div>
