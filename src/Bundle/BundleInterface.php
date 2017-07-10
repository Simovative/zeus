<?php
namespace Simovative\Zeus\Bundle;

use Simovative\Zeus\Command\CommandRequestRouterChain;
use Simovative\Zeus\Http\Get\HttpGetRequestRouterChain;
use Simovative\Zeus\Command\ApplicationController;
use Simovative\Zeus\Dependency\MasterFactory;

/**
 * @author mnoerenberg
 */
interface BundleInterface {
	
	/**
	 * Can be used to register factories at the master factory.
	 *
	 * @author mnoerenberg
	 * @param MasterFactory $masterFactory
	 * @return void
	 */
	public function registerFactories(MasterFactory $masterFactory);
	
	/**
	 * Register modules get routers.
	 *
	 * @author mnoerenberg
	 * @param HttpGetRequestRouterChain $router
	 * @return void
	*/
	public function registerGetRouters(HttpGetRequestRouterChain $router);
	
	/**
	 * Register bundles post routers.
	 *
	 * @author mnoerenberg
	 * @param CommandRequestRouterChain $router
	 * @return void
	*/
	public function registerPostRouters(CommandRequestRouterChain $router);
	
	/**
	 * Method to register bundle controller at the main application controller.
	 *
	 * @author mnoerenberg
	 * @param ApplicationController $applicationController
	 * @return void
	*/
	public function registerBundleController(ApplicationController $applicationController);
}
