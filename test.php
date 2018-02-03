<?php
namespace Tapping;

use function Task\{forkTask, getProcessStatus};
use function Crayon\{text};
use function Load\{dots,removeLastCharsByCount};
use TryPhp\{PredictOutputTrait, PredictIsTrait, PredictExceptionTrait};

/**
 * function to trigger a atomic test run and show an success/fail indicator (and on provided flag exit the parent process)
 * @param string $description
 * @param callable $test
 */
function test(string $description, callable $test) {
	$options = getopt('bq', ['build', 'quite']);
	$buildFlag = isset($options['b']) || isset($options['build']);
	$quiteFlag = isset($options['q']) || isset($options['quite']);
	$phpOutputBuffer = 'php://output';

	$loadingDescription = text($description)->yellow();

	$successIndicator = text('ok')->green();
	$successMessage = "$description $successIndicator";

	$failIndicator = text('not ok')->red();
	$failMessage = "$description $failIndicator";

	$cleanupBlock = str_repeat(' ', strlen($description) + 2);
	$redBlock = text('▌')->red();

	if (!$quiteFlag) {
		set_error_handler(function ($code, $message, $file, $line) use ($redBlock, $cleanupBlock, $phpOutputBuffer) {
			$errorMessage = text($message)->red();
			$errorMessage = "\r$redBlock $errorMessage (Error $code) $cleanupBlock";
			$errorMessage .= "\n$redBlock $file #$line";
			fwrite(fopen($phpOutputBuffer, 'w'), "$errorMessage\n\n");
		});
	}

	try {
		$pid = forkTask($test, [new class() {
			use PredictOutputTrait;
			use PredictIsTrait;
			use PredictExceptionTrait;
		}]);
		
	} catch(\Exception $ex) {
		if (!$quiteFlag) {
			$exceptionFile = text($ex->getFile());
			$exceptionLine = text($ex->getLine());
			$exceptionMessage = text($ex->getMessage())->red();
 
			$exceptionOutput = "\r$cleanupBlock";
			$exceptionOutput .= "\n$redBlock $description";
			$exceptionOutput .=  "\n$redBlock $exceptionFile #$exceptionLine";
			$exceptionOutput .= "\n$redBlock $exceptionMessage\n";
			
			fwrite(fopen($phpOutputBuffer, 'w'), "$exceptionOutput\n");
		}

		exit(1);
	}

	if ($pid > 0) {
		try {
			dots(function () use ($pid, $loadingDescription) {
				$processStatus = getProcessStatus($pid, $status);
				if ($processStatus > 0) {
					return $status === 0;
				}

				return "$loadingDescription";
			}, "$successMessage");
		} catch (\Exception $ex) {
			fwrite(fopen($phpOutputBuffer, 'w'), "$failMessage\n");

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
 * function to trigger an indicator that there is something to do on test run
 * @param string $description
 */
function todo(string $description) {
	$todoIndicator = text('todo')->magenta()->italic();
	$todoDescription = text($description);
	fwrite(fopen('php://output', 'w'), "$todoIndicator $todoDescription\n");
}