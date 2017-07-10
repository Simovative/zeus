<?php
namespace Simovative\Skeleton\Demo\Routing;

use Simovative\Skeleton\Demo\DemoFactory;
use Simovative\Zeus\Command\CommandRouterInterface;
use Simovative\Zeus\Http\Post\HttpPostRequest;
use Simovative\Zeus\Http\Url\UrlMatcher;

/**
 * @author Benedikt Schaller
 */
class DemoCommandRequestRouter implements CommandRouterInterface {
	
	/**
	 * @var DemoFactory
	 */
	private $demoFactory;
	/**
	 * @var UrlMatcher
	 */
	private $urlMatcher;
	
	
	/**
	 * @author Benedikt Schaller
	 * @param DemoFactory $demoFactory
	 * @param UrlMatcher $urlMatcher
	 */
	public function __construct(DemoFactory $demoFactory, UrlMatcher $urlMatcher) {
		$this->demoFactory = $demoFactory;
		$this->urlMatcher = $urlMatcher;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritdoc
	 */
	public function route(HttpPostRequest $request) {
		if ($this->urlMatcher->match($request->getUrl(), '/demo/login')) {
			return $this->demoFactory->createDemoLoginCommandBuilder();
		}
		if ($this->urlMatcher->match($request->getUrl(), '/demo/logout')) {
			return $this->demoFactory->createDemoLogoutCommandBuilder();
		}
		return null;
	}
}