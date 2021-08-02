<?php
namespace Simovative\Zeus\Command;

use Simovative\Zeus\Http\Request\HttpRequestInterface;

/**
 * @author mnoerenberg
 */
class CommandRequestRouterChain {
	
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
	 * @param HttpRequestInterface $request
	 * @return CommandBuilderInterface|NULL
	 */
	public function route(HttpRequestInterface $request) {
		foreach ($this->routers as $router) {
			$result = $router->route($request);
			if ($result !== null) {
				return $result;
			}
		}
		
		return null;
	}
}
