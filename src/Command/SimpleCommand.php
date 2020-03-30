<?php declare(strict_types=1);

namespace Simovative\Zeus\Command;

use Simovative\Zeus\Dependency\MasterFactory;

/**
 * Imagine you'd have to create four different classes just to log somebody out.
 * That'd be basically $this->applicationState->destroy(). I don't need validation,
 * command creation, builder and handler and whatnot. I just need to put one single
 * line of code somewhere with as little overhead as possible.
 *
 * @author shartmann
 */
abstract class SimpleCommand implements CommandBuilderInterface, CommandValidatorInterface, CommandInterface, CommandHandlerInterface {
	
	/**
	 * @author shartmann
	 * @param MasterFactory $factory
	 * @return SimpleCommand
	 */
	public function createCommandHandler(MasterFactory $factory) {
		return $this;
	}
	
	/**
	 * @author shartmann
	 * @return SimpleCommand
	 */
	public function getCommandValidator() {
		return $this;
	}
	
	/**
	 * @author shartmann
	 * @param CommandRequest $commandRequest
	 * @return CommandInterface
	 */
	public function createCommand(CommandRequest $commandRequest) {
		$this->getParameters($commandRequest);
		return $this;
	}
	
	/**
	 * @author shartmann
	 * @param CommandRequest $commandRequest
	 * @return void
	 */
	abstract protected function getParameters(CommandRequest $commandRequest): void ;
	
	/**
	 * @author shartmann
	 * @param CommandRequest $request
	 * @return CommandValidationResult
	 */
	abstract public function validate(CommandRequest $request);
	
	/**
	 * @author shartmann
	 * @param CommandInterface $command
	 * @return CommandResponseInterface
	 */
	abstract public function execute(CommandInterface $command): CommandResponseInterface;
	
}