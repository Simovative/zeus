<?php
namespace Simovative\Skeleton\Demo\Routing;

use Simovative\Skeleton\Demo\DemoFactory;
use Simovative\Zeus\Command\CommandRouterInterface;
use Simovative\Zeus\Http\Request\HttpRequestInterface;
use Simovative\Zeus\Http\Url\UrlMatcherInterface;

/**
 * @author Benedikt Schaller
 */
class DemoCommandRequestRouter implements CommandRouterInterface {
	
	/**
	 * @var DemoFactory
	 */
	private $demoFactory;
	
	/**
	 * @var UrlMatcherInterface
	 */
	private $urlMatcher;
	
	/**
	 * @author Benedikt Schaller
	 * @param DemoFactory $demoFactory
	 * @param UrlMatcherInterface $urlMatcher
	 */
	public function __construct(DemoFactory $demoFactory, UrlMatcherInterface $urlMatcher) {
		$this->demoFactory = $demoFactory;
		$this->urlMatcher = $urlMatcher;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritdoc
	 */
	public function route(HttpRequestInterface $request) {
		if ($this->urlMatcher->match($request->getUrl(), '/demo/login')) {
			return $this->demoFactory->createDemoLoginCommandBuilder();
		}
		if ($this->urlMatcher->match($request->getUrl(), '/demo/logout')) {
			return $this->demoFactory->createDemoLogoutCommand();
		}
		return null;
	}
}
