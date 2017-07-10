<?php
namespace Simovative\Zeus\Command;

/**
 * @author mnoerenberg
 */
interface CommandHandlerInterface {

	/**
	 * @author mnoerenberg
	 * @param CommandInterface $command
	 * @return CommandResponseInterface
	 */
	public function execute(CommandInterface $command);
}
