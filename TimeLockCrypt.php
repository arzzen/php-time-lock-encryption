<?php

require "fernet-php/src/Fernet.php";
use Fernet\Fernet;

/**
 * Time Lock Encryption Class
 *
 * @author Lukas Mestan
 * @copyright GPL v2
 * @see http://www.gwern.net/Self-decrypting files
 * @see http://people.csail.mit.edu/rivest/lcs35-puzzle-description.txt
 * @see https://github.com/kelvinmo/fernet-php 
 * @version 1.0.0
 */
class TimeLockCrypt
{
	/**
	 * Count of iterations
	 *
	 * @access private
	 * @var integer
	 */
	private $iterations = 0;

	/**
	 * Keyseed
	 *
	 * @access private
	 * @var string
	 */
	private $keyseed = ''; 
	/**
	 * Signing key
	 *
	 * @access private
	 * @var string
	 */
	private $signing_key = '';


	/**
	 * Class contructor
	 *
	 * @access public
	 * @param string $keyseed 
	 */
	public function __construct($keyseed)
	{
		$this->keyseed = $keyseed;
		$this->signing_key = substr($keyseed, 0, 16);
	}

	/**
	 * Encrypt message
	 *
	 * @access public
	 * @param string $delta
	 * @param string $message 
	 * @return string
	 */
	public function encrypt($delta, $message)
	{
		$key = $this->generateByTime($this->keyseed, $delta);
		$fernet = new Fernet($key);

		return $fernet->encode($message);
	}

	/**
	 * Decrypt message
	 *
	 * @access public
	 * @param string $encrypted 
	 * @return string
	 */
	public function decrypt($encrypted)
	{
		$key = $this->generateByIterations($this->keyseed, $this->iterations);
		$fernet = new Fernet($key);

		return $fernet->decode($encrypted);
	}

	/**
	 * Iterations number
	 *
	 * @access public
	 * @return integer
	 */
    public function getIterations()
    {
        return $this->iterations;
    }

	/**
	 * Generate key by iterations
	 *
	 * @access private
	 * @param string $seed
	 * @param integer $iters
	 * @return string
	 */
	private function generateByIterations($seed, $iters)
	{
		$hash = hash_hmac("sha256", $seed, $this->signing_key, TRUE);

		foreach(range(1, $iters) as $x)
		{
			$hash = hash_hmac("sha256", $hash, $this->signing_key, TRUE);
		}

		return Fernet::base64url_encode($hash);
	}

	/**
	 * Generate key by time
	 *
	 * @access private
	 * @param string $seed
	 * @param integer|string $delta strtotime 
	 * @return string
	 */
	private function generateByTime($seed, $delta)
	{
		$end = strtotime($delta);
		$hash = hash_hmac("sha256", $seed, $this->signing_key, TRUE);

		$iters = 0;
		while(time() < $end)
		{
			$hash = hash_hmac("sha256", $hash, $this->signing_key, TRUE);
			$iters += 1;
		}

		$this->iterations = $iters;

		return Fernet::base64url_encode($hash);
	}

}
