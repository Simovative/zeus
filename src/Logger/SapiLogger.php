<?php
namespace Simovative\Zeus\Logger;

/**
 * Logs to SAPI.
 *
 * @author Benedikt Schaller
 */
class SapiLogger implements LoggerInterface {
	
	const LOG_TYPE_SAPI = 4;
	
	/**
	 * @author mnoerenberg
	 * @param string $message
	 * @return void
	 */
	public function log($message) {
		error_log($message, self::LOG_TYPE_SAPI);
	}
}