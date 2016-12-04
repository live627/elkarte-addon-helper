<?php

namespace live627\AddonHelper\Tests;

class DataMapperTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $mock = $this->getMockBuilder('live627\AddonHelper\DataMapper')
            ->setConstructorArgs(array(database()))
            ->getMock();
        $object = new \ReflectionClass($mock);
        $property = $object->getProperty('db');
        $property->setAccessible(true);
        $this->assertSame('1a', $property->getValue($mock)->quote('{int:one}a', ['one' => 1]));
    }
}
