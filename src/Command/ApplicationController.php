<?php
namespace Simovative\Zeus\Command;

use Simovative\Zeus\Bundle\BundleController;
use Simovative\Zeus\Dependency\MasterFactory;
use Simovative\Zeus\Content\Content;

/**
 * @author mnoerenberg
 */
class ApplicationController {
	
	/**
	 * @var MasterFactory
	 */
	private $masterFactory;
	
	/**
	 * @var BundleController[]
	 */
	private $bundleControllers = array();
	
	/**
	 * @author mnoerenberg
	 * @param MasterFactory $masterFactory
	 */
	public function __construct(MasterFactory $masterFactory) {
		$this->masterFactory = $masterFactory;
	}
	
	/**
	 * @author mnoerenberg
	 * @param BundleController $bundleController
	 * @return void
	 */
	public function registerBundleController(BundleController $bundleController) {
		$this->bundleControllers[] = $bundleController;
		$bundleController->setMasterFactory($this->masterFactory);
	}
	
	/**
	 * Will be valled if command handler validator result is invalid.
	 *
	 * @author mnoerenberg
	 * @param CommandBuilderInterface $commandBuilder
	 * @param CommandRequest $commandRequest
	 * @return Content
	 */
	public function whichContentForFailedValidation(CommandBuilderInterface $commandBuilder, CommandRequest $commandRequest) {
		foreach ($this->bundleControllers as $bundleController) {
			$content = $bundleController->whichContentForFailedValidation($commandBuilder, $commandRequest);
			if ($content !== null) {
				return $content;
			}
		}
		
		throw new \LogicException('Content not found for invalid command request.');
	}
	
	/**
	 * Will be called if command handler response is successful.
	 *
	 * @author mnoerenberg
	 * @param CommandBuilderInterface $commandBuilder
	 * @param CommandRequest $commandRequest
	 * @param CommandResponseInterface $commandResult
	 * @return Content
	 */
	public function whichContentForCommandResult(CommandBuilderInterface $commandBuilder, CommandRequest $commandRequest, CommandResponseInterface $commandResult) {
		foreach ($this->bundleControllers as $bundleController) {
			$content = $bundleController->whichContentForCommandResult($commandBuilder, $commandRequest, $commandResult);
			if ($content !== null) {
				return $content;
			}
		}
		
		throw new \LogicException('Content not found for valid command request.');
	}
}
