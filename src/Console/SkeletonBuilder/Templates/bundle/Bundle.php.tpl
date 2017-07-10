<?php
namespace {{appNamespace}}\{{bundleName}};

use Simovative\Zeus\Bundle\Bundle;
use Simovative\Zeus\Command\ApplicationController;
use Simovative\Zeus\Dependency\FactoryInterface;
use Simovative\Zeus\Http\Get\HttpGetRequestRouterChain;
use Simovative\Zeus\Command\CommandRequestRouterChain;

class {{bundleName}}Bundle extends Bundle {
	
	/**
	 * @inheritdoc
	 */
	protected function createBundleFactory() {
		return new {{bundleName}}Factory();
	}
	
	/**
	 * @return {{bundleName}}Factory|FactoryInterface
	 */
	protected function getBundleFactory() {
		return parent::getBundleFactory();
	}
	
	/**
	 * @inheritdoc
	 */
	public function registerGetRouters(HttpGetRequestRouterChain $router) {
		$router->register($this->getBundleFactory()->create{{bundleName}}GetRequestRouter());
	}

	/**
	* @inheritdoc
	*/
	public function registerPostRouters(CommandRequestRouterChain $router) {
		$router->register($this->getBundleFactory()->create{{bundleName}}CommandRouter());
	}

	/**
	* @inheritdoc
	*/
	public function registerBundleController(ApplicationController $applicationController) {
		$applicationController->registerBundleController($this->getBundleFactory()->create{{bundleName}}BundleController());
	}
}