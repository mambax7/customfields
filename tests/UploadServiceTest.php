<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../class/UploadService.php';

class UploadServiceTest extends TestCase
{
    private $tmpDir;

    protected function setUp(): void
    {
        $this->tmpDir = rtrim(sys_get_temp_dir(), '/\\') . DIRECTORY_SEPARATOR . 'cf_ut_' . uniqid('', true);
        if (!@mkdir($concurrentDirectory = $this->tmpDir, 0755, true) && !is_dir($concurrentDirectory)) {
            $this->fail('Failed to create temporary directory for tests: ' . $this->tmpDir);
        }
    }

    protected function tearDown(): void
    {
        // Cleanup any files created in tmp dir
        if (is_dir($this->tmpDir)) {
            foreach (glob($this->tmpDir . DIRECTORY_SEPARATOR . '*') as $f) {
                @unlink($f);
            }
            @rmdir($this->tmpDir);
        }
        // Reset FILES entry
        unset($_FILES['uf']);
    }

    private function makeTempFile($content = 'x')
    {
        $tmp = tempnam(sys_get_temp_dir(), 'cf_ts_');
        file_put_contents($tmp, $content);
        return $tmp;
    }

    public function testRejectsWhenNoFile()
    {
        $svc = new class($this->tmpDir) extends \XoopsModules\Customfields\UploadService {
            public function __construct($dir){ parent::__construct($dir); }
            protected function detectMime($tmpPath){ return 'image/jpeg'; }
            protected function move($tmp,$dest){ return copy($tmp,$dest); }
        };

        $out = $svc->handle('uf', 'image');
        $this->assertSame('', $out);
    }

    public function testRejectsBySizeAndExtension()
    {
        $_FILES['uf'] = [
            'error' => UPLOAD_ERR_OK,
            'size'  => 10 * 1024 * 1024, // too big (default 5MB)
            'name'  => 'pic.jpg',
            'tmp_name' => $this->makeTempFile('aaaa'),
        ];
        $svc = new class($this->tmpDir) extends \XoopsModules\Customfields\UploadService {
            public function __construct($dir){ parent::__construct($dir); }
            protected function detectMime($tmpPath){ return 'image/jpeg'; }
            protected function move($tmp,$dest){ return copy($tmp,$dest); }
        };
        // Size too large
        $this->assertSame('', $svc->handle('uf', 'image'));

        // Now allow small size but bad extension for file type
        $_FILES['uf']['size'] = 10; // OK size
        $_FILES['uf']['name'] = 'evil.php';
        $this->assertSame('', $svc->handle('uf', 'file'));
    }

    public function testAcceptsImageWithAllowedMimeAndExtension()
    {
        $_FILES['uf'] = [
            'error' => UPLOAD_ERR_OK,
            'size'  => 1234,
            'name'  => 'safe.png',
            'tmp_name' => $this->makeTempFile(random_bytes(8)),
        ];
        $svc = new class($this->tmpDir) extends \XoopsModules\Customfields\UploadService {
            public function __construct($dir){ parent::__construct($dir); }
            protected function detectMime($tmpPath){ return 'image/png'; }
            protected function move($tmp,$dest){ return copy($tmp,$dest); }
        };
        $out = $svc->handle('uf', 'image');
        $this->assertNotSame('', $out);
        $this->assertStringStartsWith('uploads/customfields/', $out);
        // File should exist in tmp upload dir with generated name
        $parts = explode('/', $out);
        $filename = end($parts);
        $this->assertFileExists($this->tmpDir . DIRECTORY_SEPARATOR . $filename);
        $this->assertStringEndsWith('.png', $filename);
        $this->assertMatchesRegularExpression('/^[a-f0-9]+\.[a-z0-9]+$/i', preg_replace('/_\d+\./', '.', $filename));
    }

    public function testRejectsWhenMimeDoesNotMatchAllowed()
    {
        $_FILES['uf'] = [
            'error' => UPLOAD_ERR_OK,
            'size'  => 10,
            'name'  => 'safe.jpg',
            'tmp_name' => $this->makeTempFile('zzz'),
        ];
        $svc = new class($this->tmpDir) extends \XoopsModules\Customfields\UploadService {
            public function __construct($dir){ parent::__construct($dir); }
            protected function detectMime($tmpPath){ return 'application/x-msdownload'; }
            protected function move($tmp,$dest){ return copy($tmp,$dest); }
        };
        $out = $svc->handle('uf', 'image');
        $this->assertSame('', $out);
    }
}
