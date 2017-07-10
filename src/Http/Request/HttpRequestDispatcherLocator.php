<?php
namespace Simovative\Zeus\Http\Request;

use Simovative\Zeus\Dependency\FrameworkFactory;
use Simovative\Zeus\Http\Get\HttpGetRequestDispatcherInterface;
use Simovative\Zeus\Http\Post\HttpPostRequestDispatcherInterface;

/**
 * @author mnoerenberg
 */
class HttpRequestDispatcherLocator {
	
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
	 * @throws \Exception
	 * @return HttpGetRequestDispatcherInterface|HttpPostRequestDispatcherInterface
	 */
	public function getDispatcherFor(HttpRequestInterface $request) {
		if ($request->isPost()) {
			return $this->frameworkFactory->createHttpPostRequestDispatcher();
		}
		
		if ($request->isGet()) {
			return $this->frameworkFactory->createHttpGetRequestDispatcher();
		}
		
		throw new \LogicException('request method not allowed.');
	}
}
