<?php

class TimeLockCryptTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test equals generate key by time and generate key by iterations
     *
     * @access public
     */
    public function testKey()
    {
        $key = '12345';
        $timeLock = new TimeLockCrypt($key);

        $key1 = $this->invokeMethod($timeLock, 'generateByTime', array($key, '+1 seconds'));
        $key2 = $this->invokeMethod($timeLock, 'generateByIterations', array($key, $timeLock->getIterations()));

        $this->assertEquals($key1, $key2);
    }

    /**
     * Test equals time lock encrypt and decrypt message
     * 
     * @access public
     */
	public function testMessage()
    {
        $timeLock = new TimeLockCrypt('');

        $message = 'secret message';
    	$encrypted = $timeLock->encrypt('+1 second', $message);

        $this->assertEquals($message, $timeLock->decrypt($encrypted));
    }

    /**
     * Call protected/private method of a class.
     *
     * @access public
     * @param object $object
     * @param string $methodName
     * @param array $parameters 
     * @return mixed
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(TRUE);

        return $method->invokeArgs($object, $parameters);
    }

}