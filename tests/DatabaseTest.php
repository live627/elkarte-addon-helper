<?php

namespace live627\AddonHelper\Tests;

use live627\AddonHelper\Database;

class DatabaseTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        Database::getInstance();
        $this->assertSame('1a', Database::quote('{int:one}a',['one'=>1]));
    }
}
