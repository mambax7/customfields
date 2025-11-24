<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../include/functions.php';

class SaveDataEdgeCasesTest extends TestCase
{
    protected function setUp(): void
    {
        if (!defined('XOOPS_ROOT_PATH')) {
            define('XOOPS_ROOT_PATH', realpath(__DIR__ . '/../../..'));
        }
        if (!defined('XOOPS_URL')) {
            define('XOOPS_URL', 'http://localhost');
        }
        // Allow anon saves in tests unless a specific test overrides
        if (!defined('CUSTOMFIELDS_ALLOW_ANON_SAVE')) {
            define('CUSTOMFIELDS_ALLOW_ANON_SAVE', true);
        }
        // Minimal DB stub to satisfy transaction methods
        $GLOBALS['xoopsDB'] = new class {
            public function queryF($sql) { return true; }
            public function prefix($t){ return $t; }
        };
        // Reset handler stubs
        unset($GLOBALS['customfields_fieldHandler_stub'], $GLOBALS['customfields_dataHandler_stub']);
    }

    protected function tearDown(): void
    {
        unset($GLOBALS['customfields_fieldHandler_stub'], $GLOBALS['customfields_dataHandler_stub']);
        unset($GLOBALS['xoopsDB']);
        $_POST = [];
        $_FILES = [];
    }

    private function makeField($id, $type, $name, $title, array $options = [])
    {
        return new class($id, $type, $name, $title, $options) {
            private $id; private $type; private $name; private $title; private $options;
            public function __construct($id,$type,$name,$title,$options){ $this->id=$id; $this->type=$type; $this->name=$name; $this->title=$title; $this->options=$options; }
            public function getVar($k){
                switch ($k) {
                    case 'field_id': return $this->id;
                    case 'field_type': return $this->type;
                    case 'field_name': return $this->name;
                    case 'field_title': return $this->title;
                    default: return null;
                }
            }
            public function getOptions(){ return $this->options; }
        };
    }

    public function testModuleAllowlistBlocksNonWhitelisted()
    {
        if (!defined('CUSTOMFIELDS_ALLOWED_MODULES')) {
            define('CUSTOMFIELDS_ALLOWED_MODULES', ['publisher']);
        }
        // Provide at least one field so function iterates if not blocked
        $field = $this->makeField(1, 'text', 'headline', 'Headline');
        $GLOBALS['customfields_fieldHandler_stub'] = new class($field) {
            private $f; public function __construct($f){ $this->f=$f; }
            public function getFieldsByModule($module, $showInFormOnly=false){ return [$this->f]; }
        };
        $GLOBALS['customfields_dataHandler_stub'] = new class {
            public function saveItemData($m,$i,$f,$v){ return true; }
            public function getItemData($m,$i){ return []; }
            public function getItemDataArray($m,$i){ return []; }
        };
        $_POST = ['customfield_1' => 'x'];
        // Non-whitelisted module should be rejected
        $ok = customfields_saveData('news', 1);
        $this->assertFalse($ok);
    }

    public function testEditorSanitizeRemovesScriptAndKeepsAllowedTags()
    {
        $fields = [
            $this->makeField(1, 'editor', 'body', 'Body'),
        ];
        $store = new class {
            public $saved = [];
            public function saveItemData($m,$i,$f,$v){ $this->saved[$f] = $v; return true; }
            public function getItemData($m,$i){ return []; }
            public function getItemDataArray($m,$i){ return []; }
        };
        $GLOBALS['customfields_fieldHandler_stub'] = new class($fields) {
            private $fields; public function __construct($f){ $this->fields=$f; }
            public function getFieldsByModule($m,$s=false){ return $this->fields; }
        };
        $GLOBALS['customfields_dataHandler_stub'] = $store;

        $_POST = ['customfield_1' => '<p>Hi<script>alert(1)</script><strong>There</strong></p>'];
        $ok = customfields_saveData('publisher', 7);
        $this->assertTrue($ok);
        $this->assertArrayHasKey(1, $store->saved);
        $this->assertStringNotContainsString('<script>', $store->saved[1]);
        $this->assertStringContainsString('<strong>There</strong>', $store->saved[1]);
    }

    public function testCheckboxNormalizationFiltersUnknownAndDuplicates()
    {
        $fields = [
            $this->makeField(2, 'checkbox', 'flags', 'Flags', ['a' => 'A', 'b' => 'B'])
        ];
        $store = new class {
            public $saved = [];
            public function saveItemData($m,$i,$f,$v){ $this->saved[$f] = $v; return true; }
            public function getItemData($m,$i){ return []; }
            public function getItemDataArray($m,$i){ return []; }
        };
        $GLOBALS['customfields_fieldHandler_stub'] = new class($fields) {
            private $fields; public function __construct($f){ $this->fields=$f; }
            public function getFieldsByModule($m,$s=false){ return $this->fields; }
        };
        $GLOBALS['customfields_dataHandler_stub'] = $store;

        $_POST = ['customfield_2' => ' a , x , b , a '];
        $ok = customfields_saveData('publisher', 8);
        $this->assertTrue($ok);
        $this->assertSame('a,b', $store->saved[2]);
    }

    public function testFileBranchRejectionDoesNotBreakSave()
    {
        $fields = [
            $this->makeField(3, 'image', 'photo', 'Photo'),
        ];
        $store = new class {
            public $saved = [];
            public function saveItemData($m,$i,$f,$v){ $this->saved[$f] = $v; return true; }
            public function getItemData($m,$i){ return []; }
            public function getItemDataArray($m,$i){ return []; }
        };
        $GLOBALS['customfields_fieldHandler_stub'] = new class($fields) {
            private $fields; public function __construct($f){ $this->fields=$f; }
            public function getFieldsByModule($m,$s=false){ return $this->fields; }
        };
        $GLOBALS['customfields_dataHandler_stub'] = $store;

        // Simulate a file too large for default 5MB limit
        $_POST = ['customfield_3' => 'ignored'];
        $_FILES['customfield_3'] = [
            'error' => UPLOAD_ERR_OK,
            'size'  => 10 * 1024 * 1024,
            'name'  => 'huge.jpg',
            'tmp_name' => __FILE__,
        ];
        $ok = customfields_saveData('publisher', 9);
        $this->assertTrue($ok);
        // Upload rejected -> saved value should be empty string
        $this->assertSame('', $store->saved[3]);
    }
}
