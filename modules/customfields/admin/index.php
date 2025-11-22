<?php
include_once '../../../include/cp_header.php';
include_once XOOPS_ROOT_PATH . '/modules/customfields/include/functions.php';

xoops_cp_header();

$fieldHandler = customfields_getFieldHandler();
$dataHandler = customfields_getDataHandler();

$total_fields = $fieldHandler->getCount();
$total_data = $dataHandler->getCount();

$module_stats = array();
$fields = $fieldHandler->getAll();
foreach ($fields as $field) {
    $module = $field->getVar('target_module');
    if (!isset($module_stats[$module])) {
        $module_stats[$module] = 0;
    }
    $module_stats[$module]++;
}
?>

<style>
/* Modern Dashboard Styles */
.cf-dashboard {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    background: #f8f9fa;
    padding: 20px;
    margin: -10px;
}


a:visited {
  text-decoration: none;
  color: #fff;
  background-color: transparent;
}

.cf-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    border-radius: 10px;
    margin-bottom: 30px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.cf-header h1 {
    margin: 0 0 10px 0;
    font-size: 28px;
    font-weight: 600;
}

.cf-header p {
    margin: 0;
    opacity: 0.9;
    font-size: 14px;
}

.cf-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.cf-stat-card {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    border-left: 4px solid #667eea;
}

.cf-stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
}

.cf-stat-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
    margin-bottom: 15px;
}

.cf-stat-number {
    font-size: 36px;
    font-weight: 700;
    color: #2d3748;
    margin: 10px 0;
}

.cf-stat-label {
    color: #718096;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 500;
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
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.cf-card-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #2d3748;
}

.cf-card-body {
    padding: 20px;
}

.cf-table {
    width: 100%;
    border-collapse: collapse;
}

.cf-table thead {
    background: #f7fafc;
}

.cf-table th {
    padding: 12px 15px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #4a5568;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #e2e8f0;
}

.cf-table td {
    padding: 15px;
    border-bottom: 1px solid #e2e8f0;
    color: #2d3748;
}

.cf-table tbody tr:hover {
    background: #f7fafc;
}

.cf-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.cf-badge-primary {
    background: #e6f2ff;
    color: #0066cc;
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

.cf-btn-sm {
    padding: 6px 12px;
    font-size: 12px;
}

.cf-empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #718096;
}

.cf-empty-icon {
    font-size: 64px;
    opacity: 0.3;
    margin-bottom: 20px;
}

.cf-guide {
    background: #fff5e6;
    border-left: 4px solid #ffa500;
    padding: 20px;
    border-radius: 6px;
    margin-top: 20px;
}

.cf-guide h4 {
    margin-top: 0;
    color: #d97706;
}

.cf-guide ol {
    margin: 10px 0;
    padding-left: 20px;
}

.cf-guide code {
    background: #f7fafc;
    padding: 2px 6px;
    border-radius: 3px;
    font-family: 'Courier New', monospace;
    font-size: 13px;
    color: #d97706;
}

@media (max-width: 768px) {
    .cf-stats {
        grid-template-columns: 1fr;
    }
    
    .cf-header {
        padding: 20px;
    }
    
    .cf-card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
}
</style>

<div class="cf-dashboard">
    <!-- Header -->
    <div class="cf-header">
        <h1>🎨 İlave Alanlar Modülü</h1>
        <p>Dinamik özel alanlar ile modüllerinizi genişletin</p>
    </div>
    
    <!-- Stats Cards -->
    <div class="cf-stats">
        <div class="cf-stat-card">
            <div class="cf-stat-icon">📋</div>
            <div class="cf-stat-number"><?php echo $total_fields; ?></div>
            <div class="cf-stat-label">Toplam Alan</div>
        </div>
        
        <div class="cf-stat-card">
            <div class="cf-stat-icon">💾</div>
            <div class="cf-stat-number"><?php echo $total_data; ?></div>
            <div class="cf-stat-label">Kayıtlı Veri</div>
        </div>
        
        <div class="cf-stat-card">
            <div class="cf-stat-icon">🔌</div>
            <div class="cf-stat-number"><?php echo count($module_stats); ?></div>
            <div class="cf-stat-label">Entegre Modül</div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="cf-card">
        <div class="cf-card-header">
            <h3>Hızlı İşlemler</h3>
            <a href="add.php" class="cf-btn cf-btn-primary">
                ➕ Yeni Alan Ekle
            </a>
        </div>
    </div>
    
    <!-- Module Stats -->
    <div class="cf-card">
        <div class="cf-card-header">
            <h3>📊 Modül Bazında Dağılım</h3>
        </div>
        <div class="cf-card-body">
            <?php if (!empty($module_stats)): ?>
            <table class="cf-table">
                <thead>
                    <tr>
                        <th>Modül</th>
                        <th style="text-align: center;">Alan Sayısı</th>
                        <th style="text-align: right;">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($module_stats as $module => $count): ?>
                    <tr>
                        <td>
                            <strong><?php echo ucfirst($module); ?></strong>
                        </td>
                        <td style="text-align: center;">
                            <span class="cf-badge cf-badge-primary"><?php echo $count; ?> alan</span>
                        </td>
                        <td style="text-align: right;">
                            <a href="manage.php?module=<?php echo $module; ?>" class="cf-btn cf-btn-sm cf-btn-primary">
                                Görüntüle →
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="cf-empty-state">
                <div class="cf-empty-icon">📭</div>
                <h3>Henüz Alan Eklenmemiş</h3>
                <p>Başlamak için yukarıdaki "Yeni Alan Ekle" butonuna tıklayın</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Quick Guide -->
    <div class="cf-guide">
        <h4>🚀 Hızlı Başlangıç Rehberi</h4>
        <ol>
            <li><strong>Alan Tanımla:</strong> "Yeni Alan Ekle" ile bir alan oluşturun (örn: <code>ek_resim</code>)</li>
            <li><strong>Entegre Et:</strong> Hedef modülün form dosyasına entegrasyon kodunu ekleyin</li>
            <li><strong>Kaydet:</strong> Veri kaydetme işlemine <code>customfields_saveData()</code> ekleyin</li>
            <li><strong>Göster:</strong> Template'e görüntüleme kodunu ekleyin</li>
        </ol>
        <p><a href="manage.php" style="color: #d97706; font-weight: 600;">Detaylı dokümantasyon için tıklayın →</a></p>
    </div>
</div>

<?php
xoops_cp_footer();
?>
