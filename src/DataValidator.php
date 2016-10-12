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
     * @param mixed[]|null $validation_parameters array or null
     */
    protected function _validate_regex($field, $input, $validation_parameters = null)
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
    * @param integer $code
    * @param string $description
    * @param string $file
    * @param interger $line
    * @param mixed $context
    * @return boolean
    */
    public function handleError($code, $description, $file = null, $line = null, $context = null)
    {
        return false;
    }

    /**
     * htmlpurifier ... Determine if the provided value is a regular expressions
     *
     * Usage: '[key]' => 'htmlpurifier'
     *
     * @param string $field
     * @param mixed[]|null $validation_parameters array or null
     */
    protected function _sanitation_htmlpurifier($field, $validation_parameters = null)
    {
        require_once __DIR__ . '/HTMLPurifier.standalone.php';
        $config = \HTMLPurifier_Config::createDefault();
        $config->set('HTML.Doctype', 'XHTML 1.1');
        $def = $config->getHTMLDefinition(true);
        $def->addAttribute('a', 'target', 'Enum#_blank,_self,_target,_top');
        $purifier = new \HTMLPurifier($config);

        return $purifier->purify($field);
    }
}
