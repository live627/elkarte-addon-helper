<?php

namespace live627\AddonHelper\Tests;

use live627\AddonHelper\Menu;
use live627\AddonHelper\MenuSection;
use live627\AddonHelper\MenuArea;
use live627\AddonHelper\MenuSubsection;

require_once(__DIR__ . '/bootstrap.php');
require_once(__DIR__ . '/OharaTest.php');

class MenuTest extends \PHPUnit_Framework_TestCase
{
    private $menu;

    protected function setUp()
    {
        global $context, $user_info;

        $this->menu = (new Menu(new MockOhara))->addSection('section1', MenuSection::buildFromArray(array(
                'title' => 'One',
                'permission' => array('admin_forum'),
                'areas' => array(
                    'area1' => MenuArea::buildFromArray(array(
                        'label' => 'Area1 Label',
                        'function' => function () {},
                    )),
                ),
            )))->addSection('section2', MenuSection::buildFromArray(array(
                'title' => 'Two',
                'permission' => array('admin_forum'),
                'areas' => array(
                    'area2' => MenuArea::buildFromArray(array(
                        'label' => 'Area2 Label',
                        'function' => function () {},
                        'custom_url' => 'custom_url',
                        'hidden' => true,
                    )),
                    'area3' => MenuArea::buildFromArray(array(
                        'permission' => 'area3 permission',
                        'label' => 'Area3 Label',
                        'function' => function () {},
                        'subsections' => array(
                            'sub1' => new MenuSubsection('Sub One', array('admin_forum')),
                            'sub2' => new MenuSubsection('Sub Two', array('admin_forum'), false, false)),
                    )),
                    'area4' => MenuArea::buildFromArray(array(
                        'label' => 'Area4 Label',
                        'function' => function () {},
                        'enabled' => false,
                    )),
                ),
            )))->addSection('section3', MenuSection::buildFromArray(array(
                'title' => 'Three',
                'permission' => array('admin_forum'),
                'enabled' => false,
                'areas' => array(
                    'area5' => MenuArea::buildFromArray(array(
                        'label' => 'Area5 Label',
                        'function' => function () {},
                    )),
                ),
            )
        ));
        $this->menu->addOption('extra_url_parameters', array('extra' => 'param'));

        $user_info['is_admin'] = true;
        $context['right_to_left'] = false;
        $context['session_var'] = 'abcde';
        $context['session_id'] = '123456789';
        $context['current_action'] = 'section1';
        $context['current_subaction'] = '';
        $context['linktree'] = [];
    }

    public function testMenu()
    {
        global $context;

        $this->menu->execute();

        // These are long-ass arrays, y'all!
        $result = $context['menu_data_' . $context['max_menu_id']];

        $this->assertArrayNotHasKey('section3', $result['sections']);
        $this->assertCount(2, $result['sections']);
        $this->assertCount(1, $result['sections']['section2']['areas']);
        $this->assertCount(2, $result['sections']['section2']['areas']['area3']['subsections']);

        $this->assertTrue($result['sections']['section2']['areas']['area3']['subsections']['sub2']['disabled']);
        $this->assertTrue($result['sections']['section2']['areas']['area3']['subsections']['sub1']['is_first']);
        $this->assertArrayNotHasKey('area2', $result['sections']['section2']['areas']);
    }

    public function testOptions()
    {
        global $context;

        $this->menu->execute();

        $result = $context['menu_data_' . $context['max_menu_id']];
        $this->assertStringStartsWith(';extra=param', $result['extra_parameters']);
    }

    /**
     * @expectedException Elk_Exception
     */
    public function testFail()
    {
        (new Menu(new MockOhara))->execute();
    }

    public function testLinktree()
    {
        global $context;

        $this->menu->addOption('action', 'section2');
        $this->menu->addOption('current_area', 'area3');
        $this->menu->execute();

        $this->assertContains('sub1', $context['linktree'][2]['url']);
        $this->assertCount(3, $context['linktree']);
    }
}
