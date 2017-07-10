<?php
namespace Simovative\Zeus\Command;

use Simovative\Zeus\Http\Post\HttpPostRequest;

/**
 * @author mnoerenberg
 */
interface CommandRouterInterface {
	
	/**
	 * @author mnoerenberg
	 * @param HttpPostRequest $request
	 * @return CommandBuilderInterface|NULL
	 */
	public function route(HttpPostRequest $request);
}
