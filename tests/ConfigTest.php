<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../class/Config.php';

/**
 * @runTestsInSeparateProcesses
 */
class ConfigTest extends TestCase
{
    public function testDefaultsWhenNoConstantsDefined()
    {
        $this->assertSame(5 * 1024 * 1024, \XoopsModules\Customfields\Config::getMaxUploadSize());

        $imgExt = \XoopsModules\Customfields\Config::getAllowedExtensions('image');
        $fileExt = \XoopsModules\Customfields\Config::getAllowedExtensions('file');
        $this->assertContains('jpg', $imgExt);
        $this->assertContains('pdf', $fileExt);

        $imgMime = \XoopsModules\Customfields\Config::getAllowedMimes('image');
        $fileMime = \XoopsModules\Customfields\Config::getAllowedMimes('file');
        $this->assertContains('image/jpeg', $imgMime);
        $this->assertContains('application/pdf', $fileMime);

        $this->assertSame('d.m.Y', \XoopsModules\Customfields\Config::getDisplayDateFormat());
    }

    public function testOverridesWhenConstantsDefined()
    {
        if (!defined('CUSTOMFIELDS_MAX_UPLOAD_SIZE')) {
            define('CUSTOMFIELDS_MAX_UPLOAD_SIZE', 123);
        }
        if (!defined('CUSTOMFIELDS_ALLOWED_IMAGE_EXT')) {
            define('CUSTOMFIELDS_ALLOWED_IMAGE_EXT', ['bmp']);
        }
        if (!defined('CUSTOMFIELDS_ALLOWED_FILE_EXT')) {
            define('CUSTOMFIELDS_ALLOWED_FILE_EXT', ['abc']);
        }
        if (!defined('CUSTOMFIELDS_ALLOWED_IMAGE_MIME')) {
            define('CUSTOMFIELDS_ALLOWED_IMAGE_MIME', ['image/bmp']);
        }
        if (!defined('CUSTOMFIELDS_ALLOWED_FILE_MIME')) {
            define('CUSTOMFIELDS_ALLOWED_FILE_MIME', ['application/x-abc']);
        }
        if (!defined('CUSTOMFIELDS_DISPLAY_DATE_FORMAT')) {
            define('CUSTOMFIELDS_DISPLAY_DATE_FORMAT', 'Y/m/d');
        }

        $this->assertSame(123, \XoopsModules\Customfields\Config::getMaxUploadSize());
        $this->assertSame(['bmp'], \XoopsModules\Customfields\Config::getAllowedExtensions('image'));
        $this->assertSame(['abc'], \XoopsModules\Customfields\Config::getAllowedExtensions('file'));
        $this->assertSame(['image/bmp'], \XoopsModules\Customfields\Config::getAllowedMimes('image'));
        $this->assertSame(['application/x-abc'], \XoopsModules\Customfields\Config::getAllowedMimes('file'));
        $this->assertSame('Y/m/d', \XoopsModules\Customfields\Config::getDisplayDateFormat());
    }
}
