# Time Lock Encryption Class [![Build Status](https://travis-ci.org/arzzen/php-time-lock-encryption.svg)](https://travis-ci.org/arzzen/php-time-lock-encryption) [![Latest Stable Version](https://poser.pugx.org/arzzen/php-time-lock-encryption/version)](https://packagist.org/packages/arzzen/php-time-lock-encryption) [![License](https://poser.pugx.org/arzzen/php-time-lock-encryption/license)](https://packagist.org/packages/arzzen/php-time-lock-encryption)

Implementation of timed-release crypto.

This class can encrypt data using key generated for a time period.

It takes an expression that defines a given time period and generates a key by creating iteratively hashes of the initial key in a cycle until the specified time as passed.

The class returns the encrypted data using the Fernet class with the generated key.

It can also decrypt previously encrypted data by regenerating the encryption key the same number of iterations that it were used to generate the key when the data was encrypted.


### Requirements

* PHP 5.3.3 or later
* hash extension
* openssl or mcrypt extension


## Installation

You can install this library by using [Composer]. You can also view more info
about this on [Packagist].

Add this to the `require` section in your `composer.json` file.

```json
{
    "require": {
        "arzzen/php-time-lock-encryption": "1.1.*"
    }
}
```


### Usage

```php
<?php
use TimeLockCrypt;

$timeLock = new TimeLockCrypt('keyseed');

$message = 'secret message';
$encrypted = $timeLock->encrypt('+10 second', $message);
$iterations = $timeLock->getIterations();

$decrypted = $timeLock->decrypt($encrypted, $iterations);

var_dump($message == $decrypted);
?>
```
