<?php
namespace {{appNamespace}}\{{bundleName}}\Routing;

use {{appNamespace}}\{{bundleName}}Factory;
use Simovative\Zeus\Bundle\BundleController;
use Simovative\Zeus\Command\CommandRequest;
use Simovative\Zeus\Command\CommandBuilderInterface;
use Simovative\Zeus\Command\CommandResponseInterface;
use Simovative\Zeus\Dependency\MasterFactory;

class {{bundleName}}BundleController extends BundleController {
	
	/**
	 * @return MasterFactory|{{bundleName}}Factory
	 */
	protected function getMasterFactory() {
		return parent::getMasterFactory();
	}
	
	/**
	 * @inheritdoc
	 */
	public function whichContentForFailedValidation(CommandBuilderInterface $commandBuilder, CommandRequest $commandRequest) {
		return $this->getMasterFactory()->createHomePage();
	}
	
	/**
	 * @inheritdoc
	 */
	public function whichContentForCommandResult(CommandBuilderInterface $commandBuilder, CommandRequest $commandRequest, CommandResponseInterface $commandResponse) {
		return $this->getMasterFactory()->createHomePage();
	}
}