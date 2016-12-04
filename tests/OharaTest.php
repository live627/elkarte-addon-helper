<?php

namespace live627\AddonHelper\Tests;

use live627\AddonHelper\Ohara;

require_once(__DIR__.'/bootstrap.php');

$txt['months_title'] = 'Months';
$txt['MockOharaedit_desc'] = 'edit description';
$txt['MockOhara_months'] = [
    1 => 'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December',
];

$modSettings['months_title'] = 'Months';
$modSettings['MockOhara'] = 1;
$modSettings['MockOhara_months'] = [
    1 => 'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December',
];

$context['admin_menu_name'] = 'MockOhara';
$context['MockOhara'] = ['tab_data' => []];

$user_info += [
    'is_admin' => true,
    'is_guest' => false,
];

class OharaTest extends \PHPUnit_Framework_TestCase
{
    protected $loader;
    protected $o;

    protected function setUp()
    {
        if (is_callable([$this, 'createMock'])) {
            $mock = $this->createMock('live627\AddonHelper\ServiceLayerInterface');
        } else {
            $mock = $this->getMock('live627\AddonHelper\ServiceLayerInterface');
        }
        $mock->method('checkAccess')
             ->will($this->returnValueMap(['index',false,'edit',true]));

        $this->loader = $this->getMockBuilder('live627\AddonHelper\Ohara')
            ->setConstructorArgs(array(new \Simplex\Container))
            ->setMethods(array('action_index', 'actionEdit', 'getServiceLayer'))
            ->getMock();
        $this->loader->name = 'MockOhara';
        $this->loader->expects($this->never())
            ->method('action_index');
        $this->loader->method('getServiceLayer')
             ->willReturn($mock);

        $this->o = $this->getMockBuilder('Suki\Ohara')
            ->setMethods(null)
            ->getMock();
        $this->o->name = 'MockOhara';
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

        if (is_callable([$this, 'createMock'])) {
            $mock = $this->createMock('Interop\Container\ContainerInterface');
        } else {
            $mock = $this->getMock('Interop\Container\ContainerInterface');
        }
        $this->loader->setContainer($mock);
        $this->assertContains('Mock_ContainerInterface', get_class($mock));
        $actual = $this->loader->getContainer();
        $this->assertContains('Mock_ContainerInterface', get_class($actual));
        $this->assertInstanceOf('Interop\Container\ContainerInterface', $actual);
    }

    /**
     * @expectedException Elk_Exception
     */
    public function testDispatched()
    {
        global $context;

        $this->loader->getContainer()->get('dispatcher')->dispatch($this->loader);
        $this->assertSame('index', $context['sub_template']);
    }

    public function testDispatch()
    {
        global $context;

        $this->loader->expects($this->once())
            ->method('actionEdit')
            ->with();

        $context['max_menu_id'] = 'MockOhara';
        $request = $this->loader->getContainer()->get('requestStack')->getCurrentRequest();
        $request->query->set('sa', 'edit');
        $request->query->set('area', 'mock');
        $this->loader->getContainer()->get('dispatcher')->dispatch($this->loader);

        $this->assertSame('mock_edit', $context['sub_template']);
        $this->assertSame('edit description', $context['menu_data_MockOhara']['tab_data']['edit']['description']);
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
        $this->assertCount(2, $actual);
        $actual = array_filter($this->o->getAllText());
        $this->assertTrue(is_array($actual));
        $this->assertCount(1, $actual);
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
