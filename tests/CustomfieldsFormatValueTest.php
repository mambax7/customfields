<?php

use PHPUnit\Framework\TestCase;

/**
 * Unit tests for customfields_formatValue()
 */
class CustomfieldsFormatValueTest extends TestCase
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

    public function testEscapesDefaultText()
    {
        $field = $this->makeField(['field_type' => 'text', 'field_title' => 'Title']);
        // Provide raw double quotes to be escaped into &quot;
        $out = customfields_formatValue($field, '"<script>"');
        $this->assertStringNotContainsString('<script>', $out);
        $this->assertStringContainsString('&quot;&lt;script&gt;&quot;', $out);
    }

    public function testTextareaConvertsNewlinesAndEscapes()
    {
        $field = $this->makeField(['field_type' => 'textarea', 'field_title' => 'T']);
        $out = customfields_formatValue($field, "line1\nline2 <b>bold</b>");
        // nl2br in PHP may insert a trailing \n after <br /> depending on flags, so avoid strict match
        $this->assertStringContainsString('line1<br />', $out);
        $this->assertStringContainsString('line2', $out);
        $this->assertStringNotContainsString('<b>bold</b>', $out);
        $this->assertStringContainsString('&lt;b&gt;bold&lt;/b&gt;', $out);
    }

    public function testDateFormatting()
    {
        $field = $this->makeField(['field_type' => 'date', 'field_title' => 'D']);
        $out = customfields_formatValue($field, '2025-01-15');
        $this->assertSame('15.01.2025', $out);
    }

    public function testSelectUsesOptions()
    {
        $field = $this->makeField(['field_type' => 'select', 'field_title' => 'S'], [
            'a' => 'Apple', 'b' => 'Banana'
        ]);
        $this->assertSame('Apple', customfields_formatValue($field, 'a'));
        $this->assertSame('x', customfields_formatValue($field, 'x'));
    }

    public function testCheckboxJoinsLabels()
    {
        $field = $this->makeField(['field_type' => 'checkbox', 'field_title' => 'C'], [
            '1' => 'One', '2' => 'Two', '3' => 'Three'
        ]);
        $out = customfields_formatValue($field, '1,3');
        $this->assertSame('One, Three', $out);
    }

    public function testImageAndFileProduceLinks()
    {
        $imgField = $this->makeField(['field_type' => 'image', 'field_title' => 'Avatar']);
        $fileField = $this->makeField(['field_type' => 'file', 'field_title' => 'Doc']);
        $img = customfields_formatValue($imgField, 'uploads/customfields/a.jpg');
        $file = customfields_formatValue($fileField, 'uploads/customfields/b.pdf');
        $this->assertStringContainsString('<img', $img);
        $this->assertStringContainsString('uploads/customfields/a.jpg', $img);
        $this->assertStringContainsString('<a href="', $file);
        $this->assertStringContainsString('b.pdf', $file);
    }
}
