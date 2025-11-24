<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../include/functions.php';

class SaveDataNegativeTest extends TestCase
{
    protected function setUp(): void
    {
        if (!defined('XOOPS_ROOT_PATH')) {
            define('XOOPS_ROOT_PATH', realpath(__DIR__ . '/../../..'));
        }
        if (!defined('XOOPS_URL')) {
            define('XOOPS_URL', 'http://localhost');
        }

        // Minimal DB stub to satisfy transaction calls
        $GLOBALS['xoopsDB'] = new class {
            public function queryF($sql) { return true; }
            public function quoteString($s){ return "'" . addslashes((string)$s) . "'"; }
        };

        // Provide a field handler stub with a single text field to avoid DB dependencies
        $field = new class {
            public function getVar($k){
                switch ($k) {
                    case 'field_id': return 1;
                    case 'field_type': return 'text';
                    case 'field_name': return 'headline';
                    case 'field_title': return 'Headline';
                }
                return null;
            }
            public function getOptions(){ return []; }
        };
        $GLOBALS['customfields_fieldHandler_stub'] = new class($field) {
            private $f; public function __construct($f){ $this->f = $f; }
            public function getFieldsByModule($module, $showInFormOnly = false){ return [$this->f]; }
        };
        $GLOBALS['customfields_dataHandler_stub'] = new class {
            public function saveItemData($module, $itemId, $fieldId, $value){ return true; }
            public function getItemData($module, $itemId){ return []; }
            public function getItemDataArray($module, $itemId){ return []; }
        };
    }

    protected function tearDown(): void
    {
        unset($GLOBALS['customfields_fieldHandler_stub'], $GLOBALS['customfields_dataHandler_stub']);
        unset($GLOBALS['xoopsSecurity'], $GLOBALS['xoopsUser'], $GLOBALS['xoopsDB']);
        // Reset any config constants that might affect behavior (not strictly necessary here)
    }

    public function testCsrfFailureBlocksSave()
    {
        // Stub XOOPS security to fail check()
        $GLOBALS['xoopsSecurity'] = new class {
            public function check(){ return false; }
            public function createToken(){ return 'tok'; }
        };

        $_POST = ['customfield_1' => 'Value'];
        $ok = customfields_saveData('publisher', 101);
        $this->assertFalse($ok, 'CSRF check should block saving and return false');
    }

    public function testAnonymousBlockedByDefault()
    {
        // No xoopsUser object present -> anonymous
        unset($GLOBALS['xoopsUser']);
        // Ensure the allow-anon constant is not defined (default is false)
        if (defined('CUSTOMFIELDS_ALLOW_ANON_SAVE')) {
            $this->markTestSkipped('CUSTOMFIELDS_ALLOW_ANON_SAVE defined in environment; cannot verify default blocking');
        }
        $_POST = ['customfield_1' => 'Value'];
        $ok = customfields_saveData('publisher', 102);
        $this->assertFalse($ok, 'Anonymous users should be blocked by default');
    }

    public function testAdminOnlyModulesBlockNonAdmin()
    {
        // Define admin-only module list
        if (!defined('CUSTOMFIELDS_ADMIN_ONLY_MODULES')) {
            define('CUSTOMFIELDS_ADMIN_ONLY_MODULES', ['secure_mod']);
        }
        // Define admin group constant and create a non-admin user stub
        if (!defined('XOOPS_GROUP_ADMIN')) {
            define('XOOPS_GROUP_ADMIN', 1);
        }
        $GLOBALS['xoopsUser'] = new class {
            public function getGroups(){ return [2,3]; } // not including admin group
        };
        $_POST = ['customfield_1' => 'Value'];
        $ok = customfields_saveData('secure_mod', 103);
        $this->assertFalse($ok, 'Non-admin user should be blocked for admin-only modules');
    }
}
