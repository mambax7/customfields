<?php
function xoops_module_install_customfields($module)
{
    global $xoopsDB;
    
    $upload_dir = XOOPS_ROOT_PATH . '/uploads/customfields/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $htaccess = $upload_dir . '.htaccess';
    if (!file_exists($htaccess)) {
        $content = "# Prevent PHP execution\n";
        $content .= "<FilesMatch \"\\.(php|php3|php4|php5|phtml)$\">\n";
        $content .= "    deny from all\n";
        $content .= "</FilesMatch>\n";
        file_put_contents($htaccess, $content);
    }
    
    return true;
}
