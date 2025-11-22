<?php
$modversion = array();

$modversion['name'] = 'İlave Alanlar';
$modversion['version'] = '1.0.0';
$modversion['description'] = 'Diğer modüllere özel alanlar eklemek için genişletilebilir modül';
$modversion['author'] = 'Eren';
$modversion['credits'] = 'XOOPS Türkiye';
$modversion['help'] = '';
$modversion['license'] = 'GPL';
$modversion['official'] = 0;
$modversion['image'] = 'images/logo.png';
$modversion['dirname'] = 'customfields';

$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

$modversion['tables'][0] = 'customfields_definitions';
$modversion['tables'][1] = 'customfields_data';

$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

$modversion['hasMain'] = 0;
$modversion['hasSearch'] = 0;
$modversion['hasComments'] = 0;

$modversion['onInstall'] = 'include/install.php';

$modversion['config'][1] = array(
    'name' => 'max_file_size',
    'title' => '_MI_CUSTOMFIELDS_MAX_FILE_SIZE',
    'description' => '_MI_CUSTOMFIELDS_MAX_FILE_SIZE_DESC',
    'formtype' => 'text',
    'valuetype' => 'int',
    'default' => 2048
);

$modversion['config'][2] = array(
    'name' => 'allowed_image_types',
    'title' => '_MI_CUSTOMFIELDS_ALLOWED_IMAGE_TYPES',
    'description' => '_MI_CUSTOMFIELDS_ALLOWED_IMAGE_TYPES_DESC',
    'formtype' => 'text',
    'valuetype' => 'text',
    'default' => 'jpg,jpeg,png,gif,webp'
);

$modversion['config'][3] = array(
    'name' => 'allowed_file_types',
    'title' => '_MI_CUSTOMFIELDS_ALLOWED_FILE_TYPES',
    'description' => '_MI_CUSTOMFIELDS_ALLOWED_FILE_TYPES_DESC',
    'formtype' => 'text',
    'valuetype' => 'text',
    'default' => 'pdf,doc,docx,xls,xlsx,zip,rar'
);
