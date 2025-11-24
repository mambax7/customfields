<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../class/Renderer/TextareaRenderer.php';

class RendererTextareaTest extends TestCase
{
    private function makeField(array $vars)
    {
        return new class($vars) {
            private $vars; public function __construct($vars){ $this->vars = $vars; }
            public function getVar($k){ return $this->vars[$k] ?? null; }
        };
    }

    public function testTextareaRendererNl2brAndEscape()
    {
        $renderer = new \XoopsModules\Customfields\Renderer\TextareaRenderer();
        $field = $this->makeField(['field_type' => 'textarea', 'field_title' => 'T']);
        $out = $renderer->render($field, "Line1\nLine2 <i>x</i>");
        $this->assertStringContainsString('Line1<br />', $out);
        $this->assertStringContainsString('Line2', $out);
        $this->assertStringNotContainsString('<i>x</i>', $out);
        $this->assertStringContainsString('&lt;i&gt;x&lt;/i&gt;', $out);
    }
}
