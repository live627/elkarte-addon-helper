<?php

namespace live627\AddonHelper\Tests;

use live627\AddonHelper\DataValidator;

class DataValidatorTest extends \PHPUnit_Framework_TestCase
{
    protected $validator = false;

    public function setUp()
    {
        $obj = $this->getMockBuilder('live627\AddonHelper\Ohara')
            ->setConstructorArgs(array(new \Simplex\Container))
            ->setMethods(['getServiceLayer'])
            ->getMock();
        $this->validator = $obj->getContainer()->get('datavalidator');
    }

    /**
     * @dataProvider isValidRegexProvider
     */
    public function testIsValidRegex($value, $expected)
    {
        $this->validator->validation_rules(['value' => 'regex']);
        $this->validate($value, $expected);
    }

    public function isValidRegexProvider()
    {
        return [
            ['42', false],
            ['/\d/', true],
        ];
    }

    /**
     * @dataProvider sanitizeHtmlProvider
     */
    public function testSanitizeHtml($value, $expected, $clean)
    {
        $this->validator->sanitation_rules(['value' => 'htmlpurifier']);
        $this->validate($value, $expected);
        $this->assertSame($this->validator->value, $clean);
    }

    public function sanitizeHtmlProvider()
    {
        return [
            ['<img src="javascript:evil();" onload="evil();" />', true, ''],
            [
                'c<STYLE>li {list-style-image:url("javascript:alert(\'XSS\')");}</STYLE><UL><LI>XSS',
                true,
                'c<ul><li>XSS</li></ul>',
            ],
        ];
    }

    public function validate($value, $expected)
    {
        $result = $this->validator->validate(['value' => $value]);
        $this->assertSame($expected, $result);
    }
}
