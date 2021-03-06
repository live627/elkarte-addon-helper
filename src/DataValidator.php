<?php

/**
 * @package   AddonHelper
 * @version   1.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2011-2016, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace live627\AddonHelper;

class DataValidator extends \Data_Validator
{
    /**
     * regex ... Determine if the provided value is a regular expression
     *
     * Usage: '[key]' => 'regex'
     *
     * @param string $field
     * @param array  $input
     *
     * @return array
     */
    protected function _validate_regex($field, $input)
    {
        global $php_errormsg;

        if (!isset($input[$field])) {
            return [];
        }

        if (!$this->processRegex($input[$field])) {
            return [
                'error_msg' => $php_errormsg,
                'error' => 'validate_regex_syntax',
                'field' => $field,
            ];
        }
    }

    /**
     * Test a regex
     *
     * @param string $input The regex to test
     *
     * @return boolean Whether the regex is valid
     */
    private function processRegex($input)
    {
        // Turn off all error reporting
        $e = error_reporting(0);

        // Catch any errors the regex may produce.
        set_error_handler([$this, 'handleError']);

        $r = preg_match($input, null) !== false;
        restore_error_handler();
        error_reporting($e);

        return $r;
    }

    /**
     * Ignore errors
     *
     * @return boolean
     */
    public function handleError()
    {
        return false;
    }

    /**
     * htmlpurifier ... Run input through HTMLPurifier
     *
     * Usage: '[key]' => 'htmlpurifier'
     *
     * @param string $field The dirty HTML
     *
     * @return string The purified HTML
     */
    protected function _sanitation_htmlpurifier($field)
    {
        $config = \HTMLPurifier_Config::createDefault();
        $config->set('HTML.Doctype', 'XHTML 1.1');
        $def = $config->getHTMLDefinition(true);
        $def->addAttribute('a', 'target', 'Enum#_blank,_self,_target,_top');
        $purifier = new \HTMLPurifier($config);

        return $purifier->purify($field);
    }
}
