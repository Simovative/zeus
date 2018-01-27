<?php
namespace Simovative\Skeleton\Application;

use Simovative\Skeleton\Demo\DemoBundle;
use Simovative\Zeus\Dependency\FrameworkFactory;
use Simovative\Zeus\Http\HttpKernel;
use Simovative\Zeus\Http\Request\HttpRequestInterface;

/**
 * @author Benedikt Schaller
 */
class SkeletonKernel extends HttpKernel {
	
	/**
	 * @author Benedikt Schaller
	 * @return FrameworkFactory|ApplicationFactory
	 */
	protected function getMasterFactory() {
		return parent::getMasterFactory();
	}
	
	/**
	 * @author mnoerenberg
	 * @inheritdoc
	 */
	protected function registerBundles(HttpRequestInterface $request) {
		$bundles = array();
		$bundles[] = new ApplicationBundle();
		$bundles[] = new DemoBundle();
		return $bundles;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritdoc
	 */
	protected function getApplicationState() {
		return $this->getMasterFactory()->createApplicationState();
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritdoc
	 */
	public function report($throwable, HttpRequestInterface $request = null) {
		parent::report($throwable);
		var_dump($throwable);
	}
}