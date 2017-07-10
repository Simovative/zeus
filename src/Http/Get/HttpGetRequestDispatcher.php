<?php
namespace Simovative\Zeus\Http\Get;

/**
 * @author mnoerenberg
 */
class HttpGetRequestDispatcher implements HttpGetRequestDispatcherInterface {
	
	/**
	 * @var HttpGetRequestRouterChain
	 */
	private $router;
	
	/**
	 * @author mnoerenberg
	 * @param HttpGetRequestRouterChain $router
	 */
	public function __construct(HttpGetRequestRouterChain $router) {
		$this->router = $router;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Simovative\Zeus\Http\Request\HttpGetRequestDispatcherInterface::dispatch()
	 * @author mnoerenberg
	 */
	public function dispatch(HttpGetRequest $request) {
		return $this->router->route($request);
	}
}
