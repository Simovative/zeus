<?php declare(strict_types=1);

namespace Simovative\Zeus\Command;

/**
 * Imagine you'd have to create four different classes just to log somebody out.
 * That'd be basically $this->applicationState->destroy(). I don't need validation,
 * command creation, builder and handler and whatnot. I just need to put one single
 * line of code somewhere with as little overhead as possible.
 *
 * @author shartmann
 */
abstract class EmptyCommand extends SimpleCommand {
	
	/**
	 * @author shartmann
	 * @param CommandRequest $commandRequest
	 * @return EmptyCommand
	 */
	public function createCommand(CommandRequest $commandRequest) {
		return $this;
	}
	
	/**
	 * @author shartmann
	 * @param CommandRequest $commandRequest
	 */
	protected function getParameters(CommandRequest $commandRequest): void {
		return;
	}
	
	/**
	 * @author shartmann
	 * @param CommandRequest $request
	 * @return CommandValidationResult
	 */
	public function validate(CommandRequest $request) {
		return new CommandValidationResult(true);
	}
	
}