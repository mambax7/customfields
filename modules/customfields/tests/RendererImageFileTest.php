<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../class/Renderer/ImageRenderer.php';
require_once __DIR__ . '/../class/Renderer/FileRenderer.php';

class RendererImageFileTest extends TestCase
{
    protected function setUp(): void
    {
        if (!defined('XOOPS_URL')) {
            define('XOOPS_URL', 'http://localhost');
        }
    }

    private function makeField(array $vars)
    {
        return new class($vars) {
            private $vars; public function __construct($vars){ $this->vars = $vars; }
            public function getVar($k){ return $this->vars[$k] ?? null; }
        };
    }

    public function testImageRendererOutputsSafeImgTag()
    {
        $renderer = new \XoopsModules\Customfields\Renderer\ImageRenderer();
        $field = $this->makeField(['field_type' => 'image', 'field_title' => 'A " <x>']);
        $out = $renderer->render($field, 'uploads/customfields/pic.jpg');
        $this->assertStringContainsString('<img', $out);
        $this->assertStringContainsString('loading="lazy"', $out);
        $this->assertStringContainsString('http://localhost/uploads/customfields/pic.jpg', $out);
        $this->assertStringContainsString('alt="A &quot; &lt;x&gt;"', $out);
    }

    public function testFileRendererOutputsSafeLink()
    {
        $renderer = new \XoopsModules\Customfields\Renderer\FileRenderer();
        $field = $this->makeField(['field_type' => 'file', 'field_title' => 'Doc']);
        $out = $renderer->render($field, 'uploads/customfields/my file".pdf');
        $this->assertStringContainsString('<a href="http://localhost/uploads/customfields/', $out);
        $this->assertStringContainsString('rel="noopener"', $out);
        $this->assertStringContainsString('my file&quot;.pdf', $out);
    }
}
