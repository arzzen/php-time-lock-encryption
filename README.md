# Time Lock Encryption Class [![Build Status](https://travis-ci.org/arzzen/php-time-lock-encryption.svg)](https://travis-ci.org/arzzen/php-time-lock-encryption)

Implementation of timed-release crypto.

This class can encrypt data using key generated for a time period.

It takes an expression that defines a given time period and generates a key by creating iteratively hashes of the initial key in a cycle until the specified time as passed.

The class returns the encrypted data using the Fernet class with the generated key.

It can also decrypt previously encrypted data by regenerating the encryption key the same number of iterations that it were used to generate the key when the data was encrypted.


## Requirements

* PHP 5.3.3 or later
* hash extension
* openssl or mcrypt extension


## Usage

```php
<?php
include "TimeLockCrypt.php";

$timeLock = new TimeLockCrypt('');

$message = 'secret message';
$encrypted = $timeLock->encrypt('+10 second', $message);
$iterations = $timeLock->getIterations();

$decrypted = $timeLock->decrypt($encrypted, $iterations);

var_dump($message == $decrypted);
?>
```
