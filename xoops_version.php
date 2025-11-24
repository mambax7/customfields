<?php

require __DIR__ . '/preloads/autoloader.php';

$moduleDirName      = basename(__DIR__);
$moduleDirNameUpper = mb_strtoupper($moduleDirName);

$modversion = array();

$modversion['name'] = _MI_CUSTOMFIELDS_NAME;
$modversion['version'] = '1.1.1';
$modversion['description'] = _MI_CUSTOMFIELDS_DESC; //'Diğer modüllere özel alanlar eklemek için genişletilebilir modül';
$modversion['author'] = 'Eren';
$modversion['credits'] = 'XOOPS Türkiye';
$modversion['help'] = '';
$modversion['license'] = 'GPL';
$modversion['official'] = 0;
$modversion['image'] = 'images/logo.png';
$modversion['dirname'] = $moduleDirName;

$modversion['modicons16']          = 'assets/images';
$modversion['modicons32']          = 'assets/images';
$modversion['image']               = 'assets/images/logo.png';

$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

$modversion['tables'][0] = 'customfields_definitions';
$modversion['tables'][1] = 'customfields_data';

$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';
$modversion['system_menu'] = 1;


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


$modversion['templates'] = [
    // Admin
    ['file' => 'customfields_admin_guide.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => 'customfields_admin_add.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => 'customfields_admin_fields.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => 'customfields_admin_index.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => 'customfields_admin_manage.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => 'customfields_admin_field_form.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => 'customfields_admin_tests.tpl', 'description' => '', 'type' => 'admin'],
    // User
//    ['file' => 'ZZZZZ.tpl', 'description' => ''],
//    ['file' => 'ZZZZZ.tpl', 'description' => ''],
//    ['file' => 'ZZZZZ.tpl', 'description' => ''],
//    ['file' => 'ZZZZZ.tpl', 'description' => ''],
//    ['file' => 'ZZZZZ.tpl', 'description' => ''],
];


