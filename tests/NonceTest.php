<?php

namespace live627\AddonHelper\Tests;

use live627\AddonHelper\ServiceProvider;

require_once(__DIR__ . '/OharaTest.php');

class MockNonce extends \live627\AddonHelper\Nonce
{
    public function checkAttack()
    {
        try
        {
            $this->check();
            $result = 'CSRF check passed';
        }
        catch (\Exception $e)
        {
            // CSRF attack detected
            $result = $e->getMessage();
        }
        return $result;
    }
}

class NonceTest extends \PHPUnit_Framework_TestCase
{
    protected $loader;

    /**
     * @var Symfony\Component\HttpFoundation\Request
     */
    private $request;

    protected function setUp()
    {
		$obj=new MockOhara;
        $this->loader = new MockNonce($obj);
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
     * @expectedException \InvalidArgumentException
     */
    public function testTtlNotInt()
    {
        $this->loader->setTtl(true);
    }

    public function testMissingSessionToken()
    {
        $actual = $this->loader->checkAttack();
        $this->assertSame('Missing CSRF session token', $actual);
    }

    public function testMissingFormToken()
    {
        $_SESSION[$this->loader->getKey()] = true;
        $actual = $this->loader->checkAttack();
        $this->assertSame('Missing CSRF form token', $actual);
    }

    public function testTokenOriginMismatch()
    {
        $this->generate();
        $this->request->headers->set('User-Agent', '');
        $this->request->request->set($this->loader->getKey(), true);
        $actual = $this->loader->checkAttack();
        $this->assertSame('Form origin does not match token origin.', $actual);
    }

    public function testTokenMismatch()
    {
        $this->generate();
        $this->request->request->set($this->loader->getKey(), true);
        $actual = $this->loader->checkAttack();
        $this->assertSame('Invalid CSRF token', $actual);
    }

    public function generate()
    {
        $this->request->headers->set('User-Agent', 'live627\AddonHelper');
        $hash = $this->loader->generate();
        $this->assertEquals($hash, $this->loader->getHash());
        $this->request->request->set($this->loader->getKey(), $hash);
    }

    public function testExpiredToken()
    {
        $this->generate();
        $this->loader->setTtl(-90);
        $actual = $this->loader->checkAttack();
        $this->assertSame('CSRF token has expired.', $actual);
        $this->loader->setTtl(90);
    }

    public function testSuccess()
    {
        $this->generate();
        $actual = $this->loader->check();
        $this->assertTrue($actual);
    }
}
