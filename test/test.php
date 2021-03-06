<?php
require_once __DIR__ . '/../vendor/autoload.php';

use function Tapping\{test, todo};

// desc is shown in both cases
ob_start();
test('desc', function () {exit;});
$successState = ob_get_clean();

if (!preg_match('/desc/', $successState)) {
	trigger_error('test failed', E_USER_ERROR);
}

ob_start();
test('desc', function () {throw new Exception('something happened');});
$failState = ob_get_clean();

if (!preg_match('/desc/', $successState)) {
	trigger_error('test failed', E_USER_ERROR);
}

ob_start();
test('desc', function () {exit(1);});
$failState = ob_get_clean();

if (!preg_match('/desc/', $successState)) {
	trigger_error('test failed', E_USER_ERROR);
}

// successful
ob_start();
test('desc', function () {exit;});
$successState = ob_get_clean();

if (preg_match('/not/', $successState)) {
	trigger_error('test failed', E_USER_ERROR);
}

// fail
ob_start();
test('desc', function () {throw new Exception('something happened');});
$successState = ob_get_clean();

if (!preg_match('/not/', $successState)) {
	trigger_error('test failed', E_USER_ERROR);
}

// todo
ob_start();
todo('desc');
$todoState = ob_get_clean();

if (
	!preg_match('/todo/', $todoState) ||
	!preg_match('/desc/', $todoState)
) {
	trigger_error('test failed', E_USER_ERROR);
}