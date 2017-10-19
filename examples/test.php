<?php
require_once __DIR__ . '/../vendor/autoload.php';

use function Tapping\{test, todo};

test('some test that gonna fail', function () {
	exit(1);
});

test('some test that gonna pass', function () {
	exit(0);
});

todo('some test i need to write');