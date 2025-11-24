<?php

use PHPUnit\Framework\TestCase;

class DummyUser
{
/** @var array */
private $groups;

public function __construct(array $groups)
{
$this->groups = $groups;
}

public function getGroups()
{
return $this->groups;
}
}

class AdminAccessTest extends TestCase
{
public function testReturnsFalseWhenUserIsMissing(): void
{
$this->assertFalse(customfields_isAdminUser(null));
$this->assertFalse(customfields_isAdminUser(new stdClass()));
}

public function testDetectsAdminGroup(): void
{
$user = new DummyUser([XOOPS_GROUP_ADMIN]);
$this->assertTrue(customfields_isAdminUser($user));
}

public function testRejectsNonAdminGroups(): void
{
$user = new DummyUser([2, 3]);
$this->assertFalse(customfields_isAdminUser($user));
}
}
