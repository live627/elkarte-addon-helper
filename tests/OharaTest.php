<?php

namespace live627\AddonHelper\Tests;

use live627\AddonHelper\Ohara;

//~ require_once(__DIR__ . '/../vendor/elkarte/elkarte/tests/travis-ci/bootstrap.php');
require_once(__DIR__ . '/bootstrap.php');

$txt['months_title'] = 'Months';
$txt['MockOhara_months'] = array(1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

$modSettings['months_title'] = 'Months';
$modSettings['MockOhara']=1;
$modSettings['MockOhara_months'] = array(1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

$context['admin_menu_name']='MockOhara';
$context['MockOhara']=['tab_data'=>[]];

		$user_info = array(
			'is_admin' => true,
			'is_guest' => false,
		);

class MockOhara extends Ohara
{
    public $name = 'MockOhara';

    public $subActions = [
        'index' => ['actionIndex'],
        'edit' => ['actionEdit', 'g'],
    ];

    public function __construct()
    {
        parent::__construct(new \Simplex\Container);
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

    public function testName()
    {
        $actual = $this->loader->getName();
        $this->assertEquals('MockOhara', $actual);

        $this->loader->setName('trolololol');
        $actual = $this->loader->getName();
        $this->assertSame('trolololol', $actual);
    }

    public function testContainer()
    {
        $actual = $this->loader->getContainer();
        $this->assertInstanceOf('Interop\Container\ContainerInterface', $actual);

        $mock = $this->createMock('Interop\Container\ContainerInterface');
        $this->loader->setContainer($mock);
        $this->assertContains('Mock_ContainerInterface', get_class($mock));
        $actual = $this->loader->getContainer();
        $this->assertContains('Mock_ContainerInterface', get_class($actual));
        $this->assertInstanceOf('Interop\Container\ContainerInterface', $actual);
    }

    public function testDispatched()
    {
        global $context, $i;

        $this->loader->getContainer()->get('dispatcher')->dispatch($this->loader);
        $this->assertSame('i', $i);
        $this->assertSame('index', $context['sub_template']);
    }

    public function testDispatch()
    {
        global $context, $i;

        $request = $this->loader->getContainer()->get('request');
        $request->query->set('sa','edit');
        $request->query->set('area','mock');
        $this->loader->getContainer()->get('dispatcher')->dispatch($this->loader);

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

    public function testAllText()
    {
        $this->testText();

        $actual = array_filter($this->loader->getAllText());
        $this->assertTrue(is_array($actual));
        $this->assertCount(2,$actual);
        $actual = array_filter($this->o->getAllText());
        $this->assertTrue(is_array($actual));
        $this->assertCount(1,$actual);
    }

    public function testModSettings()
    {
        $actual = $this->loader->modSetting('months_title');
        $this->assertSame('Months', $actual);
        $actual = $this->loader->modSetting('months');
        $this->assertFalse($actual);

        $actual = $this->o->modSetting('months_title');
        $this->assertSame('Months', $actual);
        $actual = $this->o->modSetting('months');
        $this->assertFalse($actual);
    }

    public function testSettings()
    {
        $actual = $this->loader->setting('months_title');
        $this->assertFalse($actual);
        $actual = $this->loader->setting('months')[2];
        $this->assertSame('February', $actual);

        $actual = $this->o->setting('months_title');
        $this->assertFalse($actual);
        $actual = $this->o->setting('months')[2];
        $this->assertSame('February', $actual);
    }
}
