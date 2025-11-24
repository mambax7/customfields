<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../class/Validator.php';

class ValidatorTest extends TestCase
{
    private $v;

    protected function setUp(): void
    {
        $this->v = new \XoopsModules\Customfields\Validator();
    }

    public function testValidateTextTruncatesAndRegex()
    {
        $out = $this->v->validateText('  abcdef  ', 3);
        $this->assertSame('abc', $out);

        // Regex mismatch returns empty
        $out2 = $this->v->validateText('xyz', 255, '/^abc/');
        $this->assertSame('', $out2);
    }

    public function testValidateNumberIntegerAndFloat()
    {
        $i = $this->v->validateNumber('12', null, null, true);
        $this->assertSame(12, $i);

        $f = $this->v->validateNumber('3.14', 0.0, 10.0, false);
        $this->assertEquals(3.14, $f);

        $invalid = $this->v->validateNumber('abc', 0, 100, true);
        $this->assertNull($invalid);
    }

    public function testValidateDate()
    {
        $d = $this->v->validateDate('2025-02-01');
        $this->assertSame('2025-02-01', $d);

        $bad = $this->v->validateDate('2025-13-99');
        $this->assertNull($bad);
    }

    public function testValidateOptionAndCheckbox()
    {
        $opt = $this->v->validateOption('b', ['a','b','c']);
        $this->assertSame('b', $opt);

        $optBad = $this->v->validateOption('z', ['a','b','c']);
        $this->assertSame('', $optBad);

        $set = $this->v->normalizeCheckbox('1, 2, 9, 1', ['1','2','3']);
        $this->assertSame('1,2', $set);
    }

    public function testSanitizeEditor()
    {
        $html = '<p>Hello <script>alert(1)</script><strong>World</strong></p>';
        $safe = $this->v->sanitizeEditor($html);
        $this->assertStringContainsString('<p>Hello ', $safe);
        $this->assertStringContainsString('<strong>World</strong>', $safe);
        $this->assertStringNotContainsString('<script>', $safe);
    }
}
