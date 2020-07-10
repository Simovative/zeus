<?php
namespace Simovative\Test\Integration\TestBundle\Command;

use Simovative\Test\Integration\TestBundle\ApplicationState;
use Simovative\Zeus\Command\CommandHandlerInterface;
use Simovative\Zeus\Command\CommandInterface;
use Simovative\Zeus\Command\CommandResponseInterface;
use Simovative\Zeus\Command\CommandSuccessResponse;

/**
 * @author Benedikt Schaller
 */
class TestCommandHandler implements CommandHandlerInterface {
	
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
	 * @param CommandInterface|TestCommand $command
	 * @return CommandResponseInterface
	 */
	public function execute(CommandInterface $command) {
		$this->state->logout();
		return new CommandSuccessResponse($command);
	}
}