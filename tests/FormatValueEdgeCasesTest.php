<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../include/functions.php';

class FormatValueEdgeCasesTest extends TestCase
{
    private function makeField(array $vars, array $options = [])
    {
        return new class($vars, $options) {
            private $vars; private $options;
            public function __construct($vars, $options) { $this->vars = $vars; $this->options = $options; }
            public function getVar($name) { return $this->vars[$name] ?? null; }
            public function getOptions() { return $this->options; }
        };
    }

    public function testImageEscapesAttributesAndAddsTitle()
    {
        if (!defined('XOOPS_URL')) {
            define('XOOPS_URL', 'http://localhost');
        }
        $field = $this->makeField(['field_type' => 'image', 'field_title' => 'A "title" <x>']);
        $out = customfields_formatValue($field, 'uploads/customfields/pic "x".jpg');
        $this->assertStringContainsString('<img', $out);
        $this->assertStringContainsString('loading="lazy"', $out);
        $this->assertStringContainsString('title="A &quot;title&quot; &lt;x&gt;"', $out);
        // Ensure inner quotes in attribute values are escaped
        $this->assertStringContainsString('&quot;', $out);
        $this->assertStringContainsString('http://localhost/uploads/customfields/', $out);
    }

    public function testFileLinkHasRelNoopenerAndEscapedLabel()
    {
        if (!defined('XOOPS_URL')) {
            define('XOOPS_URL', 'http://localhost');
        }
        $field = $this->makeField(['field_type' => 'file', 'field_title' => 'Doc']);
        $out = customfields_formatValue($field, 'uploads/customfields/my file".pdf');
        $this->assertStringContainsString('rel="noopener"', $out);
        $this->assertStringContainsString('<a href="http://localhost/uploads/customfields/', $out);
        // Label is basename and escaped (double quote should be encoded as &quot;)
        $this->assertStringContainsString('my file&quot;.pdf', $out);
    }

    public function testSelectAndCheckboxLabelsAreEscaped()
    {
        $fieldSel = $this->makeField(['field_type' => 'select', 'field_title' => 'S'], [
            'k1' => 'A <B> & "',
        ]);
        $this->assertSame('A &lt;B&gt; &amp; &quot;', customfields_formatValue($fieldSel, 'k1'));

        $fieldCb = $this->makeField(['field_type' => 'checkbox', 'field_title' => 'C'], [
            '1' => 'One & "', '2' => '<Two>'
        ]);
        $out = customfields_formatValue($fieldCb, '1,2');
        $this->assertSame('One &amp; &quot;, &lt;Two&gt;', $out);
    }
}
