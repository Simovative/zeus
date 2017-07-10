<?php
namespace Simovative\Zeus\Command;

use Simovative\Zeus\Dependency\MasterFactory;

/**
 * @author mnoerenberg
 */
class CommandHandlerLocator {
	
	/**
	 * @var MasterFactory
	 */
	private $factory;
	
	/**
	 * @author mnoerenberg
	 * @param MasterFactory $masterFactory
	 */
	public function __construct(MasterFactory $masterFactory) {
		$this->factory = $masterFactory;
	}
	
	/**
	 * Creates the correct command handler by command name.
	 *
	 * @author mnoerenberg
	 * @param CommandInterface $command
	 * @return CommandHandlerInterface
	 */
	public function getCommandHandlerFor(CommandInterface $command) {
		$reflection = new \ReflectionClass($command);
		$className = $reflection->getShortName();
		$commandHandlerFactoryMethodName = 'create' . $className . 'Handler';
		return $this->factory->$commandHandlerFactoryMethodName();
	}
}
