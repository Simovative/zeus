<?php
namespace Simovative\Test\Integration\TestBundle\Routing;

use Simovative\Test\Integration\TestBundle\ApplicationFactory;
use Simovative\Test\Integration\TestBundle\ApplicationState;
use Simovative\Zeus\Content\Redirect;
use Simovative\Zeus\Exception\FilesystemException;
use Simovative\Zeus\Http\Get\HttpGetRequestRouterInterface;
use Simovative\Zeus\Http\Request\HttpRequestInterface;
use Simovative\Zeus\Http\Url\Url;
use Simovative\Zeus\Http\Url\UrlMatcher;

/**
 * @author Benedikt Schaller
 */
class TestGetRequestRouter implements HttpGetRequestRouterInterface {
	
	/**
	 * @var UrlMatcher
	 */
	private $urlMatcher;
	/**
	 * @var ApplicationState
	 */
	private $state;
	/**
	 * @var ApplicationFactory
	 */
	private $applicationFactory;
	
	/**
	 * @param ApplicationFactory $applicationFactory
	 * @param UrlMatcher $urlMatcher
	 * @param ApplicationState $state
	 * @author Benedikt Schaller
	 */
	public function __construct(ApplicationFactory $applicationFactory, UrlMatcher $urlMatcher, ApplicationState $state) {
		$this->applicationFactory = $applicationFactory;
		$this->urlMatcher = $urlMatcher;
		$this->state = $state;
	}
	
	/**
	 * @inheritdoc
	 * @author Benedikt Schaller
	 * @throws FilesystemException
	 */
	public function route(HttpRequestInterface $request) {
		if ($this->urlMatcher->matchAny($request->getUrl(), array('/', '', '/test'))) {
			return $this->applicationFactory->createTestPage();
		}

		if (! $this->state->isLoggedIn()) {
			return new Redirect(new Url(ApplicationFactory::URL_PREFIX . '/test'));
		}
		return null;
	}
}