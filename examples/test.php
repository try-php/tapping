<?php
require_once __DIR__ . '/../vendor/autoload.php';

use function Tapping\{test, todo};

test('some test that gonna pass', function () {
	exit;
});

test('some test that gonna fail with message', function () {
	throw new Exception('Some very serious error');
});


test('some test that just fails', function () {
	exit(1);
});

todo('some test i need to write');