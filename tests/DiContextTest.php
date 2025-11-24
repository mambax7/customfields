<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../include/functions.php';

class DiContextTest extends TestCase
{
    protected function setUp(): void
    {
        if (!defined('XOOPS_ROOT_PATH')) {
            define('XOOPS_ROOT_PATH', realpath(__DIR__ . '/../../..'));
        }
        if (!defined('XOOPS_URL')) {
            define('XOOPS_URL', 'http://localhost');
        }
        // Allow anonymous saves in tests
        if (!defined('CUSTOMFIELDS_ALLOW_ANON_SAVE')) {
            define('CUSTOMFIELDS_ALLOW_ANON_SAVE', true);
        }

        // Minimal DB stub for transaction helpers
        $GLOBALS['xoopsDB'] = new class {
            public array $queries = [];
            public function queryF($sql){ $this->queries[] = $sql; return true; }
            public function prefix($t){ return $t; }
            public function quoteString($s){ return "'" . addslashes((string)$s) . "'"; }
        };

        // Reset handler stubs
        unset($GLOBALS['customfields_fieldHandler_stub'], $GLOBALS['customfields_dataHandler_stub']);
    }

    protected function tearDown(): void
    {
        unset($GLOBALS['customfields_fieldHandler_stub'], $GLOBALS['customfields_dataHandler_stub']);
        unset($GLOBALS['xoopsDB']);
        unset($GLOBALS['customfields_upload_service']);
        unset($GLOBALS['customfields_validator']);
        $_POST = [];
        $_FILES = [];
    }

    private function makeField($id, $type, $name, $title)
    {
        return new class($id,$type,$name,$title) {
            private $id; private $type; private $name; private $title;
            public function __construct($id,$type,$name,$title){ $this->id=$id; $this->type=$type; $this->name=$name; $this->title=$title; }
            public function getVar($k){
                switch ($k) {
                    case 'field_id': return $this->id;
                    case 'field_type': return $this->type;
                    case 'field_name': return $this->name;
                    case 'field_title': return $this->title;
                }
                return null;
            }
            public function getOptions(){ return []; }
        };
    }

    public function testUploadServiceInjectedViaContextIsUsed()
    {
        // Fake upload service always returns a known path
        $fakeService = new class {
            public function handle($name, $type){ return 'uploads/customfields/injected.png'; }
        };

        $field = $this->makeField(10, 'image', 'photo', 'Photo');
        $GLOBALS['customfields_fieldHandler_stub'] = new class($field) {
            private $f; public function __construct($f){ $this->f=$f; }
            public function getFieldsByModule($m,$s=false){ return [$this->f]; }
        };
        $store = new class {
            public $saved = [];
            public function saveItemData($m,$i,$f,$v){ $this->saved[$f] = $v; return true; }
            public function getItemData($m,$i){ return []; }
            public function getItemDataArray($m,$i){ return []; }
        };
        $GLOBALS['customfields_dataHandler_stub'] = $store;

        // Simulate presence of a file field in POST and FILES
        $_POST = ['customfield_10' => ''];
        $_FILES['customfield_10'] = [ 'error' => UPLOAD_ERR_OK, 'size' => 1, 'name' => 'x.png', 'tmp_name' => __FILE__ ];

        $ok = customfields_saveData('publisher', 5, ['uploadService' => $fakeService]);
        $this->assertTrue($ok);
        $this->assertSame('uploads/customfields/injected.png', $store->saved[10]);
    }

    public function testValidatorInjectedViaContextIsUsed()
    {
        // Fake validator that uppercases text
        $fakeValidator = new class {
            public function validateText($v, $max){ return strtoupper((string)$v); }
            public function validateDate($v, $fmt){ return '2025-01-01'; }
            public function validateOption($v, $opts){ return (string)$v; }
            public function normalizeCheckbox($csv, $allowed){ return (string)$csv; }
            public function sanitizeEditor($html){ return (string)$html; }
        };

        $field = $this->makeField(1, 'text', 'headline', 'Headline');
        $GLOBALS['customfields_fieldHandler_stub'] = new class($field) {
            private $f; public function __construct($f){ $this->f=$f; }
            public function getFieldsByModule($m,$s=false){ return [$this->f]; }
        };
        $store = new class {
            public $saved = [];
            public function saveItemData($m,$i,$f,$v){ $this->saved[$f] = $v; return true; }
            public function getItemData($m,$i){ return []; }
            public function getItemDataArray($m,$i){ return []; }
        };
        $GLOBALS['customfields_dataHandler_stub'] = $store;

        $_POST = ['customfield_1' => 'hello'];
        $ok = customfields_saveData('publisher', 7, ['validator' => $fakeValidator]);
        $this->assertTrue($ok);
        $this->assertSame('HELLO', $store->saved[1]);
    }
}
