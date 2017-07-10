<?php
namespace Simovative\Skeleton\Demo\Command;

use Simovative\Skeleton\Demo\DemoFactory;
use Simovative\Zeus\Command\CommandBuilderAbstract;
use Simovative\Zeus\Command\CommandHandlerInterface;
use Simovative\Zeus\Command\CommandInterface;
use Simovative\Zeus\Command\CommandRequest;
use Simovative\Zeus\Dependency\MasterFactory;

/**
 * @author Benedikt Schaller
 */
class LogoutCommandBuilder extends CommandBuilderAbstract {
	
	/**
	 * Will create the command from the command request. The result is only an marker interface.
	 *
	 * @author Benedikt Schaller
	 * @param CommandRequest $commandRequest
	 * @return CommandInterface
	 */
	public function createCommand(CommandRequest $commandRequest) {
		return new LogoutCommand();
	}
	
	/**
	 * Should call the correct method in the master factory to create the command handler. Should never create the
	 * handler on itself.
	 *
	 * @author Benedikt Schaller
	 * @param MasterFactory|DemoFactory $factory
	 * @return CommandHandlerInterface
	 */
	public function createCommandHandler(MasterFactory $factory) {
		return $factory->createDemoLogoutCommandHandler();
	}
}