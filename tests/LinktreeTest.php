<?php

namespace live627\AddonHelper\Tests;

use live627\AddonHelper\ServiceProvider;

require_once(__DIR__.'/OharaTest.php');

class LinktreeTest extends \PHPUnit_Framework_TestCase
{
    public function getLinktree()
    {
        global $context;

        return $context['linktree'];
    }

    protected function setUp()
    {
        global $context;

        $context['linktree'] = [];

        $obj = $this->getMockBuilder('live627\AddonHelper\Ohara')
            ->setConstructorArgs([new \Simplex\Container])
            ->setMethods(['getServiceLayer'])
            ->getMock();
        $obj->getContainer()->get('linktree')->add(
            'Foo',
            '/vendor/foo',
            'before foo',
            'after foo'
        )->add(
            'BarDoom',
            '/vendor/foo.bardoom',
            'before bardoom',
            'after bardoom'
        )->execute();
    }

    public function testExistingLink()
    {
        $expect = [
            'name' => 'Foo',
            'url' => '/vendor/foo',
            'extra_before' => 'before foo',
            'extra_after' => 'after foo',
        ];
        $this->assertContains($expect, $this->getLinktree());

        $expect = [
            'name' => 'BarDoom',
            'url' => '/vendor/foo.bardoom',
            'extra_before' => 'before bardoom',
            'extra_after' => 'after bardoom',
        ];
        $this->assertContains($expect, $this->getLinktree());
    }

    public function testMissingLink()
    {
        $expect = [
            'name' => 'Baz Dib',
            'url' => '/vendor/baz.dib',
            'extra_before' => 'before baz',
            'extra_after' => 'after baz',
        ];
        $this->assertNotContains($expect, $this->getLinktree());
    }

    public function testLinkCount()
    {
        $this->assertCount(2, $this->getLinktree());
    }
}
