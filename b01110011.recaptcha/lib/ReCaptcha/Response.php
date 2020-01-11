<?php
/**
 * This is a PHP library that handles calling reCAPTCHA.
 *
 * @copyright Copyright (c) 2015, Google Inc.
 * @link      http://www.google.com/recaptcha
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace ReCaptcha;

/**
 * The response returned from the service.
 */
class Response
{
    /**
     * Success or failure.
     * @var boolean
     */
    private $success = false;
    private $score = 0;
    private $permissibleScore = 0;
    private $arRawResponse = array();

    /**
     * Error code strings.
     * @var array
     */
    private $errorCodes = array();

    /**
     * The hostname of the site where the reCAPTCHA was solved.
     * @var string
     */
    private $hostname;

    /**
     * Constructor.
     *
     * @param boolean $success
     * @param array $errorCodes
     * @param string $hostname
     */
    public function __construct($success, array $errorCodes = array(), $hostname = null, $iScore = 0, $permissibleScore = 0, $sAction = '', $sChallenge_ts = '', $responseData = array())
    {
        $this->success = $success;
        $this->errorCodes = $errorCodes;
        $this->hostname = $hostname;
        $this->score = $iScore;
        $this->permissibleScore = $permissibleScore;
        $this->action = $sAction;
        $this->challenge_ts = $sChallenge_ts;
		$this->arRawResponse = $responseData;
    }

    public function getRawResponse()
    {
        return $this->arRawResponse;
    }	
	
    /**
     * Is success?
     *
     * @return boolean
     */
    public function isSuccess()
    {
        if ($this->success)
        {
            return $this->permissibleScore <= $this->score;
        }
        else
        {
            return false;
        }
    }

    public function getSuccess()
    {
        return $this->success;
    }

    public function getScore()
    {
        return $this->score;
    }

    public function getPermissibleScore()
    {
        return $this->permissibleScore;
    }

    /**
     * Get error codes.
     *
     * @return array
     */
    public function getErrorCodes()
    {
        return $this->errorCodes;
    }

    /**
     * Get hostname.
     *
     * @return string
     */
    public function getHostname()
    {
      return $this->hostname;
    }
}