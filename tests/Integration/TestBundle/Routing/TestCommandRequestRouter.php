<?php
namespace Simovative\Test\Integration\TestBundle\Routing;

use Simovative\Test\Integration\TestBundle\ApplicationFactory;
use Simovative\Zeus\Command\CommandRouterInterface;
use Simovative\Zeus\Http\Request\HttpRequestInterface;
use Simovative\Zeus\Http\Url\UrlMatcherInterface;

/**
 * @author Benedikt Schaller
 */
class TestCommandRequestRouter implements CommandRouterInterface {
	
	/**
	 * @var UrlMatcherInterface
	 */
	private $urlMatcher;
	
	/**
	 * @var ApplicationFactory
	 */
	private $applicationFactory;
	
	/**
	 * @author Benedikt Schaller
	 * @param ApplicationFactory $applicationFactory
	 * @param UrlMatcherInterface $urlMatcher
	 */
	public function __construct(ApplicationFactory $applicationFactory, UrlMatcherInterface $urlMatcher) {
		$this->urlMatcher = $urlMatcher;
		$this->applicationFactory = $applicationFactory;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritdoc
	 */
	public function route(HttpRequestInterface $request) {
		if ($this->urlMatcher->match($request->getUrl(), '/test')) {
			return $this->applicationFactory->createTestCommandBuilder();
		}
		return null;
	}
}
