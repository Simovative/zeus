<?php declare(strict_types=1);

namespace Simovative\Skeleton\Demo\Command;

use Simovative\Skeleton\Application\ApplicationState;
use Simovative\Zeus\Command\CommandInterface;
use Simovative\Zeus\Command\CommandResponseInterface;
use Simovative\Zeus\Command\CommandSuccessResponse;
use Simovative\Zeus\Command\EmptyCommand;

/**
 * @author shartmann
 */
class LogoutCommand extends EmptyCommand {
	
	/** @var ApplicationState */
	private $state;
	
	/**
	 * @author shartmann
	 * @param ApplicationState $state
	 */
	public function __construct(ApplicationState $state) {
		$this->state = $state;
	}
	
	/**
	 * @author shartmann
	 * @param CommandInterface $command
	 * @return CommandResponseInterface
	 */
	public function execute(CommandInterface $command): CommandResponseInterface {
		$this->state->logout();
		return new CommandSuccessResponse();
	}
	
}