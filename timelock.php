<?php

class TimeLock
{
	private $iterations = 0;
	

	public function encrypt($keyseed, $delta, $message)
	{
		$key = $this->generate_by_time($keyseed, $delta);
	
		return (new Fernet($key))->encrypt($message);
	}

	public function decrypt($keyseed, $iterations, $encrypted)
	{
		$this->iterations = $iterations;
		$key = $this->generate_by_iters($keyseed, $iterations);

		return (new Fernet($key))->decrypt($encrypted);
	}

	public function getIterations()
	{
		return $this->iterations;
	}

	private function generate_by_iters($seed, $iters)
	{
		$hash = hash('sha256', $seed);
		foreach($iters as $x)
		{
			$hash = hash('sha256', $hash);
		}

		return base64_encode($hash);
	}

	private function generate_by_time($seed, $delta)
	{
		$end = time() + strtotime($delta);
		$hash = hash('sha256', $seed);

		$itters = 0;
		while(time() < $end)
		{
			$hash = hash('sha256', $hash);
			$iters += 1;
		}

		$this->iterations = $iters;

		return base64_encode($hash);
	}

}
