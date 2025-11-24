<?php

use PHPUnit\Framework\TestCase;

class UrlHelperTest extends TestCase
{
public function testStripsSchemeAndHost(): void
{
$url = customfields_url('http://example.test/uploads/customfields/file.jpg');
$this->assertSame('http://localhost/uploads/customfields/file.jpg', $url);
}

public function testBlocksScriptSchemes(): void
{
$url = customfields_url('javascript:alert(1)');
$this->assertSame('http://localhost/', $url);
}

public function testRemovesTraversalAndBackslashes(): void
{
$url = customfields_url('../etc/../uploads\\..\\secret.php');
$this->assertSame('http://localhost/secret.php', $url);
}

public function testNormalisesDuplicateSlashes(): void
{
$url = customfields_url('/uploads//customfields///file.jpg');
$this->assertSame('http://localhost/uploads/customfields/file.jpg', $url);
}
}
