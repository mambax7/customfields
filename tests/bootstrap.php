<?php
// Minimal bootstrap for running unit tests of the customfields module

// Define minimal XOOPS constants expected by helpers
if (!defined('XOOPS_ROOT_PATH')) {
    // Point to project root (adjusted relatively from module dir during PHPUnit run)
    define('XOOPS_ROOT_PATH', realpath(__DIR__ . '/../../..'));
}
if (!defined('XOOPS_URL')) {
    define('XOOPS_URL', 'http://localhost');
}

// Load functions under test without requiring full XOOPS runtime
require_once dirname(__DIR__) . '/include/functions.php';
