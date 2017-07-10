<?php
namespace Simovative\Zeus\Command;

use Command\CommandValidationResult;

/**
 * @author mnoerenberg
 */
interface CommandValidatorInterface {
	
	/**
	 * @author mnoerenberg
	 * @param CommandRequest $request
	 * @return CommandValidationResult
	 */
	public function validate(CommandRequest $request);
}
