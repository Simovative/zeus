<?php
namespace Simovative\Skeleton\Demo\Command;

use Simovative\Skeleton\Application\ApplicationState;
use Simovative\Zeus\Command\CommandFailureResponse;
use Simovative\Zeus\Command\CommandHandlerInterface;
use Simovative\Zeus\Command\CommandInterface;
use Simovative\Zeus\Command\CommandResponseInterface;
use Simovative\Zeus\Command\CommandSuccessResponse;

/**
 * @author Benedikt Schaller
 */
class LoginCommandHandler implements CommandHandlerInterface {
	
	/**
	 * @var ApplicationState
	 */
	private $state;
	
	/**
	 * @author Benedikt Schaller
	 * @param ApplicationState $state
	 */
	public function __construct(ApplicationState $state) {
		$this->state = $state;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @param CommandInterface|LoginCommand $command
	 * @return CommandResponseInterface
	 */
	public function execute(CommandInterface $command) {
		if ($command->getPassword() == 'test123') {
			$this->state->setUser(123, $command->getUsername());
			return new CommandSuccessResponse($command->getUsername());
		} else {
			return new CommandFailureResponse('Wrong password! The right one is "test123".');
		}
	}
}