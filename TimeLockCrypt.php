<?php

require "fernet-php/src/Fernet.php";
use Fernet\Fernet;

/**
 * Time-Lock encryption class
 *
 * @author Lukas Mestan
 * @copyright GPL v2
 * @see http://www.gwern.net/Self-decrypting files
 * @see http://people.csail.mit.edu/rivest/lcs35-puzzle-description.txt
 * @version 1.0.0
 */
class TimeLockCrypt
{
	private $iterations = 0;

	private $keyseed = ''; 
	private $signing_key = '';


	public function __construct($keyseed)
	{
		$this->keyseed = $keyseed;
		$this->signing_key = substr($keyseed, 0, 16);
	}


	public function encrypt($delta, $message)
	{
		$key = $this->generateByTime($this->keyseed, $delta);

		return (new Fernet($key))->encode($message);
	}

	public function decrypt($encrypted)
	{
		$key = $this->generateByIterations($this->keyseed, $this->iterations);

		return (new Fernet($key))->decode($encrypted);
	}


	private function generateByIterations($seed, $iters)
	{
		$hash = hash_hmac("sha256", $seed, $this->signing_key, TRUE);

		foreach(range(1, $iters) as $x)
		{
			$hash = hash_hmac("sha256", $hash, $this->signing_key, TRUE);
		}

		return Fernet::base64url_encode($hash);
	}

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

    public function getIterations()
    {
        return $this->iterations;
    }

}
