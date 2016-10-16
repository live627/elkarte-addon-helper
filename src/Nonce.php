<?php

/**
 * @package AddonHelper
 * @version 1.0
 * @author John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2011-2016, John Rayes
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace live627\AddonHelper;

class Nonce
{
    /**
     * @var string
     */
    private $hash;

    /**
     * @var string
     */
    private $key;

    /**
     * @var int
     */
    private $ttl = 900;

    /**
     * @var Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * @param string $key The token key.
     * @param int $ttl (Facultative) Makes the token expire after $this->ttl seconds. (null = never)
     */
    public function __construct(Ohara $obj, $key = null, $ttl = 900)
    {
        if (!isset($key)) {
            $this->key = 'csrf_' . bin2hex(random_bytes(8));
        }
        if (!is_int($ttl)) {
            throw new \InvalidArgumentException('Integer expected: $ttl');
        }
        $this->ttl = $ttl;
        $this->request = $obj->getContainer()->get('request');
    }
    /**
     * Check CSRF tokens match between session and POST.
     * Make sure you have generated a token in the form before checking it.
     *
     * @access public
     * @throws Exceptions\MissingDataException if token not in session
     * @throws Exceptions\MissingDataException if token not POSTed
     * @return bool Returns false if a CSRF attack is detected, true otherwise.
     */
    public function check()
    {
        $this->hash = Session::get($this->key);
        if ($this->hash === false) {
            throw new Exceptions\MissingDataException('Missing CSRF session token');
        }

        if (!$this->request->request->has($this->key)) {
            throw new Exceptions\MissingDataException('Missing CSRF form token');
        }
        $this->checkOrigin();
        $this->checkExpiration();

        // Free up session token for one-time CSRF token usage.
        Session::pull($this->key);

        return true;
    }

    /**
     * Check token origin (client IP and user agent) match with what
     * the client (usually a web browser) tells us.
     *
     * @access private
     * @throws Exceptions\BadCombinationException if session token matches form token
     * @throws Exceptions\BadCombinationException if client data doesn't match
     */
    private function checkOrigin()
    {
        if (sha1( $this->request-> getClientIp() . $this->request->headers->get('User-Agent')) != substr(base64_decode($this->hash), 10, 40)) {
            throw new Exceptions\BadCombinationException('Form origin does not match token origin.');
        }
        if ($this->request->request->get($this->key) !== $this->hash) {
            throw new Exceptions\BadCombinationException('Invalid CSRF token');
        }
    }

    /**
     * Check for token expiration
     *
     * @access private
     * @throws \RangeException if it expired
     */
    private function checkExpiration()
    {
        if ($this->ttl !== null && is_int($this->ttl) && intval(substr(base64_decode($this->hash), 0, 10)) + $this->ttl < time()) {
            throw new \RangeException('CSRF token has expired.');
        }
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param int $ttl
     */
    public function setTtl($ttl)
    {
        if (!is_int($ttl)) {
            throw new \InvalidArgumentException('Integer expected: $ttl');
        }
        $this->ttl = $ttl;
    }

    /**
     * @return int
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     * CSRF token generator. After generating the token, put it inside a hidden form field named $this->key.
     *
     * @return string The generated, base64 encoded token.
     */
    public function generate()
    {
        // token generation (basically base64_encode any random complex string, time() is used for token expiration)
        $this->hash = base64_encode(time() . sha1($this->request-> getClientIp() . $this->request->headers->get('User-Agent')) . bin2hex(random_bytes(32)));
        Session::put($this->key, $this->hash);
        return $this->hash;
    }
}
