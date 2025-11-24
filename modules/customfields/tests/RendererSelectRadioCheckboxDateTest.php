<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../class/Renderer/SelectRenderer.php';
require_once __DIR__ . '/../class/Renderer/RadioRenderer.php';
require_once __DIR__ . '/../class/Renderer/CheckboxRenderer.php';
require_once __DIR__ . '/../class/Renderer/DateRenderer.php';

class RendererSelectRadioCheckboxDateTest extends TestCase
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

    public function testSelectRendererUsesOptionsAndEscapes()
    {
        $r = new \XoopsModules\Customfields\Renderer\SelectRenderer();
        $f = $this->makeField(['field_type' => 'select'], ['k' => 'A <b>&' ]);
        $this->assertSame('A &lt;b&gt;&amp;', $r->render($f, 'k'));
        $this->assertSame('x', $r->render($f, 'x'));
    }

    public function testRadioRendererUsesOptionsAndEscapes()
    {
        $r = new \XoopsModules\Customfields\Renderer\RadioRenderer();
        $f = $this->makeField(['field_type' => 'radio'], ['1' => 'Yes "']);
        $this->assertSame('Yes &quot;', $r->render($f, '1'));
        $this->assertSame('2', $r->render($f, '2'));
    }

    public function testCheckboxRendererJoinsLabels()
    {
        $r = new \XoopsModules\Customfields\Renderer\CheckboxRenderer();
        $f = $this->makeField(['field_type' => 'checkbox'], ['a' => 'A', 'b' => 'B']);
        $this->assertSame('A, B', $r->render($f, 'a,b'));
        $this->assertSame('', $r->render($f, ''));
    }

    public function testDateRendererFormatsOrEmptyOnInvalid()
    {
        $r = new \XoopsModules\Customfields\Renderer\DateRenderer();
        $f = $this->makeField(['field_type' => 'date']);
        $this->assertSame('01.02.2025', $r->render($f, '2025-02-01'));
        $this->assertSame('', $r->render($f, 'bad-date'));
        $this->assertSame('', $r->render($f, ''));
    }
}
