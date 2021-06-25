<?php
namespace Simovative\Skeleton\Demo\Routing;

use Simovative\Skeleton\Application\ApplicationFactory;
use Simovative\Skeleton\Application\ApplicationState;
use Simovative\Skeleton\Demo\DemoFactory;
use Simovative\Zeus\Content\Redirect;
use Simovative\Zeus\Http\Get\HttpGetRequestRouterInterface;
use Simovative\Zeus\Http\Request\HttpRequestInterface;
use Simovative\Zeus\Http\Url\Url;
use Simovative\Zeus\Http\Url\UrlMatcher;

/**
 * @author Benedikt Schaller
 */
class DemoGetRequestRouter implements HttpGetRequestRouterInterface {
	
	/**
	 * @var DemoFactory
	 */
	private $demoFactory;
	/**
	 * @var UrlMatcher
	 */
	private $urlMatcher;
	/**
	 * @var ApplicationState
	 */
	private $state;
	
	/**
	 * @author Benedikt Schaller
	 * @param DemoFactory $demoFactory
	 * @param UrlMatcher $urlMatcher
	 * @param ApplicationState $state
	 */
	public function __construct(DemoFactory $demoFactory, UrlMatcher $urlMatcher, ApplicationState $state) {
		$this->demoFactory = $demoFactory;
		$this->urlMatcher = $urlMatcher;
		$this->state = $state;
	}
	
	/**
	 * @inheritdoc
	 * @author Benedikt Schaller
	 */
	public function route(HttpRequestInterface $request) {
		if ($this->urlMatcher->matchAny($request->getUrl(), array('/', '', '/demo/login'))) {
			return $this->demoFactory->createDemoLoginPage();
		}
		
		// Login wall starts here
		if (! $this->state->isLoggedIn()) {
			return new Redirect(new Url(ApplicationFactory::URL_PREFIX . '/demo/login'));
		}
		
		// Internal pages
		if ($this->urlMatcher->match($request->getUrl(), '/demo/home')) {
			return $this->demoFactory->createDemoHomePage($request->get('username'));
		}
		return null;
	}
}
