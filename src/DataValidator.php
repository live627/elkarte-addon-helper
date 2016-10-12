<?php

/**
 * @package AddonHelper
 * @version 1.0
 * @author John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2011-2016, John Rayes
 * @license http://opensource.org/licenses/MIT MIT
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
     * @param mixed[] $input
     */
    protected function _validate_regex($field, $input)
    {
        global $php_errormsg;

        if (!isset($input[$field]))
            return;

        // Turn off all error reporting
        $e = error_reporting(0);

        // Catch any errors the regex may produce.
        set_error_handler(array($this, 'handleError'));

        $r=preg_match($input[$field], null) === false;
            restore_error_handler();
            error_reporting($e);
        if ($r) {
            return array(
                'error_msg' => $php_errormsg,
                'error' => 'validate_regex_syntax',
                'field' => $field,
            );
        }
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
     * htmlpurifier ... Determine if the provided value is a regular expressions
     *
     * Usage: '[key]' => 'htmlpurifier'
     *
     * @param string $field
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
