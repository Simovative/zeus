<?php
namespace Simovative\Zeus\Logger;

/**
 * @author mnoerenberg
 */
interface LoggerInterface {
	
	/**
	 * @author mnoerenberg
	 * @param string $message
	 * @return void
	 */
	public function log($message);
}
