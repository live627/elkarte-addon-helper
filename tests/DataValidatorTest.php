<?php

namespace live627\AddonHelper\Tests;

use live627\AddonHelper\DataValidator;

class DataValidatorTest extends \PHPUnit_Framework_TestCase
{
    protected $validator = false;

    public function setUp()
    {
        $this->validator = new DataValidator;
    }

    /**
     * @dataProvider isValidRegexProvider
     */
    public function testIsValidRegex($value, $expected)
    {
        $this->validator->validation_rules(array('value' => 'regex'));
        $this->validate($value, $expected);
    }

    public function isValidRegexProvider()
    {
        return array(
            array('42', false),
            array('/\d/', true)
        );
    }

    public function validate($value, $expected)
    {
        $result = $this->validator->validate(array('value' => $value));
        $this->assertSame($expected, $result);
    }
}
