# test
> Atomic test functions

[![Build Status](https://travis-ci.org/try-php/test.svg?branch=master)](https://travis-ci.org/try-php/test)

## Install

```bash
$ composer require try/test
```

## Usage

```php
<?php
require_once '/path/to/autoload.php';

use function Trying\test;

test('some description of the test', function () {
	exit(0); // test will be marked as passed
	// exit(1); to indicate the test failed
});
```

## License

GPL-2.0 © Willi Eßer, Felix Buchheim