# Time Lock Encryption Class [![Build Status](https://travis-ci.org/arzzen/php-time-lock-encryption.svg)](https://travis-ci.org/arzzen/php-time-lock-encryption)

Implementation of timed-release crypto


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
