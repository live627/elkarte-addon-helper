<?php

//~ require_once(__DIR__ . '/../vendor/elkarte/elkarte/tests/travis-ci/bootstrap.php');
require_once(__DIR__ . '/bootstrap.php');

$txt['months_title'] = 'Months';
$txt['MockOhara_months'] = array(1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

class MockOhara extends live627\AddonHelper\Ohara
{
    public $name = __CLASS__;

    public $subActions = [
        'edit' => ['actionEdit', ''],
    ];

    public function __construct($util)
    {
        $this->util = $util;
        parent::__construct(new \Simplex\Container);
        $this->container->get('dispatcher')->dispatch($this);
    }

    public function actionEdit()
    {
    }
}

class MockSukiOhara extends Suki\Ohara
{
    public $name = 'MockOhara';

    public function __construct()
    {
        $this->setRegistry();
    }
}

class OharaTest extends PHPUnit_Framework_TestCase
{
    protected $loader;
    protected $o;

    protected function setUp()
    {
        $this->loader = new MockOhara;
        $this->o = new MockSukiOhara;
    }

    public function testText()
    {
        $actual = $this->loader->text('months')[2];
        $this->assertSame('February', $actual);
        $actual = $this->o->text('months')[2];
        $this->assertSame('February', $actual);
        $actual = $this->loader->text('months_title');
        $this->assertSame('Months', $actual);
        $actual = $this->o->text('months_title');
        $this->assertFalse($actual);
        $actual = $this->loader->text('days_title');
        $this->assertFalse($actual);
        $actual = $this->o->text('days_title');
        $this->assertFalse($actual);
    }
}
