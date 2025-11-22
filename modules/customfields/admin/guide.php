<?php
include_once '../../../include/cp_header.php';

xoops_cp_header();
?>

<style>
.cf-guide {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    background: #f8f9fa;
    padding: 20px;
    margin: -10px;
}

.cf-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    border-radius: 10px;
    margin-bottom: 30px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
a:visited {
  text-decoration: none;
  color: #fff;
  background-color: transparent;
}
.cf-header h1 {
    margin: 0 0 10px 0;
    font-size: 24px;
    font-weight: 600;
}

.cf-header p {
    margin: 0;
    opacity: 0.9;
    font-size: 14px;
}

.cf-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    margin-bottom: 20px;
    overflow: hidden;
}

.cf-card-header {
    padding: 20px;
    background: #f7fafc;
    border-bottom: 2px solid #e2e8f0;
}

.cf-card-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #2d3748;
}

.cf-card-body {
    padding: 25px;
}

.cf-step {
    margin-bottom: 30px;
    padding-bottom: 30px;
    border-bottom: 1px dashed #e2e8f0;
}

.cf-step:last-child {
    border-bottom: none;
}

.cf-step-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 50%;
    font-weight: 700;
    font-size: 16px;
    margin-right: 12px;
}

.cf-step-title {
    font-size: 16px;
    font-weight: 600;
    color: #2d3748;
    margin: 15px 0 10px 0;
}

.cf-step-desc {
    color: #718096;
    margin-bottom: 15px;
    line-height: 1.6;
}

.cf-code-block {
    background: #2d3748;
    color: #e2e8f0;
    padding: 20px;
    border-radius: 8px;
    overflow-x: auto;
    margin: 15px 0;
    position: relative;
}

.cf-code-block code {
    font-family: 'Courier New', monospace;
    font-size: 13px;
    line-height: 1.6;
    display: block;
    white-space: pre;
}

.cf-code-label {
    background: #667eea;
    color: white;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    display: inline-block;
    margin-bottom: 10px;
}

.cf-info-box {
    background: #e6f2ff;
    border-left: 4px solid #667eea;
    padding: 15px;
    border-radius: 6px;
    margin: 15px 0;
}

.cf-info-box strong {
    color: #667eea;
}

.cf-warning-box {
    background: #fff5e6;
    border-left: 4px solid #ffa500;
    padding: 15px;
    border-radius: 6px;
    margin: 15px 0;
}

.cf-warning-box strong {
    color: #d97706;
}

.cf-success-box {
    background: #e6ffe6;
    border-left: 4px solid #48bb78;
    padding: 15px;
    border-radius: 6px;
    margin: 15px 0;
}

.cf-btn {
    display: inline-block;
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 14px;
}

.cf-btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.cf-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    color: white;
}
</style>

<div class="cf-guide">
    <!-- Header -->
    <div class="cf-header">
        <h1>📚 Kullanım Rehberi</h1>
        <p>İlave alanları modüllerinize nasıl entegre edeceğinizi öğrenin</p>
    </div>
    
    <!-- Quick Links -->
    <div class="cf-card">
        <div class="cf-card-header">
            <h3>⚡ Hızlı Erişim</h3>
        </div>
        <div class="cf-card-body">
            <a href="add.php" class="cf-btn cf-btn-primary" style="margin-right: 10px;">➕ Yeni Alan Ekle</a>
            <a href="manage.php" class="cf-btn cf-btn-primary">📋 Alanları Yönet</a>
        </div>
    </div>
    
    <!-- News Module Integration -->
    <div class="cf-card">
        <div class="cf-card-header">
            <h3>🗞️ News Modülü Entegrasyonu</h3>
        </div>
        <div class="cf-card-body">
            
            <!-- Step 1 -->
            <div class="cf-step">
                <h4 class="cf-step-title">
                    <span class="cf-step-number">1</span>
                    Form Ekleme (Admin Panel)
                </h4>
                <p class="cf-step-desc">
                    News modülünün admin panelinde haber ekleme/düzenleme formuna ilave alanları ekleyin.
                </p>
                
                <span class="cf-code-label">📁 Dosya: modules/news/admin/index.php</span>
                <div class="cf-code-block">
<code>&lt;?php
// Aranacak: $sform->display();
// Hemen sonrasına ekleyin:

include_once XOOPS_ROOT_PATH . '/modules/customfields/include/functions.php';
$storyid = isset($_REQUEST['storyid']) ? intval($_REQUEST['storyid']) : 0;
echo customfields_renderForm('news', $storyid);
?&gt;</code>
                </div>
            </div>
            
            <!-- Step 2 -->
            <div class="cf-step">
                <h4 class="cf-step-title">
                    <span class="cf-step-number">2</span>
                    Veri Kaydetme
                </h4>
                <p class="cf-step-desc">
                    Haber kaydedildiğinde ilave alan verilerini de kaydedin.
                </p>
                
                <span class="cf-code-label">📁 Dosya: modules/news/admin/index.php</span>
                <div class="cf-code-block">
<code>&lt;?php
// Aranacak: $storyHandler->insert($story)
// Hemen sonrasına ekleyin:

if ($newstoryid = $storyHandler->insert($story)) {
    
    // İlave alanları kaydet
    include_once XOOPS_ROOT_PATH . '/modules/customfields/include/functions.php';
    customfields_saveData('news', $newstoryid);
    
    redirect_header("index.php", 2, "Haber kaydedildi");
}
?&gt;</code>
                </div>
            </div>
            
            <!-- Step 3 -->
            <div class="cf-step">
                <h4 class="cf-step-title">
                    <span class="cf-step-number">3</span>
                    Silme İşlemi (İsteğe Bağlı)
                </h4>
                <p class="cf-step-desc">
                    Haber silindiğinde ilave alan verilerini de silin.
                </p>
                
                <span class="cf-code-label">📁 Dosya: modules/news/admin/index.php</span>
                <div class="cf-code-block">
<code>&lt;?php
// Aranacak: $storyHandler->delete($story)
// Hemen sonrasına ekleyin:

if ($storyHandler->delete($story)) {
    
    // İlave alanları sil
    include_once XOOPS_ROOT_PATH . '/modules/customfields/include/functions.php';
    customfields_deleteData('news', $storyid);
    
    redirect_header("index.php", 2, "Haber silindi");
}
?&gt;</code>
                </div>
            </div>
            
            <!-- Step 4 -->
            <div class="cf-step">
                <h4 class="cf-step-title">
                    <span class="cf-step-number">4</span>
                    Template'te Gösterme
                </h4>
                <p class="cf-step-desc">
                    Haber görüntüleme sayfasında ilave alanları gösterin.
                </p>
                
                <span class="cf-code-label">📁 Dosya: modules/news/templates/news_article.tpl</span>
                <div class="cf-code-block">
<code>{* Haber içeriğinin altına ekleyin *}

{customfields module="news" item_id=$story.id assign="custom_fields"}
{if $custom_fields}
&lt;div class="custom-fields-section"&gt;
    &lt;h3&gt;Ek Bilgiler&lt;/h3&gt;
    {foreach from=$custom_fields item=field}
        &lt;div class="custom-field-item"&gt;
            &lt;strong&gt;{$field.title}:&lt;/strong&gt;
            {$field.formatted_value}
        &lt;/div&gt;
    {/foreach}
&lt;/div&gt;
{/if}</code>
                </div>
            </div>
            
        </div>
    </div>
    
    <!-- Other Modules -->
    <div class="cf-card">
        <div class="cf-card-header">
            <h3>🔌 Diğer Modüller</h3>
        </div>
        <div class="cf-card-body">
            <p>Aynı mantıkla herhangi bir XOOPS modülüne entegre edebilirsiniz:</p>
            
            <div class="cf-info-box">
                <strong>💡 İpucu:</strong> Sadece <code>'news'</code> yazan yerleri hedef modül adınız ile değiştirin.
                Örneğin: <code>'publisher'</code>, <code>'content'</code>, <code>'articles'</code>
            </div>
            
            <h4>📋 Genel Adımlar:</h4>
            <ol>
                <li>İlave Alanlar modülünden hedef modül için alan tanımlayın</li>
                <li>Hedef modülün admin form sayfasına <code>customfields_renderForm()</code> ekleyin</li>
                <li>Kaydetme işlemine <code>customfields_saveData()</code> ekleyin</li>
                <li>Template'e Smarty fonksiyonu ekleyin</li>
            </ol>
        </div>
    </div>
    
    <!-- Tips & Tricks -->
    <div class="cf-card">
        <div class="cf-card-header">
            <h3>🎯 İpuçları</h3>
        </div>
        <div class="cf-card-body">
            
            <div class="cf-warning-box">
                <strong>⚠️ Dikkat:</strong>
                <ul>
                    <li>Alan adlarında Türkçe karakter kullanmayın</li>
                    <li>Dosya yüklemeleri için <code>uploads/customfields/</code> dizini yazılabilir olmalı</li>
                    <li>Template değişikliklerinden sonra cache'i temizleyin</li>
                </ul>
            </div>
            
            <div class="cf-success-box">
                <strong>✅ En İyi Uygulamalar:</strong>
                <ul>
                    <li>Alan adlarını anlamlı ve kısa tutun (örn: <code>ek_resim</code>)</li>
                    <li>Başlıklarda Türkçe kullanabilirsiniz (örn: "Ek Resim")</li>
                    <li>Zorunlu alanları dikkatli seçin</li>
                    <li>Açıklama ekleyerek kullanıcılara yardımcı olun</li>
                </ul>
            </div>
            
        </div>
    </div>
    
    <!-- API Reference -->
    <div class="cf-card">
        <div class="cf-card-header">
            <h3>🔧 API Fonksiyonları</h3>
        </div>
        <div class="cf-card-body">
            <div class="cf-code-block">
<code>// Form gösterme
customfields_renderForm($module_name, $item_id)

// Veri kaydetme
customfields_saveData($module_name, $item_id)

// Veri silme
customfields_deleteData($module_name, $item_id)

// Veri alma (PHP)
$data = customfields_getData($module_name, $item_id)

// Alanları alma
$fields = customfields_getFields($module_name)

// Template için hazırlama
$template_data = customfields_prepareForTemplate($module_name, $item_id)</code>
            </div>
        </div>
    </div>
    
</div>

<?php
xoops_cp_footer();
?>