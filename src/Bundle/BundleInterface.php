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
	 * Register bundles header routers.
	 *
	 * @author Benedikt Schaller
	 * @param HttpGetRequestRouterChain $router
	 * @return void
	 */
	public function registerHeaderRouters(HttpGetRequestRouterChain $router);
	
	/**
	 * Method to register bundle controller at the main application controller.
	 * Can be used for all post, put, patch and delete methods.
	 *
	 * @author mnoerenberg
	 * @param ApplicationController $applicationController
	 * @return void
	*/
	public function registerBundleController(ApplicationController $applicationController);
	
	/**
	 * Register bundles put routers.
	 *
	 * @author Benedikt Schaller
	 * @param CommandRequestRouterChain $router
	 * @return void
	 */
	public function registerPutRouters(CommandRequestRouterChain $router);
	
	/**
	 * Register bundles patch routers.
	 *
	 * @author Benedikt Schaller
	 * @param CommandRequestRouterChain $router
	 * @return void
	 */
	public function registerPatchRouters(CommandRequestRouterChain $router);
	
	/**
	 * Register bundles delete routers.
	 *
	 * @author Benedikt Schaller
	 * @param CommandRequestRouterChain $router
	 * @return void
	 */
	public function registerDeleteRouters(CommandRequestRouterChain $router);
	
	/**
	 * @author Benedikt Schaller
	 * @param ApplicationController $applicationController
	 * @return void
	 */
	public function registerPostController(ApplicationController $applicationController);
	
	/**
	 * @author Benedikt Schaller
	 * @param ApplicationController $applicationController
	 * @return void
	 */
	public function registerPatchController(ApplicationController $applicationController);
	
	/**
	 * @author Benedikt Schaller
	 * @param ApplicationController $applicationController
	 * @return void
	 */
	public function registerPutController(ApplicationController $applicationController);
	
	/**
	 * @author Benedikt Schaller
	 * @param ApplicationController $applicationController
	 * @return void
	 */
	public function registerDeleteController(ApplicationController $applicationController);
}
