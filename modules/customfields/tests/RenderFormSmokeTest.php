<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../include/functions.php';

class RenderFormSmokeTest extends TestCase
{
    protected function setUp(): void
    {
        if (!defined('XOOPS_ROOT_PATH')) {
            define('XOOPS_ROOT_PATH', realpath(__DIR__ . '/../../..'));
        }
        if (!defined('XOOPS_URL')) {
            define('XOOPS_URL', 'http://localhost');
        }

        // Security stub to provide token
        $GLOBALS['xoopsSecurity'] = new class {
            public function createToken(){ return 'unit-token'; }
            public function check(){ return true; }
            public function getTokenHTML(){ return '<input type="hidden" name="XOOPS_TOKEN_REQUEST" value="tok" />'; }
        };

        // Provide a single text field with special chars in title/description
        $field = new class {
            public function getVar($k){
                switch ($k) {
                    case 'field_id': return 1;
                    case 'field_type': return 'text';
                    case 'field_name': return 'headline';
                    case 'field_title': return 'A "Title" <X>';
                    case 'field_description': return 'Desc & more';
                    case 'required': return 1;
                }
                return null;
            }
            public function getOptions(){ return []; }
        };
        $GLOBALS['customfields_fieldHandler_stub'] = new class($field) {
            private $f; public function __construct($f){ $this->f=$f; }
            public function getFieldsByModule($m,$s=true){ return [$this->f]; }
            public function renderField($f, $v = ''){ return '<input type="text" name="customfield_1" />'; }
        };
    }

    protected function tearDown(): void
    {
        unset($GLOBALS['customfields_fieldHandler_stub']);
        unset($GLOBALS['xoopsSecurity']);
    }

    public function testRenderFormContainsCsrfAndEscapedLabels()
    {
        $html = customfields_renderForm('publisher', 0);
        $this->assertStringContainsString('name="XOOPS_TOKEN_REQUEST"', $html);
        // Label should contain escaped title
        $this->assertStringContainsString('A &quot;Title&quot; &lt;X&gt;', $html);
        // Help block escaped too
        $this->assertStringContainsString('Desc &amp; more', $html);
        // Required marker exists
        $this->assertStringContainsString('<span class="required">*</span>', $html);
    }
}
