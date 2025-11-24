<?php

use PHPUnit\Framework\TestCase;

/**
 * Lightweight integration test of save and prepare flows using stubs.
 */
class SavePrepareFlowTest extends TestCase
{
    protected function setUp(): void
    {
        // Ensure constants exist
        if (!defined('XOOPS_ROOT_PATH')) {
            define('XOOPS_ROOT_PATH', realpath(__DIR__ . '/../../..'));
        }
        if (!defined('XOOPS_URL')) {
            define('XOOPS_URL', 'http://localhost');
        }
        // Allow anonymous saves in tests (bypass permission gate)
        if (!defined('CUSTOMFIELDS_ALLOW_ANON_SAVE')) {
            define('CUSTOMFIELDS_ALLOW_ANON_SAVE', true);
        }

        // Reset handler stubs globals before each test
        unset($GLOBALS['customfields_fieldHandler_stub'], $GLOBALS['customfields_dataHandler_stub']);
        // Minimal DB stub to satisfy transaction queries
        $GLOBALS['xoopsDB'] = new class {
            public function queryF($sql) { return true; }
            public function prefix($t){ return $t; }
        };
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

    public function testSaveAndPrepareForTextAndSelect()
    {
        require_once dirname(__DIR__) . '/include/functions.php';

        $fields = [
            $this->makeField(1, 'text', 'headline', 'Headline'),
            $this->makeField(2, 'select', 'color', 'Color', ['r' => 'Red', 'g' => 'Green']),
            $this->makeField(3, 'checkbox', 'flags', 'Flags', ['a' => 'A', 'b' => 'B']),
            $this->makeField(4, 'date', 'when', 'When'),
        ];

        // Field handler stub
        $GLOBALS['customfields_fieldHandler_stub'] = new class($fields) {
            private $fields;
            public function __construct($fields){ $this->fields = $fields; }
            public function getFieldsByModule($module, $showInFormOnly = false){ return $this->fields; }
        };

        // Data handler stub storing results in memory
        $GLOBALS['customfields_dataHandler_stub'] = new class {
            public $saved = [];
            public function saveItemData($module, $itemId, $fieldId, $value){
                $this->saved[$module][$itemId][$fieldId] = (string)$value; return true;
            }
            public function getItemData($module, $itemId){
                return isset($this->saved[$module][$itemId]) ? $this->saved[$module][$itemId] : [];
            }
            public function getItemDataArray($module, $itemId){ return $this->getItemData($module, $itemId); }
        };

        // Simulate POST
        $_POST = [
            'customfield_1' => ' Hello World ',
            'customfield_2' => 'g',
            'customfield_3' => 'a,b,x',
            'customfield_4' => '2025-11-05',
        ];

        // Save
        $ok = customfields_saveData('publisher', 42);
        $this->assertTrue($ok);

        // Prepare
        $prepared = customfields_prepareForTemplate('publisher', 42);
        $this->assertArrayHasKey('headline', $prepared);
        $this->assertSame('Hello World', $prepared['headline']['value']); // trimmed by validator
        $this->assertSame('Green', $prepared['color']['formatted_value']);
        $this->assertSame('A, B', $prepared['flags']['formatted_value']);
        $this->assertSame('05.11.2025', $prepared['when']['formatted_value']);
    }
}
