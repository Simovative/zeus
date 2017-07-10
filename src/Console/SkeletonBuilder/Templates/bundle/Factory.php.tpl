<?php
namespace {{appNamespace}}\{{bundleName}};

use {{appNamespace}}\{{bundleName}}\Page\HomePage;
use {{appNamespace}}\{{bundleName}}\Routing\{{bundleName}}BundleController;
use {{appNamespace}}\{{bundleName}}\Routing\{{bundleName}}GetRequestRouter;
use {{appNamespace}}\{{bundleName}}\Routing\{{bundleName}}CommandRouter;
use Simovative\Zeus\Dependency\Factory;
use Simovative\Zeus\Dependency\MasterFactory;
use Simovative\Zeus\Dependency\FrameworkFactory;

class {{bundleName}}Factory extends Factory {
	
	/**
	 * @return FrameworkFactory|MasterFactory|ApplicationFactory
	 */
	protected function getMasterFactory() {
		return parent::getMasterFactory();
	}
	
	/**
	 * @return {{bundleName}}GetRequestRouter
	 */
	public function create{{bundleName}}GetRequestRouter() {
		return new {{bundleName}}GetRequestRouter($this, $this->getMasterFactory()->createUrlMatcher());
	}
	
	/**
	 * @return {{bundleName}}CommandRouter
	 */
	public function create{{bundleName}}CommandRouter() {
		return new {{bundleName}}CommandRouter($this, $this->getMasterFactory()->createUrlMatcher());
	}
	
	/**
	 * @return {{bundleName}}BundleController
	 */
	public function create{{bundleName}}BundleController() {
		return new {{bundleName}}BundleController();
	}
	
	/**
	 * @return HomePage
	 */
	public function createHomePage() {
		return new HomePage(
			$this->getMasterFactory()->createTemplateEngine()
		);
	}
}