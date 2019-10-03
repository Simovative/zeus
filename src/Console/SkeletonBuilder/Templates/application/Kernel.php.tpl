<?php
namespace {{namespace}}\Application;

use Simovative\Zeus\Dependency\FrameworkFactory;
use Simovative\Zeus\Exception\IncompleteSetupException;
use Simovative\Zeus\Http\HttpKernel;
use Simovative\Zeus\Http\Request\HttpRequestInterface;

class {{prefix}}Kernel extends HttpKernel {
	
	/**
	 * @return FrameworkFactory|{{prefix}}ApplicationFactory
	 */
	protected function getMasterFactory() {
		return parent::getMasterFactory();
	}
	
	/**
	 * @inheritdoc
	 */
	protected function registerBundles() {
		$bundles = array();
		$bundles[] = new {{appPrefix}}Bundle();
		return $bundles;
	}
	
	/**
	 * @inheritdoc
	 */
	protected function getApplicationState() {
		return $this->getMasterFactory()->createApplicationState();
	}
	
	/**
	 * @inheritdoc
	 */
	public function report($throwable, HttpRequestInterface $request = null) {
		parent::report($throwable);
		if (
			$throwable instanceof IncompleteSetupException
			&& is_readable(__DIR__ . '/setup.html')
		) {
			include __DIR__ . '/setup.html';
			die();
		} else {
			var_dump($throwable);
		}
	}
}