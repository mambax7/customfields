<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../include/functions.php';
require_once __DIR__ . '/../class/Logger.php';

class UrlAndLoggerTest extends TestCase
{
    protected function setUp(): void
    {
        if (!defined('XOOPS_URL')) {
            define('XOOPS_URL', 'http://localhost');
        }
    }

    public function testCustomfieldsUrlStripsSchemeHostAndTraversal()
    {
        $base = rtrim(constant('XOOPS_URL'), '/');
        $out1 = customfields_url('http://evil.tld/uploads/customfields/../file.txt');
        $this->assertSame($base . '/uploads/customfields/file.txt', $out1);

        $out2 = customfields_url('/uploads/customfields//image.png');
        $this->assertSame($base . '/uploads/customfields//image.png', $out2);

        $out3 = customfields_url('..//../uploads/customfields/../../x.jpg');
        // We don't normalize double slashes here; just assert base prefix and no '../'
        $this->assertStringStartsWith($base . '/', $out3);
        $this->assertStringNotContainsString('../', $out3);
        $this->assertStringContainsString('/uploads/customfields/', $out3);
        // Ensure the first case also stripped '../'
        $this->assertStringNotContainsString('../', $out1);
    }

    public function testLoggerFallbackWithoutPsrLogger()
    {
        // Ensure PSR interface is not defined; call logger methods to ensure no errors
        \XoopsModules\Customfields\Logger::setLogger(null);
        \XoopsModules\Customfields\Logger::info('info message');
        \XoopsModules\Customfields\Logger::warning('warn message');
        \XoopsModules\Customfields\Logger::error('error message');
        $this->assertTrue(true); // If no exceptions, fallback worked
    }

    // PSR-3 path is exercised indirectly when available in production; here we
    // only ensure the fallback path works without a PSR logger and that URL
    // helper behaves correctly.
}
