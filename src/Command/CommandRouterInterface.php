<?php
namespace Simovative\Zeus\Command;

use Simovative\Zeus\Http\Request\HttpRequestInterface;

/**
 * @author mnoerenberg
 */
interface CommandRouterInterface {
	
	/**
	 * @author mnoerenberg
	 * @param HttpRequestInterface $request
	 * @return CommandBuilderInterface|NULL
	 */
	public function route(HttpRequestInterface $request);
}
