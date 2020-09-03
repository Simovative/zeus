<?php
namespace Simovative\Zeus\Command;

use Simovative\Zeus\Dependency\MasterFactory;

/**
 * A command builder connects the validator, command and command handler that belong to a specific command in
 * the application. If will also map the command request to the command.
 *
 * @author Benedikt Schaller
 */
interface CommandBuilderInterface {
	
	/**
	 * Will create the command from the command request. The result is only an marker interface.
	 *
	 * @author Benedikt Schaller
	 * @param CommandRequest $commandRequest
	 * @return CommandInterface
	 */
	public function createCommand(CommandRequest $commandRequest);
	
	/**
	 * Should call the correct method in the master factory to create the command handler. Should never create the
	 * handler on itself.
	 *
	 * @author Benedikt Schaller
	 * @param MasterFactory $factory
	 * @return CommandHandlerInterface
	 */
	public function createCommandHandler(MasterFactory $factory);
	
	/**
	 * Should return the command validator. The validator should be passed to the command builder as a constructor
	 * parameter because it will always be used.
	 *
	 * @author Benedikt Schaller
	 * @return CommandValidatorInterface|null
	 */
	public function getCommandValidator();
}