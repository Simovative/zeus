<?php
namespace Simovative\Skeleton\Demo\Command;

use Simovative\Zeus\Command\CommandRequest;
use Simovative\Zeus\Command\CommandValidator;

/**
 * @author Benedikt Schaller
 */
class LogoutCommandValidator extends CommandValidator {
	
	/**
	 * @author Benedikt Schaller
	 */
	public function __construct() {
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritdoc
	 */
	protected function validateRequest(CommandRequest $commandRequest) {
	}
}