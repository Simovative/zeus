<?php
namespace Simovative\Zeus\Bundle;

use Simovative\Zeus\Command\CommandBuilderInterface;
use Simovative\Zeus\Command\CommandRequest;
use Simovative\Zeus\Dependency\MasterFactory;
use Simovative\Zeus\Command\CommandResponseInterface;
use Simovative\Zeus\Content\Content;

/**
 * @author mnoerenberg
 */
abstract class BundleController {
	
	/**
	 * @var MasterFactory
	 */
	private $masterFactory;
	
	/**
	 * @author mnoerenberg
	 * @return MasterFactory
	 */
	protected function getMasterFactory() {
		return $this->masterFactory;
	}
	
	/**
	 * @author mnoerenberg
	 * @param MasterFactory $masterFactory
	 * @return void
	 */
	public function setMasterFactory(MasterFactory $masterFactory) {
		$this->masterFactory = $masterFactory;
	}
	
	/**
	 * Will be called if command handler validator if result is invalid.
	 *
	 * @author mnoerenberg
	 * @param CommandBuilderInterface $commandBuilder
	 * @param CommandRequest $commandRequest
	 * @return null|Content
	 */
	abstract public function whichContentForFailedValidation(CommandBuilderInterface $commandBuilder, CommandRequest $commandRequest);
	
	/**
	 * Will be called if command handler response is successful.
	 *
	 * @author mnoerenberg
	 * @param CommandBuilderInterface $commandBuilder
	 * @param CommandRequest $commandRequest
	 * @param CommandResponseInterface $commandResponse
	 * @return null|Content
	 */
	abstract public function whichContentForCommandResult(CommandBuilderInterface $commandBuilder, CommandRequest $commandRequest, CommandResponseInterface $commandResponse);
}
