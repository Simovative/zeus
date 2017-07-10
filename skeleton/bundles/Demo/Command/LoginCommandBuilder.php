<?php
namespace Simovative\Skeleton\Demo\Command;

use Simovative\Skeleton\Demo\DemoFactory;
use Simovative\Zeus\Command\CommandBuilderAbstract;
use Simovative\Zeus\Command\CommandRequest;
use Simovative\Zeus\Dependency\MasterFactory;

/**
 * @author Benedikt Schaller
 */
class LoginCommandBuilder extends CommandBuilderAbstract {
	
	/**
	 * @author Benedikt Schaller
	 * @inheritdoc
	 * @return LoginCommand
	 */
	public function createCommand(CommandRequest $commandRequest) {
		return new LoginCommand($commandRequest->get('username'), $commandRequest->get('password'));
	}
	
	/**
	 * @author Benedikt Schaller
	 * @param MasterFactory|DemoFactory $factory
	 * @return LoginCommandHandler
	 */
	public function createCommandHandler(MasterFactory $factory) {
		return $factory->createDemoLoginCommandHandler();
	}
}