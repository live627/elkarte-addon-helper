<?php

namespace live627\AddonHelper\Tests;

use live627\AddonHelper\Ohara;

//~ require_once(__DIR__ . '/../vendor/elkarte/elkarte/tests/travis-ci/bootstrap.php');
require_once(__DIR__ . '/bootstrap.php');

$txt['months_title'] = 'Months';
$txt['MockOhara_months'] = array(1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

$context['admin_menu_name']='MockOhara';
$context['MockOhara']=['tab_data'=>[]];

class MockOhara extends Ohara
{
    public $name = 'MockOhara';

    public $subActions = [
        'index' => ['actionIndex', ''],
        'edit' => ['actionEdit', ''],
    ];

    public function __construct()
    {
        parent::__construct(new \Simplex\Container);
        $this->container->get('dispatcher')->dispatch($this);
    }

    public function actionIndex()
    {
        global $i;

        $i = 'i';
    }

    public function actionEdit()
    {
        global $i;

        $i = 'e';
    }
}

class MockSukiOhara extends \Suki\Ohara
{
    public $name = 'MockOhara';

    public function __construct()
    {
        $this->setRegistry();
    }
}

class OharaTest extends \PHPUnit_Framework_TestCase
{
    protected $loader;
    protected $o;

    protected function setUp()
    {
        $this->loader = new MockOhara;
        $this->o = new MockSukiOhara;
    }

    public function testDispatched()
    {
        global $context, $i;

        $this->assertSame('i', $i);
        $this->assertSame('index', $context['sub_template']);
    }

    public function testDispatch()
    {
        global $context, $i;

		$obj=new MockOhara;
        $request = $obj->getContainer()->get('request');
        $request->query->set('sa','edit');
        $request->query->set('area','mock');
        $obj->getContainer()->get('dispatcher')->dispatch($obj);

        $this->assertSame('e', $i);
        $this->assertSame('mock_edit', $context['sub_template']);
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
