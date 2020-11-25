<?php
declare(strict_types=1);

namespace Simovative\Test\Integration\TestBundle;

use Simovative\Test\Integration\TestBundle\Command\TestCommandBuilder;
use Simovative\Test\Integration\TestBundle\Command\TestCommandHandler;
use Simovative\Test\Integration\TestBundle\Command\TestCommandValidator;
use Simovative\Test\Integration\TestBundle\Handler\TestHandler;
use Simovative\Test\Integration\TestBundle\Page\TestPage;
use Simovative\Test\Integration\TestBundle\Routing\TestHandlerRequestRouter;
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
class ApplicationFactory extends Factory
{

    public const URL_PREFIX = '';

    /**
     * @author Benedikt Schaller
     * @return ApplicationState
     */
    public function createApplicationState(): ApplicationState
    {
        return new ApplicationState(
            $this->createTestSession()
        );
    }

    /**
     * @author Benedikt Schaller
     * @return Session
     */
    private function createTestSession(): Session
    {
        return new Session(
            $this->createTestSessionStorage()
        );
    }

    /**
     * @author Benedikt Schaller
     * @return SessionStorageInterface
     */
    private function createTestSessionStorage()
    {
        return new TestSessionStorage(['username' => 'testUser']);
    }

    /**
     * @author Benedikt Schaller
     * @return HttpResponseLocatorInterface
     */
    public function createHttpResponseLocator()
    {
        return new TestResponseLocator();
    }

    /**
     * Allow Application Factory to overwrite various Framework Methods
     *
     * @author shartmann
     * @return bool
     */
    public function isOverwriteAllowed(): bool
    {
        return true;
    }

    /**
     * @author Benedikt Schaller
     * @return UrlMatcher
     */
    public function createUrlMatcher(): UrlMatcher
    {
        return new UrlMatcher(self::URL_PREFIX);
    }

    /**
     * @author Benedikt Schaller
     * @return TemplateEngineInterface
     * @throws FilesystemException
     */
    public function createTestTemplateEngine(): TemplateEngineInterface
    {
        $templateEngine = $this->getMasterFactory()->createSmartyTemplateEngine();
        $templateEngine->assign('url_prefix', self::URL_PREFIX);
        return $templateEngine;
    }

    /**
     * @author Benedikt Schaller
     * @return TestPage
     * @throws FilesystemException
     */
    public function createTestPage(): TestPage
    {
        return new TestPage(
            $this->getMasterFactory()->createBootstrapFormPopulation(),
            $this->createTestTemplateEngine(),
            $this->createApplicationState()->getUsername()
        );
    }

    /**
     * @author Benedikt Schaller
     * @return TestCommandHandler
     */
    public function createTestCommandHandler(): TestCommandHandler
    {
        return new TestCommandHandler($this->createApplicationState());
    }

    /**
     * @author Benedikt Schaller
     * @return TestCommandBuilder
     */
    public function createTestCommandBuilder(): TestCommandBuilder
    {
        return new TestCommandBuilder(
            $this->createTestCommandValidator()
        );
    }

    /**
     * @author Benedikt Schaller
     * @return TestCommandValidator
     */
    private function createTestCommandValidator(): TestCommandValidator
    {
        return new TestCommandValidator();
    }

    /**
     * @author Benedikt Schaller
     * @return TestGetRequestRouter
     */
    public function createTestGetRequestRouter(): TestGetRequestRouter
    {
        return new TestGetRequestRouter($this, $this->createUrlMatcher(), $this->createApplicationState());
    }

    /**
     * @author Benedikt Schaller
     * @return TestCommandRequestRouter
     */
    public function createTestPostRequestRouter(): TestCommandRequestRouter
    {
        return new TestCommandRequestRouter($this, $this->createUrlMatcher());
    }

    /**
     * @author Benedikt Schaller
     * @return TestBundleController
     */
    public function createTestBundleController(): TestBundleController
    {
        return new TestBundleController();
    }

    /**
     * @author Benedikt Schaller
     * @return TestHandler
     */
    public function createTestHandler(): TestHandler
    {
        return new TestHandler();
    }

    /**
     * @author Benedikt Schaller
     * @return TestHandlerRequestRouter
     */
    public function createTestHandlerRequestRouter(): TestHandlerRequestRouter
    {
        return new TestHandlerRequestRouter($this, $this->createUrlMatcher());
    }
}