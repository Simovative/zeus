<?php
namespace Simovative\Zeus\Command;

use Simovative\Zeus\Exception\RouteNotFoundException;
use Simovative\Zeus\Http\Request\HttpRequest;

/**
 * @author mnoerenberg
 */
class CommandRequestRouterChain  {
	
	/**
	 * @var CommandRouterInterface[]
	 */
	private $routers = array();

	/**
	 *
	 * @author mnoerenberg
	 * @param CommandRouterInterface $router
	 * @return void
	 */
	public function register(CommandRouterInterface $router) {
		$this->routers[] = $router;
	}
	
	/**
	 * @author mnoerenberg
	 * @param HttpRequest $request
	 * @throws RouteNotFoundException
	 * @return CommandBuilderInterface|NULL
	 */
	public function route(HttpRequest $request) {
		foreach ($this->routers as $router) {
			$result = $router->route($request);
			if ($result !== null) {
				return $result;
			}
		}
		
		throw new RouteNotFoundException($request->getUrl());
	}
}
