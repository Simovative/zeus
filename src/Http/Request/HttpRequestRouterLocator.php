<?php
namespace Simovative\Zeus\Http\Request;

use Simovative\Zeus\Command\CommandRequestRouterChain;
use Simovative\Zeus\Dependency\FrameworkFactory;
use Simovative\Zeus\Exception\ApplicationException;
use Simovative\Zeus\Http\Get\HttpGetRequestRouterChain;

/**
 * @author mnoerenberg
 */
class HttpRequestRouterLocator {
	
	/**
	 * @var FrameworkFactory
	 */
	private $frameworkFactory;
	
	/**
	 * @author mnoerenberg
	 * @param FrameworkFactory $frameworkFactory
	 */
	public function __construct(FrameworkFactory $frameworkFactory) {
		$this->frameworkFactory = $frameworkFactory;
	}
	
	/**
	 * @author mnoerenberg
	 * @param HttpRequestInterface $request
	 * @throws ApplicationException
	 * @return HttpGetRequestRouterChain|CommandRequestRouterChain
	 */
	public function getDispatcherFor(HttpRequestInterface $request) {
		if ($request->isGet()) {
			return $this->frameworkFactory->getHttpGetRequestRouterChain();
		}
		
		if ($request->isPost()) {
			return $this->frameworkFactory->getCommandRequestRouterChain();
		}
		
		throw new \LogicException('request method not allowed.');
	}
}
