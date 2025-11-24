<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../include/functions.php';
require_once __DIR__ . '/../class/Renderer/DateRenderer.php';
require_once __DIR__ . '/../class/Config.php';

/**
 * @runTestsInSeparateProcesses
 */
class DateFormatConfigTest extends TestCase
{
    protected function setUp(): void
    {
        if (!defined('XOOPS_ROOT_PATH')) {
            define('XOOPS_ROOT_PATH', realpath(__DIR__ . '/../../..'));
        }
        if (!defined('XOOPS_URL')) {
            define('XOOPS_URL', 'http://localhost');
        }
        // Ensure custom display format is defined for this test
        if (!defined('CUSTOMFIELDS_DISPLAY_DATE_FORMAT')) {
            define('CUSTOMFIELDS_DISPLAY_DATE_FORMAT', 'Y/m/d');
        }
    }

    private function makeField(array $vars)
    {
        return new class($vars) {
            private $vars; public function __construct($vars){ $this->vars = $vars; }
            public function getVar($k){ return $this->vars[$k] ?? null; }
        };
    }

    public function testDateRendererUsesConfigFormat()
    {
        $renderer = new \XoopsModules\Customfields\Renderer\DateRenderer();
        $field = $this->makeField(['field_type' => 'date']);
        $out = $renderer->render($field, '2025-02-03');
        $this->assertSame('2025/02/03', $out);
    }

    public function testLegacyFormatterUsesConfigFormat()
    {
        $field = $this->makeField(['field_type' => 'date']);
        $out = customfields_formatValue($field, '2025-12-31');
        $this->assertSame('2025/12/31', $out);
    }
}
