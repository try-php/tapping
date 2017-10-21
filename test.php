<?php
namespace Tapping;

use function Task\{forkTask, getProcessStatus};
use function Crayon\{text};
use function Load\{dots,removeLastCharsByCount};
use TryPhp\PredictOutputTrait;

/**
 * function to trigger a atomic test run and show an success/fail indicator (and on provided flag exit the parent process)
 * @param string $description
 * @param callable $test
 */
function test(string $description, callable $test) {
	$options = getopt('bq', ['build', 'quite']);
	$buildFlag = isset($options['b']) || isset($options['build']);
	$quiteFlag = isset($options['q']) || isset($options['quite']);

	$loadingDescription = text($description)->yellow();

	$successIndicator = text('ok')->green();
	$successMessage = "$description $successIndicator";

	$failIndicator = text('not ok')->red();
	$failMessage = "$description $failIndicator";

	try {
		$pid = forkTask($test, [new class() {
			use PredictOutputTrait;
		}]);
		
	} catch(\Exception $ex) {
		if (!$quiteFlag) {
			$cleanupBlock = str_repeat(' ', strlen($description) + 2);
			$redBlock = text('▌')->red();
			$exceptionFile = text($ex->getFile());
			$exceptionLine = text($ex->getLine());
			$exceptionMessage = text($ex->getMessage())->red();
 
			$exceptionOutput = "\r$cleanupBlock";
			$exceptionOutput .= "\n$redBlock $description";
			$exceptionOutput .=  "\n$redBlock $exceptionFile #$exceptionLine";
			$exceptionOutput .= "\n$redBlock $exceptionMessage\n";
			
			fwrite(fopen('php://output', 'w'), "$exceptionOutput\n");
		}

		exit(1);
	}

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

			// exit parent process if in a ci run
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