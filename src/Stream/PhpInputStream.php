<?php
declare(strict_types=1);

namespace Simovative\Zeus\Stream;

/**
 * @author Benedikt Schaller
 */
class PhpInputStream extends Stream {
	
	private const PHP_INPUT_STREAM = 'php://input';
	private const OPEN_MODE_READ = 'r';
	
	/**
	 * @author Benedikt Schaller
	 */
	public function __construct() {
		parent::__construct(self::PHP_INPUT_STREAM, self::OPEN_MODE_READ);
	}
}