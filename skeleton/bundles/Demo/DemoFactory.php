<?php
namespace Simovative\Skeleton\Demo;

use Simovative\Skeleton\Application\ApplicationFactory;
use Simovative\Skeleton\Demo\Command\LoginCommandBuilder;
use Simovative\Skeleton\Demo\Command\LoginCommandHandler;
use Simovative\Skeleton\Demo\Command\LoginCommandValidator;
use Simovative\Skeleton\Demo\Command\LogoutCommand;
use Simovative\Skeleton\Demo\Page\HomePage;
use Simovative\Skeleton\Demo\Page\LoginPage;
use Simovative\Skeleton\Demo\Routing\DemoBundleController;
use Simovative\Skeleton\Demo\Routing\DemoGetRequestRouter;
use Simovative\Skeleton\Demo\Routing\DemoCommandRequestRouter;
use Simovative\Zeus\Dependency\Factory;
use Simovative\Zeus\Dependency\MasterFactory;
use Simovative\Zeus\Dependency\FrameworkFactory;

/**
 * @author Benedikt Schaller
 */
class DemoFactory extends Factory {
	
	/**
	 * @author Benedikt Schaller
	 * @return FrameworkFactory|MasterFactory|ApplicationFactory
	 */
	protected function getMasterFactory() {
		return parent::getMasterFactory();
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return DemoGetRequestRouter
	 */
	public function createDemoGetRequestRouter() {
		return new DemoGetRequestRouter($this, $this->getMasterFactory()->createUrlMatcher(), $this->getMasterFactory()->createApplicationState());
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return DemoCommandRequestRouter
	 */
	public function createDemoPostRequestRouter() {
		return new DemoCommandRequestRouter($this, $this->getMasterFactory()->createUrlMatcher());
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return DemoBundleController
	 */
	public function createDemoBundleController() {
		return new DemoBundleController();
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return LoginPage
	 */
	public function createDemoLoginPage() {
		return new LoginPage(
			$this->getMasterFactory()->createBootstrapFormPopulation(),
			$this->getMasterFactory()->createDemoTemplateEngine()
		);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return LoginCommandBuilder
	 */
	public function createDemoLoginCommandBuilder() {
		return new LoginCommandBuilder(
			$this->createDemoLoginCommandValidator()
		);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return LoginCommandValidator
	 */
	private function createDemoLoginCommandValidator() {
		return new LoginCommandValidator($this->getMasterFactory()->getTranslator());
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return LoginCommandHandler
	 */
	public function createDemoLoginCommandHandler() {
		return new LoginCommandHandler($this->getMasterFactory()->createApplicationState());
	}
	
	/**
	 * @author Benedikt Schaller
	 * @param string $username
	 * @return HomePage
	 */
	public function createDemoHomePage($username) {
		return new HomePage(
			$this->getMasterFactory()->createDemoTemplateEngine(),
			$username
		);
	}
	
	/**
	 * @author shartmann
	 * @return LogoutCommand
	 */
	public function createDemoLogoutCommand(): LogoutCommand {
		return new LogoutCommand($this->getMasterFactory()->createApplicationState());
	}

}