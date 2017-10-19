# tapping
> Atomic testing

![demo](./demo.png)

[![Build Status](https://travis-ci.org/try-php/test.svg?branch=master)](https://travis-ci.org/try-php/test)

## Install

```bash
$ composer require try/tapping
```

## Usage

```php
<?php
require_once '/path/to/autoload.php';

use function Tapping\test;

test('some description of the test', function () {
	exit(0); // test will be marked as passed
	// exit(1); to indicate the test failed
});
```

## API

### Functions

#### `test($description, $test)`

Function to run an atomic test and output it's status.

##### Arguments

| Argument | Type | Description |
|---|---|---|
| $description | `string` | The description what the test case is supposed to do. Will be output on test run with an indication of success or failure. |
| $test | `callable` | The test case encapsulated in an callable. The test case will be forked as a child process, so anything in the callable is appropriate, since it won't affect the parent testrunner process. Needs to `exit(1)` to indicate test as failed and `exit(0)` to mark the test as passed. |

##### CLI Flags

Since the runner of the tests is pure php, it is necessary for the usage in CI pipelines or other build systems that the main test process exists with an error code. Tapping has a build in feature for this.

By providing script flags on script call, e.g.

```bash
$ php test.php --build
```

The test run will exit the whole process with `-1`, as soon as the first test fails.

Flag aliases which trigger such behaviour are `--build`, `--ci` and `-b`.

#### `todo($description)`

Function to show an notation for a test case, not yet written. As reminder or note or whatever.

##### Arguments

| Argument | Type | Description |
|---|---|---|
| $description | `string` | The description of the test to be written. |

## License

GPL-2.0 © Willi Eßer, Felix Buchheim