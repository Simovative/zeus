<?php
namespace Simovative\Skeleton\Demo;

use Simovative\Zeus\Bundle\Bundle;
use Simovative\Zeus\Command\ApplicationController;
use Simovative\Zeus\Command\CommandRequestRouterChain;
use Simovative\Zeus\Dependency\FactoryInterface;
use Simovative\Zeus\Http\Get\HttpGetRequestRouterChain;

/**
 * @author Benedikt Schaller
 */
class DemoBundle extends Bundle {
	
	/**
	 * @author Benedikt Schaller
	 * @inheritdoc
	 */
	protected function createBundleFactory() {
		return new DemoFactory();
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return DemoFactory|FactoryInterface
	 */
	protected function getBundleFactory() {
		return parent::getBundleFactory();
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritdoc
	 */
	public function registerGetRouters(HttpGetRequestRouterChain $router) {
		$router->register($this->getBundleFactory()->createDemoGetRequestRouter());
	}

	/**
	 * @author Benedikt Schaller
	 * @inheritdoc
	 */
	public function registerPostRouters(CommandRequestRouterChain $router) {
		$router->register($this->getBundleFactory()->createDemoPostRequestRouter());
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritdoc
	 */
	public function registerBundleController(ApplicationController $applicationController) {
		$applicationController->registerBundleController($this->getBundleFactory()->createDemoBundleController());
	}
}