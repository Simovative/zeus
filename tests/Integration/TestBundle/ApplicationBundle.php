<?php
namespace Simovative\Test\Integration\TestBundle;

use Simovative\Zeus\Bundle\Bundle;
use Simovative\Zeus\Command\ApplicationController;
use Simovative\Zeus\Command\CommandRequestRouterChain;
use Simovative\Zeus\Http\Get\HttpGetRequestRouterChain;

/**
 * @author Benedikt Schaller
 */
class ApplicationBundle extends Bundle {
	
	/**
	 * @var ApplicationFactory
	 */
	private $applicationFactory;
	
	/**
	 * @author Benedikt Schaller
	 * @inheritdoc
	 */
	protected function createBundleFactory() {
		$this->applicationFactory = new ApplicationFactory();
		return $this->applicationFactory;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritdoc
	 */
	public function registerGetRouters(HttpGetRequestRouterChain $router) {
		$router->register($this->applicationFactory->createTestGetRequestRouter());
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritdoc
	 */
	public function registerPostRouters(CommandRequestRouterChain $router) {
		$router->register($this->applicationFactory->createTestPostRequestRouter());
	}
	
	/**
	 * @author Benedikt Schaller
	 * @param CommandRequestRouterChain $router
	 * @return void
	 */
	public function registerPutRouters(CommandRequestRouterChain $router) {
		$router->register($this->applicationFactory->createTestPostRequestRouter());
	}
	
	/**
	 * @author Benedikt Schaller
	 * @param CommandRequestRouterChain $router
	 * @return void
	 */
	public function registerPatchRouters(CommandRequestRouterChain $router) {
		$router->register($this->applicationFactory->createTestPostRequestRouter());
	}
	
	/**
	 * @author Benedikt Schaller
	 * @param CommandRequestRouterChain $router
	 * @return void
	 */
	public function registerDeleteRouters(CommandRequestRouterChain $router) {
		$router->register($this->applicationFactory->createTestPostRequestRouter());
	}
	
	/**
	 * @author Benedikt Schaller
	 * @param ApplicationController $applicationController
	 * @return void
	 */
	public function registerPostController(ApplicationController $applicationController) {
		$applicationController->registerBundleController($this->applicationFactory->createTestBundleController());
	}
	
	/**
	 * @author Benedikt Schaller
	 * @param ApplicationController $applicationController
	 * @return void
	 */
	public function registerPatchController(ApplicationController $applicationController) {
		$applicationController->registerBundleController($this->applicationFactory->createTestBundleController());
	}
	
	/**
	 * @author Benedikt Schaller
	 * @param ApplicationController $applicationController
	 * @return void
	 */
	public function registerPutController(ApplicationController $applicationController) {
		$applicationController->registerBundleController($this->applicationFactory->createTestBundleController());
	}
	
	/**
	 * @author Benedikt Schaller
	 * @param ApplicationController $applicationController
	 * @return void
	 */
	public function registerDeleteController(ApplicationController $applicationController) {
		$applicationController->registerBundleController($this->applicationFactory->createTestBundleController());
	}
	
	/**
	 * @author Benedikt Schaller
	 * @param HttpGetRequestRouterChain $router
	 * @return void
	 */
	public function registerHeaderRouters(HttpGetRequestRouterChain $router) {
		$router->register($this->applicationFactory->createTestGetRequestRouter());
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritdoc
	 */
	public function registerBundleController(ApplicationController $applicationController) {
		$applicationController->registerBundleController($this->applicationFactory->createTestBundleController());
	}
}