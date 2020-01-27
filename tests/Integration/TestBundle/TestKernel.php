<?php
namespace Simovative\Test\Integration\TestBundle;

use Simovative\Zeus\Dependency\FrameworkFactory;
use Simovative\Zeus\Http\HttpKernel;
use Simovative\Zeus\Http\Request\HttpRequestInterface;

/**
 * @author Benedikt Schaller
 */
class TestKernel extends HttpKernel {
	
	/**
	 * @author Benedikt Schaller
	 * @return FrameworkFactory|ApplicationFactory
	 */
	protected function getMasterFactory() {
		return parent::getMasterFactory();
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritdoc
	 */
	protected function registerBundles(HttpRequestInterface $request) {
		$bundles = array();
		$bundles[] = new ApplicationBundle();
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
	}
}