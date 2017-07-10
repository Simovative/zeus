<?php
namespace Simovative\Zeus\Exception;

/**
 * @author mnoerenberg
 */
class DatabaseConnectionException extends \Exception {
	
	/**
	 * @author mnoerenberg
	 */
	public function __construct() {
		parent::__construct('Database connection failed.');
	}
}
