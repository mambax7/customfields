<link rel="stylesheet" type="text/css"
      href="<{$xoops_url}>/modules/customfields/assets/css/guide.css" />

<div class="cf-guide">

    <!-- Header -->
    <div class="cf-header">
        <h1><{$smarty.const._AM_CUSTOMFIELDS_GUIDE_TITLE}></h1>
        <p><{$smarty.const._AM_CUSTOMFIELDS_GUIDE_SUBTITLE}></p>
    </div>

    <!-- Quick Links -->
    <div class="cf-card">
        <div class="cf-card-header">
            <h3><{$smarty.const._AM_CUSTOMFIELDS_QUICK_ACCESS}></h3>
        </div>
        <div class="cf-card-body">
            <a href="add.php" class="cf-btn cf-btn-primary" style="margin-right: 10px;">
                <{$smarty.const._AM_CUSTOMFIELDS_ADD_FIELD}>
            </a>
            <a href="manage.php" class="cf-btn cf-btn-primary">
                <{$smarty.const._AM_CUSTOMFIELDS_MANAGE_FIELDS}>
            </a>
        </div>
    </div>

    <!-- News Module Integration -->
    <div class="cf-card">
        <div class="cf-card-header">
            <h3><{$smarty.const._AM_CUSTOMFIELDS_NEWS_INTEGRATION}></h3>
        </div>
        <div class="cf-card-body">

            <!-- Step 1 -->
            <div class="cf-step">
                <h4 class="cf-step-title">
                    <span class="cf-step-number">1</span>
                    <{$smarty.const._AM_CUSTOMFIELDS_STEP1_FORM_ADD_ADMIN}>
                </h4>
                <p class="cf-step-desc">
                    <{$smarty.const._AM_CUSTOMFIELDS_STEP1_DESC}>
                </p>
                <span class="cf-code-label">📁 modules/news/admin/index.php</span>
                <div class="cf-code-block">
                    <code>&lt;?php
                        // Search for:
                        $sform-&gt;display();

                        // Add immediately after:
                        include_once XOOPS_ROOT_PATH . '/modules/customfields/include/functions.php';
                        $storyid = isset($_REQUEST['storyid']) ? (int) $_REQUEST['storyid'] : 0;
                        echo customfields_renderForm('news', $storyid);
                        ?&gt;
                    </code>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="cf-step">
                <h4 class="cf-step-title">
                    <span class="cf-step-number">2</span>
                    <{$smarty.const._AM_CUSTOMFIELDS_STEP2_SAVE_DATA}>
                </h4>
                <p class="cf-step-desc">
                    <{$smarty.const._AM_CUSTOMFIELDS_STEP2_DESC}>
                </p>
                <span class="cf-code-label">📁 modules/news/admin/index.php</span>
                <div class="cf-code-block">
                    <code>&lt;?php
                        // Search for:
                        $storyHandler-&gt;insert($story);

                        // Add immediately after:
                        if ($newstoryid = $storyHandler-&gt;insert($story)) {
                        // Save custom fields
                        include_once XOOPS_ROOT_PATH . '/modules/customfields/include/functions.php';
                        customfields_saveData('news', $newstoryid);
                        redirect_header('index.php', 2, _AM_CUSTOMFIELDS_NEWS_SAVED);
                        }
                        ?&gt;
                    </code>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="cf-step">
                <h4 class="cf-step-title">
                    <span class="cf-step-number">3</span>
                    <{$smarty.const._AM_CUSTOMFIELDS_STEP3_DELETE_OPTIONAL}>
                </h4>
                <p class="cf-step-desc">
                    <{$smarty.const._AM_CUSTOMFIELDS_STEP3_DESC}>
                </p>
                <span class="cf-code-label">📁 modules/news/admin/index.php</span>
                <div class="cf-code-block">
                    <code>&lt;?php
                        // Search for:
                        $storyHandler-&gt;delete($story);

                        // Add immediately after:
                        if ($storyHandler-&gt;delete($story)) {
                        // Delete custom fields
                        include_once XOOPS_ROOT_PATH . '/modules/customfields/include/functions.php';
                        customfields_deleteData('news', $storyid);
                        redirect_header('index.php', 2, _AM_CUSTOMFIELDS_NEWS_DELETED);
                        }
                        ?&gt;
                    </code>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="cf-step">
                <h4 class="cf-step-title">
                    <span class="cf-step-number">4</span>
                    <{$smarty.const._AM_CUSTOMFIELDS_STEP4_DISPLAY_TEMPLATE}>
                </h4>
                <p class="cf-step-desc">
                    <{$smarty.const._AM_CUSTOMFIELDS_STEP4_DESC}>
                </p>
                <span class="cf-code-label">📁 modules/news/templates/news_article.tpl</span>
                <div class="cf-code-block">
                    <code>
                        {ldelim}* Add below article content *{rdelim}
                        {ldelim}customfields module="news" item_id=$story.id assign="custom_fields"{rdelim}
                        {ldelim}if $custom_fields{rdelim}
                        &lt;div class="custom-fields-section"&gt;
                        &lt;h3&gt;<{$smarty.const._AM_CUSTOMFIELDS_EXTRA_INFO}>&lt;/h3&gt;
                        {ldelim}foreach from=$custom_fields item=field{rdelim}
                        &lt;div class="custom-field-item"&gt;
                        &lt;strong&gt;{$field.title}:&lt;/strong&gt; {$field.formatted_value}
                        &lt;/div&gt;
                        {ldelim}/foreach{rdelim}
                        &lt;/div&gt;
                        {ldelim}/if{rdelim}
                    </code>
                </div>
            </div>

        </div>
    </div>

    <!-- Other Modules -->
    <div class="cf-card">
        <div class="cf-card-header">
            <h3><{$smarty.const._AM_CUSTOMFIELDS_OTHER_MODULES}></h3>
        </div>
        <div class="cf-card-body">
            <p><{$smarty.const._AM_CUSTOMFIELDS_OTHER_MODULES_DESC}></p>
            <div class="cf-info-box">
                <strong><{$smarty.const._AM_CUSTOMFIELDS_TIP_LABEL}></strong>
                <{$smarty.const._AM_CUSTOMFIELDS_TIP_TEXT}>
            </div>

            <h4><{$smarty.const._AM_CUSTOMFIELDS_GENERAL_STEPS}></h4>
            <ol>
                <li><{$smarty.const._AM_CUSTOMFIELDS_STEP_GENERAL_DEFINE_FIELDS}></li>
                <li><{$smarty.const._AM_CUSTOMFIELDS_STEP_GENERAL_ADD_RENDERFORM}></li>
                <li><{$smarty.const._AM_CUSTOMFIELDS_STEP_GENERAL_ADD_SAVEDATA}></li>
                <li><{$smarty.const._AM_CUSTOMFIELDS_STEP_GENERAL_ADD_SMARTY}></li>
            </ol>
        </div>
    </div>

    <!-- Tips & Tricks -->
    <div class="cf-card">
        <div class="cf-card-header">
            <h3><{$smarty.const._AM_CUSTOMFIELDS_TIPS_TITLE}></h3>
        </div>
        <div class="cf-card-body">

            <div class="cf-warning-box">
                <strong><{$smarty.const._AM_CUSTOMFIELDS_WARNING_LABEL}></strong>
                <ul>
                    <li><{$smarty.const._AM_CUSTOMFIELDS_TIP_NO_TR_CHARS}></li>
                    <li><{$smarty.const._AM_CUSTOMFIELDS_TIP_UPLOAD_WRITABLE}></li>
                    <li><{$smarty.const._AM_CUSTOMFIELDS_TIP_CLEAR_CACHE}></li>
                </ul>
            </div>

            <div class="cf-success-box">
                <strong><{$smarty.const._AM_CUSTOMFIELDS_BEST_PRACTICES_TITLE}></strong>
                <ul>
                    <li><{$smarty.const._AM_CUSTOMFIELDS_BP_MEANINGFUL_SHORT_NAMES}></li>
                    <li><{$smarty.const._AM_CUSTOMFIELDS_BP_TURKISH_TITLES_OK}></li>
                    <li><{$smarty.const._AM_CUSTOMFIELDS_BP_REQUIRED_FIELDS}></li>
                    <li><{$smarty.const._AM_CUSTOMFIELDS_BP_ADD_DESCRIPTIONS}></li>
                </ul>
            </div>

        </div>
    </div>

    <!-- API Reference -->
    <div class="cf-card">
        <div class="cf-card-header">
            <h3><{$smarty.const._AM_CUSTOMFIELDS_API_FUNCTIONS_TITLE}></h3>
        </div>
        <div class="cf-card-body">
            <div class="cf-code-block">
                <code>
                    // Render form
                    customfields_renderForm($module_name, $item_id);

                    // Save data
                    customfields_saveData($module_name, $item_id);

                    // Delete data
                    customfields_deleteData($module_name, $item_id);

                    // Fetch data (PHP)
                    $data = customfields_getData($module_name, $item_id);

                    // Get fields
                    $fields = customfields_getFields($module_name);

                    // Prepare for template
                    $template_data = customfields_prepareForTemplate($module_name, $item_id);
                </code>
            </div>
        </div>
    </div>

</div>
