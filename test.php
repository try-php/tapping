<?php
namespace Trying;

use function Task\{forkTask, getProcessStatus};
use function Crayon\{text};
use function Load\{dots,removeLastCharsByCount};
use function Argv\{cleanArguments, getFlags};

$cleanedArguments = cleanArguments($argv);
$flags = getFlags($cleanedArguments, ['b' => 'build', 'ci' => 'build']);

function test(string $desc, callable $test) {
	$loadingDesc = text($desc)->yellow();
	$successIndicator = text('ok')->green();
	$successMessage = "$desc $successIndicator";
	$failIndicator = text('not ok')->red();
	$failMessage = "$desc $failIndicator";

	$pid = forkTask($test, []);
	if ($pid > 0) {
		try {
			dots(function () use ($pid, $loadingDesc) {
				$processStatus = getProcessStatus($pid, $status);
				if ($processStatus > 0) {
					if ($status === 0) {
						return true;
					} else {
						return false;
					}
				}

				return "$loadingDesc";
			}, "$successMessage");
		} catch (\Exception $ex) {
			echo "$failMessage\n";
			if ($flags->build) {
				exit(1);
			}
		}
	} else {
		exit;
	}
}