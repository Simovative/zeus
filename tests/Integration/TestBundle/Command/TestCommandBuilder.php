<?php
namespace Simovative\Test\Integration\TestBundle\Command;

use Simovative\Test\Integration\TestBundle\ApplicationFactory;
use Simovative\Zeus\Command\CommandBuilderAbstract;
use Simovative\Zeus\Command\CommandHandlerInterface;
use Simovative\Zeus\Command\CommandInterface;
use Simovative\Zeus\Command\CommandRequest;
use Simovative\Zeus\Dependency\MasterFactory;

/**
 * @author Benedikt Schaller
 */
class TestCommandBuilder extends CommandBuilderAbstract {
	
	/**
	 * Will create the command from the command request. The result is only an marker interface.
	 *
	 * @author Benedikt Schaller
	 * @param CommandRequest $commandRequest
	 * @return CommandInterface
	 */
	public function createCommand(CommandRequest $commandRequest) {
		return new TestCommand();
	}
	
	/**
	 * Should call the correct method in the master factory to create the command handler. Should never create the
	 * handler on itself.
	 *
	 * @author Benedikt Schaller
	 * @param MasterFactory|ApplicationFactory $factory
	 * @return CommandHandlerInterface
	 */
	public function createCommandHandler(MasterFactory $factory) {
		return $factory->createTestCommandHandler();
	}
}