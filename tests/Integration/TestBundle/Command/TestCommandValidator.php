<?php
namespace Simovative\Test\Integration\TestBundle\Command;

use Simovative\Zeus\Command\CommandRequest;
use Simovative\Zeus\Command\CommandValidator;

/**
 * @author Benedikt Schaller
 */
class TestCommandValidator extends CommandValidator {
	
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