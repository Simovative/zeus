<?php
namespace Simovative\Zeus\Http\Get;

use Simovative\Zeus\Exception\IncompleteSetupException;
use Simovative\Zeus\Exception\RouteNotFoundException;
use Simovative\Zeus\Http\Request\HttpRequestInterface;

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
	 * @inheritDoc
	 * @author mnoerenberg
     * @throws RouteNotFoundException
     * @throws IncompleteSetupException
	 */
	public function dispatch(HttpRequestInterface $request) {
		return $this->router->route($request);
	}
}
