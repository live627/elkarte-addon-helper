<?php

namespace live627\AddonHelper\Tests;

use live627\AddonHelper\Nonce;

require_once(__DIR__.'/OharaTest.php');

class NonceTest extends \PHPUnit_Framework_TestCase
{
    protected $loader;

    /**
     * @var Symfony\Component\HttpFoundation\Request
     */
    private $request;

    protected function setUp()
    {
        $obj = new MockOhara;
        $this->loader = new Nonce($obj);
        $this->request = $obj->getContainer()->get('request');
    }

    public function testKey()
    {
        $actual = $this->loader->getKey();
        $this->assertContains('csrf_', $actual);

        $this->loader->setKey('trolololol');
        $actual = $this->loader->getKey();
        $this->assertSame('trolololol', $actual);
    }

    public function testTtl()
    {
        $actual = $this->loader->getTtl();
        $this->assertSame(900, $actual);

        $this->loader->setTtl(90);
        $actual = $this->loader->getTtl();
        $this->assertSame(90, $actual);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testTtlNotInt()
    {
        $this->loader->setTtl(true);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testTtlInConstructorNotInt()
    {
        new Nonce(new MockOhara, null, true);
    }

    /**
     * @expectedException live627\AddonHelper\Exceptions\MissingDataException
     */
    public function testMissingSessionToken()
    {
        $this->loader->check();
    }

    /**
     * @expectedException live627\AddonHelper\Exceptions\MissingDataException
     */
    public function testMissingFormToken()
    {
        $_SESSION[$this->loader->getKey()] = true;
        $this->loader->check();
    }

    /**
     * @expectedException live627\AddonHelper\Exceptions\BadCombinationException
     */
    public function testTokenOriginMismatch()
    {
        $this->generate();
        $this->request->headers->set('User-Agent', '');
        $this->request->request->set($this->loader->getKey(), true);
        $this->loader->check();
    }

    /**
     * @expectedException live627\AddonHelper\Exceptions\BadCombinationException
     */
    public function testTokenMismatch()
    {
        $this->generate();
        $this->request->request->set($this->loader->getKey(), true);
        $this->loader->check();
    }

    public function generate()
    {
        $this->request->headers->set('User-Agent', 'live627\AddonHelper');
        $hash = $this->loader->generate();
        $this->assertEquals($hash, $this->loader->getHash());
        $this->request->request->set($this->loader->getKey(), $hash);
    }

    /**
     * @expectedException RangeException
     */
    public function testExpiredToken()
    {
        $this->generate();
        $this->loader->setTtl(-90);
        $this->loader->check();
    }

    public function testSuccess()
    {
        $this->generate();
        $actual = $this->loader->check();
        $this->assertTrue($actual);
    }
}
