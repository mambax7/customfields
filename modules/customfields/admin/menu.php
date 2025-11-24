<?php

use Xmf\Module\Admin;
use XoopsModules\Customfields\{
    Helper
};
/** @var Admin $adminObject */
/** @var Helper $helper */


include dirname(__DIR__) . '/preloads/autoloader.php';

$moduleDirName = \basename(\dirname(__DIR__));
$moduleDirNameUpper = mb_strtoupper($moduleDirName);

$helper = Helper::getInstance();
$helper->loadLanguage('common');
$helper->loadLanguage('feedback');

$pathIcon32 = Admin::menuIconPath('');
$pathModIcon32 = XOOPS_URL .   '/modules/' . $moduleDirName . '/assets/images/';
if (is_object($helper->getModule()) && false !== $helper->getModule()->getInfo('modicons32')) {
    $pathModIcon32 = $helper->url($helper->getModule()->getInfo('modicons32'));
}
$adminmenu = array();

$adminmenu[] = [
    'title' => _MI_CUSTOMFIELDS_MENU_HOME,
    'link'  => 'admin/index.php',
    'icon'  => $pathModIcon32 . '/home.png',
];

$adminmenu[] = [
    'title' => _MI_CUSTOMFIELDS_MENU_01,
    'link'  => 'admin/manage.php',
    'icon'  => $pathModIcon32 . '/content.png',
];


$adminmenu[] = [
    'title' => _MI_CUSTOMFIELDS_MENU_05,
    'link'  => 'admin/fields.php',
    'icon'  => $pathModIcon32 . '/editcopy.png',
];


$adminmenu[] = [
    'title' => _MI_CUSTOMFIELDS_MENU_02,
    'link'  => 'admin/add.php',
    'icon'  => $pathModIcon32 . '/editcopy.png',
];

$adminmenu[] = [
    'title' => _MI_CUSTOMFIELDS_MENU_03,
    'link'  => 'admin/guide.php',
    'icon'  => $pathModIcon32 . '/about.png',
];

// Tests tab (admin-only) linking to test helpers
$adminmenu[] = [
    'title' => defined('_MI_CUSTOMFIELDS_MENU_TESTS') ? _MI_CUSTOMFIELDS_MENU_TESTS : 'Tests',
    'link'  => 'admin/tests.php',
    'icon'  => $pathModIcon32 . '/about.png',
];

$adminmenu[] = [
    'title' => _MI_CUSTOMFIELDS_MENU_ABOUT,
    'link' => 'admin/about.php',
    'icon' => $pathIcon32 . '/about.png',
];



