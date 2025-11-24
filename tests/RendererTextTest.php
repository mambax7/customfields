<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../class/Renderer/TextRenderer.php';

class RendererTextTest extends TestCase
{
    private function makeField(array $vars)
    {
        return new class($vars) {
            private $vars; public function __construct($vars){ $this->vars = $vars; }
            public function getVar($k){ return $this->vars[$k] ?? null; }
        };
    }

    public function testTextRendererEscapesHtml()
    {
        $renderer = new \XoopsModules\Customfields\Renderer\TextRenderer();
        $field = $this->makeField(['field_type' => 'text', 'field_title' => 'T']);
        $out = $renderer->render($field, '"<b>x</b>');
        $this->assertSame('&quot;&lt;b&gt;x&lt;/b&gt;', $out);
    }
}
