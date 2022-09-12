<?php
namespace Simovative\Zeus\Bundle;

use Simovative\Zeus\Command\CommandRequestRouterChain;
use Simovative\Zeus\Http\Get\HttpGetRequestRouterChain;
use Simovative\Zeus\Command\ApplicationController;
use Simovative\Zeus\Dependency\MasterFactory;
use Simovative\Zeus\Dependency\FactoryInterface;

/**
 * @author mnoerenberg
 */
abstract class Bundle implements BundleInterface {
	
	/**
	 * @var FactoryInterface
	 */
	protected $bundleFactory;
	
	/**
	 * @author mnoerenberg
	 * @return FactoryInterface
	 */
	abstract protected function createBundleFactory();
	
	/**
	 * @author mnoerenberg
	 * @return FactoryInterface
	 */
	protected function getBundleFactory() {
		if (! $this->bundleFactory instanceof FactoryInterface) {
			$this->bundleFactory = $this->createBundleFactory();
		}
		return $this->bundleFactory;
	}
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 * @throws \Exception
	 */
	public function registerFactories(MasterFactory $masterFactory) {
		$masterFactory->register($this->getBundleFactory());
	}
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function registerGetRouters(HttpGetRequestRouterChain $router) {}

    public function registerOptionRouters(HttpGetRequestRouterChain $router): void {}

    /**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function registerPostRouters(CommandRequestRouterChain $router) {}
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function registerBundleController(ApplicationController $applicationController) {}
	
	/**
	 * @inheritdoc
	 * @author Benedikt Schaller
	 */
	public function registerPutRouters(CommandRequestRouterChain $router) {}
	
	/**
	 * @inheritdoc
	 * @author Benedikt Schaller
	 */
	public function registerPatchRouters(CommandRequestRouterChain $router) {}
	
	/**
	 * @inheritdoc
	 * @author Benedikt Schaller
	 */
	public function registerDeleteRouters(CommandRequestRouterChain $router) {}
	
	/**
	 * @inheritdoc
	 * @author Benedikt Schaller
	 */
	public function registerPostController(ApplicationController $applicationController) {}
	
	/**
	 * @inheritdoc
	 * @author Benedikt Schaller
	 */
	public function registerPatchController(ApplicationController $applicationController) {}
	
	/**
	 * @inheritdoc
	 * @author Benedikt Schaller
	 */
	public function registerPutController(ApplicationController $applicationController) {}
	
	/**
	 * @inheritdoc
	 * @author Benedikt Schaller
	 */
	public function registerDeleteController(ApplicationController $applicationController) {}
	
	/**
	 * @inheritDoc
	 * @author Benedikt Schaller
	 */
	public function registerHeadRouters(HttpGetRequestRouterChain $router) {}
}
