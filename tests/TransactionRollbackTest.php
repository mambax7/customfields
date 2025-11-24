<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../include/functions.php';

class TransactionRollbackTest extends TestCase
{
    protected function setUp(): void
    {
        if (!defined('XOOPS_ROOT_PATH')) {
            define('XOOPS_ROOT_PATH', realpath(__DIR__ . '/../../..'));
        }
        if (!defined('XOOPS_URL')) {
            define('XOOPS_URL', 'http://localhost');
        }
        if (!defined('CUSTOMFIELDS_ALLOW_ANON_SAVE')) {
            define('CUSTOMFIELDS_ALLOW_ANON_SAVE', true);
        }

        // DB stub that records BEGIN/COMMIT/ROLLBACK
        $GLOBALS['xoopsDB'] = new class {
            public array $queries = [];
            public function queryF($sql){ $this->queries[] = strtoupper(trim($sql)); return true; }
            public function prefix($t){ return $t; }
        };
    }

    protected function tearDown(): void
    {
        unset($GLOBALS['customfields_fieldHandler_stub'], $GLOBALS['customfields_dataHandler_stub']);
        unset($GLOBALS['xoopsDB']);
        $_POST = [];
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

    public function testRollbackOnSecondFieldFailure()
    {
        $fields = [
            $this->makeField(1, 'text', 'headline', 'Headline'),
            $this->makeField(2, 'text', 'subtitle', 'Subtitle'),
        ];

        // Field handler returns two fields
        $GLOBALS['customfields_fieldHandler_stub'] = new class($fields) {
            private $fields; public function __construct($f){ $this->fields=$f; }
            public function getFieldsByModule($m,$s=false){ return $this->fields; }
        };

        // Data handler succeeds for first field, fails for second
        $GLOBALS['customfields_dataHandler_stub'] = new class {
            private $count = 0;
            public function saveItemData($m,$i,$f,$v){
                $this->count++;
                return $this->count === 1; // true for first, false for second
            }
            public function getItemData($m,$i){ return []; }
            public function getItemDataArray($m,$i){ return []; }
        };

        $_POST = [
            'customfield_1' => 'A',
            'customfield_2' => 'B',
        ];

        $ok = customfields_saveData('publisher', 77);
        $this->assertFalse($ok, 'Save should fail and rollback when a field save fails');

        // Verify that ROLLBACK (or COMMIT on success) sequence contains START/BEGIN and ROLLBACK
        $queries = $GLOBALS['xoopsDB']->queries;
        $hasBegin = false; $hasRollback = false;
        foreach ($queries as $q) {
            if ($q === 'START TRANSACTION' || $q === 'BEGIN') { $hasBegin = true; }
            if ($q === 'ROLLBACK') { $hasRollback = true; }
        }
        $this->assertTrue($hasBegin, 'Transaction should begin');
        $this->assertTrue($hasRollback, 'Transaction should rollback on failure');
    }
}
