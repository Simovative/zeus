<?php
namespace Simovative\Zeus\Http\Get;

use Simovative\Zeus\Exception\IncompleteSetupException;
use Simovative\Zeus\Content\Content;
use Simovative\Zeus\Http\Request\HttpRequestInterface;

/**
 * @author mnoerenberg
 */
class HttpGetRequestRouterChain {
	
	/**
	 * @var HttpGetRequestRouterInterface[]
	 */
	private $routers = array();

	/**
	 *
	 * @author mnoerenberg
	 * @param HttpGetRequestRouterInterface $router
	 * @return void
	 */
	public function register(HttpGetRequestRouterInterface $router) {
		$this->routers[] = $router;
	}
	
	/**
	 * @author mnoerenberg
	 * @author shartmann
	 * @param HttpRequestInterface $request
	 * @throws IncompleteSetupException
	 * @return Content|null
	 */
	public function route(HttpRequestInterface $request) {
		foreach ($this->routers as $router) {
			$result = $router->route($request);
			if ($result !== null) {
				return $result;
			}
		}
		if (empty($this->routers)) {
			throw new IncompleteSetupException('No routers registred!');
		}
		return null;
	}
}
