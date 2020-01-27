<?php
namespace Simovative\Zeus\Http\Request;

use LogicException;
use Simovative\Zeus\Dependency\FrameworkFactory;
use Simovative\Zeus\Http\Get\HttpGetRequestDispatcherInterface;

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
	 * @return HttpGetRequestDispatcherInterface|HttpRequestDispatcherInterface
	 * @throws \Exception
	 */
	public function getDispatcherFor(HttpRequestInterface $request) {
		if ($request->isPost() || $request->isDelete() || $request->isPatch() || $request->isPut()) {
			return $this->frameworkFactory->createHttpCommandDispatcher();
		}
		
		if ($request->isGet() || $request->isHeader()) {
			return $this->frameworkFactory->createHttpGetRequestDispatcher();
		}
		
		throw new LogicException('request method not allowed.');
	}
}
