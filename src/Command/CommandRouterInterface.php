<?php
namespace Simovative\Zeus\Command;

use Simovative\Zeus\Http\Request\HttpRequest;

/**
 * @author mnoerenberg
 */
interface CommandRouterInterface {
	
	/**
	 * @author mnoerenberg
	 * @param HttpRequest $request
	 * @return CommandBuilderInterface|NULL
	 */
	public function route(HttpRequest $request);
}
