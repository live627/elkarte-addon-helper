<?php

/**
 * @package AddonHelper
 * @version 1.0
 * @author John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2011-2016, John Rayes
 * @license http://opensource.org/licenses/MIT MIT
 *
 *
 * This file contains code by:
 *
 *  - Ohara v1.0
 *  - Copyright (c) 2014, Jessica González
 *  - license http://www.mozilla.org/MPL/1.0/
 */

namespace live627\AddonHelper;

use Interop\Container\ContainerInterface;

class Ohara extends \Action_Controller
{
    /**
     * The main identifier for the class extending Ohara;
     * needs to be re-defined by each extending class.
     *
     * @access public
     * @var string
     */
    public $name = '';

    /**
     * Text array for holding your own text strings.
     *
     * @access protected
     * @var array
     */
    protected $text = [];

/**
 * URL to the script currently running
 *
 * @var string
 */
public $scriptUrl = '';

/**
 * URL to Elkarte
 *
 * @var string
 */
public $boardUrl = '';

    /**
     * Array of subactions.
     *
     * @access public
     * @var array
     */
    public $subActions = [
        'index' => ['actionIndex', 'admin_forum']
    ];

    /**
     * holds instance of Simplex.
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Nonce
     */
    protected $nonce;

    /**
     * Sets many properties replacing SMF's global vars.
     *
     * @access public
     */
    public function __construct(ContainerInterface $container)
    {
        global $scripturl, $boardurl;

        $this->scriptUrl = $scripturl;
        $this->boardUrl = $boardurl;
        $this->container = $container;
        $this->container->register(new ServiceProvider);
        $this->nonce = new Nonce($this, md5(static::class));
    }

    /**
     * Default action according to {@link $subActions}.
     *
     * @usedby Dispatcher
     * @return string
     */
    public function actionIndex()
    {
        \Errors::instance()->fatal_lang_error('no_access', false);
    }

    /**
     * Noise.
     *
     * @access public
     * @abstracting \Action_Controller
     * @return void
     */
    public function action_index() {}

    /**
     * Getter for {@link $name} property.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $permission
     * @access public
     * @return bool
     */
    public function allowedTo($permission)
    {
        return allowedTo($permission);
    }

    /**
     * Getter for {@link $text} property.
     *
     * @access public
     * @param string $var The name of the $txt key you want to retrieve
     * @return bool|string
     */
    public function text($var)
    {
        global $txt;

        // This should be extended by somebody else...
        if (empty($this->name) || empty($var))
            return false;

        if (!isset($this->text[$var]))
            $this->setText($var);

        return $this->text[$var];
    }

    /**
     * Loads the extending class language file and sets a new key in {@link $text}
     * Ohara automatically adds the value of {@link $var} plus an underscore
     * to match the exact $txt key when fetching the var
     *
     * @access private
     * @param string $var The name of the $txt key you want to retrieve
     * @return boolean
     */
    private function setText($var)
    {
        global $txt;

        // Load the mod's language file.
        loadLanguage($this->name);

            $this->text[$var] = false;
        if (!empty($txt[$this->name . '_' . $var])) {
            $this->text[$var] = $txt[$this->name . '_' . $var];
        } elseif (!empty($txt[$this->name . $var])) {
            $this->text[$var] = $txt[$this->name . $var];
        } elseif (!empty($txt[$var])) {
            $this->text[$var] = $txt[$var];
        }
        return $this->text[$var] !== false;
    }

    /**
     * Getter for {@link $text}
     * @access public
     * @return array
     */
    public function getAllText()
    {
        return $this->text;
    }

    /**
     * Checks for a $modSetting key and its state
     * returns true if the $modSetting exists and its not empty
     * regardless of what its value is
     *
     * @param string $var The name of the $modSetting key you want to retrieve
     * @access public
     * @return boolean
     */
    public function enable($var)
    {
        global $modSettings;

        if (empty($var))
            return false;

            return !empty($modSettings[$this->name .'_'. $var]);
    }

    /**
     * Returns the actual value of the selected $modSetting
     * uses Ohara::enable() to determinate if the var exists
     *
     * @param string $var The name of the $modSetting key you want to retrieve
     * @access public
     * @return mixed|boolean
     */
    public function setting($var)
    {
        global $modSettings;

        // This should be extended by somebody else...
        if (empty($this->name) || empty($var))
            return false;

        if (true === $this->enable($var))
            return $modSettings[$this->name .'_'. $var];

        else
            return false;
    }

    /**
     * Returns the actual value of a generic $modSetting var
     * useful to check external $modSettings vars
     *
     * @param string $var The name of the $modSetting key you want to retrieve
     * @access public
     * @return mixed|boolean
     */
    public function modSetting($var)
    {
        global $modSettings;

        // This should be extended by somebody else...
        if (empty($this->name))
            return false;

        if (empty($var))
            return false;

        if (isset($modSettings[$var]))
            return $modSettings[$var];

        else
            return false;
    }
}
