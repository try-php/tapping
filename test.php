<?php
namespace Tapping;

use function Task\{forkTask, getProcessStatus};
use function Crayon\{text};
use function Load\{dots,removeLastCharsByCount};

/**
 * function to trigger a atomic test run and show an success/fail indicator (and on provided flag exit the parent process)
 * @param string $description
 * @param callable $test
 */
function test(string $description, callable $test) {
	// build flag
	$options = getopt('b', ['build', 'ci']);
	$buildFlag = isset($options['b']) || isset($options['build']) || isset($options['ci']);

	$loadingDescription = text($description)->yellow();

	$successIndicator = text('ok')->green();
	$successMessage = "$description $successIndicator";

	$failIndicator = text('not ok')->red();
	$failMessage = "$description $failIndicator";

	$pid = forkTask($test, []);

	if ($pid > 0) {
		try {
			dots(function () use ($pid, $loadingDescription) {
				$processStatus = getProcessStatus($pid, $status);
				if ($processStatus > 0) {
					if ($status === 0) {
						return true;
					} else {
						return false;
					}
				}

				return "$loadingDescription";
			}, "$successMessage");
		} catch (\Exception $ex) {
			fwrite(fopen('php://output', 'w'), "$failMessage\n");
			if ($buildFlag) {
				exit(1);
			}
		}
	} else {
		exit;
	}
}

/**
 * function to trigger an TODO indicator on test run
 * @param string $description
 */
function todo(string $description) {
	$todoIndicator = text('todo')->magenta()->italic();
	$todoDescription = text($description);
	fwrite(fopen('php://output', 'w'), "$todoIndicator $todoDescription\n");
}