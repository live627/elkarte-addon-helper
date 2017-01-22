<?php

/**
 * @package   AddonHelper
 * @version   1.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2011-2016, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace live627\AddonHelper;

interface ServiceLayerInterface
{
    /**
     * @return DataMapper
     */
    public function getDataMap();

    /**
     * @param EntityInterface $object
     *
     * @return EntityInterface
     */
    public function makeNew(EntityInterface $object);

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return EntityInterface
     */
    public function makeFromPost(\Symfony\Component\HttpFoundation\Request $request);

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param DataValidator                             $validator
     *
     * @return array
     */
    public function validatePostData(\Symfony\Component\HttpFoundation\Request $request, DataValidator $validator);

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return EntityInterface
     */
    public function makeFromRequest(\Symfony\Component\HttpFoundation\Request $request);

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    public function checkAccess(\Symfony\Component\HttpFoundation\Request $request);
}
