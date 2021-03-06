<?php

namespace live627\AddonHelper\Tests;

class MockBitwiseFlag extends \live627\AddonHelper\BitwiseFlag
{
    const FLAG_REGISTERED = 0x1;
    const FLAG_ACTIVE = 0x2;
    const FLAG_MEMBER = 0x4;
    const FLAG_ADMIN = 0x8;

    public function __construct()
    {
        parent::__construct();
    }

    public function isRegistered()
    {
        return $this->isFlagSet(self::FLAG_REGISTERED);
    }

    public function isActive()
    {
        return $this->isFlagSet(self::FLAG_ACTIVE);
    }

    public function isMember()
    {
        return $this->isFlagSet(self::FLAG_MEMBER);
    }

    public function isAdmin()
    {
        return $this->isFlagSet(self::FLAG_ADMIN);
    }

    public function setRegistered($value)
    {
        $this->setFlag(self::FLAG_REGISTERED, $value);
    }

    public function setActive($value)
    {
        $this->setFlag(self::FLAG_ACTIVE, $value);
    }

    public function setMember($value)
    {
        $this->setFlag(self::FLAG_MEMBER, $value);
    }

    public function setAdmin($value)
    {
        $this->setFlag(self::FLAG_ADMIN, $value);
    }
}

class BitwiseFlagTest extends \PHPUnit_Framework_TestCase
{
    protected $loader;

    protected function setUp()
    {
        $this->loader = new MockBitwiseFlag;

        $this->loader->setRegistered(true);
        $this->loader->setActive(true);
        $this->loader->setMember(true);
    }

    public function testInitialSet()
    {
        $actual = $this->loader->isRegistered();
        $this->assertTrue($actual);

        $actual = $this->loader->isActive();
        $this->assertTrue($actual);

        $actual = $this->loader->isMember();
        $this->assertTrue($actual);

        $actual = $this->loader->isAdmin();
        $this->assertFalse($actual);
    }

    public function testRawBits()
    {
        $this->assertEquals(0x7, (string) $this->loader);
    }

    public function testChangeFlag()
    {
        $this->loader->setActive(false);
        $actual = $this->loader->isActive();
        $this->assertFalse($actual);

        $actual = $this->loader->isRegistered();
        $this->assertTrue($actual);
    }

    public function testRawBitsChanged()
    {
        $this->loader->setActive(false);
        $this->assertEquals(0x5, (string) $this->loader);
    }
}
