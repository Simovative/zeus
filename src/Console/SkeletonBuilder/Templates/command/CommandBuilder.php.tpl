<?php
namespace {{appNamespace}}\{{bundleName}}\Command;

use {{appNamespace}}\{{bundleName}\{{bundleName}}Factory;
use Simovative\Zeus\Command\CommandBuilderAbstract;
use Simovative\Zeus\Command\CommandRequest;
use Simovative\Zeus\Dependency\MasterFactory;

class {{name}}CommandBuilder extends CommandBuilderAbstract {

	/**
	 * @inheritdoc
	 */
	public function createCommand(CommandRequest $commandRequest) {
		return new {{name}}Command();
	}

	/**
 	 * @inheritdoc
 	 * @param {{bundleName}}Factory|MasterFactory $factory
	*/
	public function createCommandHandler(MasterFactory $factory) {
		return $factory->create{{name}}CommandHandler();
	}
}