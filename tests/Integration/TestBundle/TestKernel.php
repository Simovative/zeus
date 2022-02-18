<?php
namespace Simovative\Test\Integration\TestBundle;

use Simovative\Zeus\Dependency\FrameworkFactory;
use Simovative\Zeus\Http\HttpKernel;
use Simovative\Zeus\Http\Request\HttpRequestInterface;
use Simovative\Zeus\State\ApplicationStateInterface;

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

	protected function registerBundles(HttpRequestInterface $request) {
		$bundles = array();
		$bundles[] = new ApplicationBundle();
		return $bundles;
	}

	protected function getApplicationState(): ?ApplicationStateInterface
    {
		return $this->getMasterFactory()->createApplicationState();
	}

	public function report($throwable, HttpRequestInterface $request = null) {
		parent::report($throwable);
	}
}
