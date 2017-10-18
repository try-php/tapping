<?php
namespace Trying;
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/test.php';

test('1', function () {
	exit(1);
});

test('2', function () {
	exit(0);
});