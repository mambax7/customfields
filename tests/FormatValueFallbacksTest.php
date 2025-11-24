<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../include/functions.php';

class FormatValueFallbacksTest extends TestCase
{
    private function makeField(array $vars, array $options = [])
    {
        return new class($vars, $options) {
            private $vars; private $options;
            public function __construct($vars, $options){ $this->vars = $vars; $this->options = $options; }
            public function getVar($k){ return $this->vars[$k] ?? null; }
            public function getOptions(){ return $this->options; }
        };
    }

    public function testEmptyValueReturnsEmptyString()
    {
        $field = $this->makeField(['field_type' => 'text']);
        $this->assertSame('', customfields_formatValue($field, ''));
        $this->assertSame('', customfields_formatValue($field, null));
    }

    public function testUnsupportedTypeFallsBackToEscapedString()
    {
        $field = $this->makeField(['field_type' => 'unknown']);
        $out = customfields_formatValue($field, '"<tag>');
        $this->assertSame('&quot;&lt;tag&gt;', $out);
    }

    public function testInvalidDateHandled()
    {
        $field = $this->makeField(['field_type' => 'date']);
        $out = customfields_formatValue($field, 'invalid-date');
        // strtotime('invalid-date') may return false; renderer or fallback returns ''
        $this->assertTrue($out === '' || preg_match('/\d{2}\.\d{2}\.\d{4}/', $out) === 1);
    }
}
