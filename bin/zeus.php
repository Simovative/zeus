<?php
/*
 * Paths may vary. If you installed this framework via composer, the second
 * path is correct. If you just checked out the framework directly, the first
 * one will be the right one.
 */
foreach (array(
			 __DIR__ . '/../vendor/autoload.php',
			 __DIR__ . '/../../../autoload.php'
		 ) as $file) {
	if (is_readable($file)) {
		require $file;
	}
}

$app = new \Simovative\Zeus\Console\CliApplication();
$app->run();
