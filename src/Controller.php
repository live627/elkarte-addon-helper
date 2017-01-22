<?php

/**
 * @package   AddonHelper
 * @version   1.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2011-2016, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace live627\AddonHelper;

use DomainException;
use Interop\Container\ContainerInterface;

abstract class Controller extends \Action_Controller implements ControllerInterface
{
    use OharaTrait;

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
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

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
        $this->request = $this->getContainer()->get('requestStack')->getCurrentRequest();
        $this->nonce = new Nonce($this->getContainer()->get('requestStack'), md5(static::class));
    }

    /**
     * {@inheritdoc}
     */
    public function actionIndex()
    {
        throw new DomainException('Uhh... wut?');
    }

    /**
     * Noise.
     *
     * @access public
     * @abstracting \Action_Controller
     * @return void
     */
    public function action_index()
    {
    }

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
}
