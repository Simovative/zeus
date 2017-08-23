<?php
namespace {{appNamespace}}\{{bundleName}}\Command;

use Simovative\Zeus\Command\CommandHandlerInterface;
use Simovative\Zeus\Command\CommandInterface;
use Simovative\Zeus\Command\CommandResponseInterface;
use Simovative\Zeus\Command\CommandSuccessResponse;

class {{name}}CommandHandler implements CommandHandlerInterface {
	
	/**
	 * @param CommandInterface|{{name}}Command $command
	 * @return CommandResponseInterface
	 */
	public function execute(CommandInterface $command) {
		return new CommandSuccessResponse();
	}
}