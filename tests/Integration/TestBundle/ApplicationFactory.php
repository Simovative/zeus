<?php
namespace Simovative\Test\Integration\TestBundle;

use Simovative\Test\Integration\TestBundle\Command\TestCommandBuilder;
use Simovative\Test\Integration\TestBundle\Command\TestCommandHandler;
use Simovative\Test\Integration\TestBundle\Command\TestCommandValidator;
use Simovative\Test\Integration\TestBundle\Page\TestPage;
use Simovative\Test\Integration\TestBundle\Routing\TestBundleController;
use Simovative\Test\Integration\TestBundle\Routing\TestCommandRequestRouter;
use Simovative\Test\Integration\TestBundle\Routing\TestGetRequestRouter;
use Simovative\Zeus\Dependency\Factory;
use Simovative\Zeus\Exception\FilesystemException;
use Simovative\Zeus\Http\Response\HttpResponseLocatorInterface;
use Simovative\Zeus\Http\Url\UrlMatcher;
use Simovative\Zeus\Session\Session;
use Simovative\Zeus\Session\Storage\SessionStorageInterface;
use Simovative\Zeus\Template\TemplateEngineInterface;

/**
 * @author Benedikt Schaller
 */
class ApplicationFactory extends Factory {
	
	const URL_PREFIX = '';
	
	/**
	 * @author Benedikt Schaller
	 * @return ApplicationState
	 */
	public function createApplicationState() {
		return new ApplicationState(
			$this->createTestSession()
		);
	}
	
	/**
	 * @return Session
	 * @author Benedikt Schaller
	 */
	private function createTestSession() {
		return new Session(
			$this->createTestSessionStorage()
		);
	}
	
	/**
	 * @return SessionStorageInterface
	 * @author Benedikt Schaller
	 */
	private function createTestSessionStorage() {
		return new TestSessionStorage(['username' => 'testUser']);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return HttpResponseLocatorInterface
	 */
	public function createHttpResponseLocator() {
		return new TestResponseLocator();
	}
	
	/**
	 * Allow Application Factory to overwrite various Framework Methods
	 *
	 * @author shartmann
	 * @return bool
	 */
	public function isOverwriteAllowed() {
		return true;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return UrlMatcher
	 */
	public function createUrlMatcher() {
		return new UrlMatcher(self::URL_PREFIX);
	}
	
	/**
	 * @return TemplateEngineInterface
	 * @throws FilesystemException
	 * @author Benedikt Schaller
	 */
	public function createTestTemplateEngine() {
		$templateEngine = $this->getMasterFactory()->createSmartyTemplateEngine();
		$templateEngine->assign('url_prefix', self::URL_PREFIX);
		return $templateEngine;
	}
	
	/**
	 * @return TestPage
	 * @throws FilesystemException
	 * @author Benedikt Schaller
	 */
	public function createTestPage() {
		return new TestPage(
			$this->getMasterFactory()->createBootstrapFormPopulation(),
			$this->createTestTemplateEngine(),
			$this->createApplicationState()->getUsername()
		);
	}
	
	/**
	 * @return TestCommandHandler
	 * @author Benedikt Schaller
	 */
	public function createTestCommandHandler() {
		return new TestCommandHandler($this->createApplicationState());
	}
	
	/**
	 * @return TestCommandBuilder
	 * @author Benedikt Schaller
	 */
	public function createTestCommandBuilder() {
		return new TestCommandBuilder(
			$this->createTestCommandValidator()
		);
	}
	
	/**
	 * @return TestCommandValidator
	 * @author Benedikt Schaller
	 */
	private function createTestCommandValidator() {
		return new TestCommandValidator();
	}
	
	/**
	 * @return TestGetRequestRouter
	 * @author Benedikt Schaller
	 */
	public function createTestGetRequestRouter() {
		return new TestGetRequestRouter($this, $this->createUrlMatcher(), $this->createApplicationState());
	}
	
	/**
	 * @return TestCommandRequestRouter
	 * @author Benedikt Schaller
	 */
	public function createTestPostRequestRouter() {
		return new TestCommandRequestRouter($this, $this->createUrlMatcher());
	}
	
	/**
	 * @return TestBundleController
	 * @author Benedikt Schaller
	 */
	public function createTestBundleController() {
		return new TestBundleController();
	}
}