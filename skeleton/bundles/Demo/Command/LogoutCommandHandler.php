<?php
namespace Simovative\Skeleton\Demo\Command;

use Simovative\Skeleton\Application\ApplicationState;
use Simovative\Zeus\Command\CommandHandlerInterface;
use Simovative\Zeus\Command\CommandInterface;
use Simovative\Zeus\Command\CommandResponseInterface;
use Simovative\Zeus\Command\CommandSuccessResponse;

/**
 * @author Benedikt Schaller
 */
class LogoutCommandHandler implements CommandHandlerInterface {
	
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
		$this->state->logout();
		return new CommandSuccessResponse();
	}
}