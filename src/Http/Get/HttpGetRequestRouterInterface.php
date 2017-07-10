<?php
namespace Simovative\Zeus\Http\Get;

use Simovative\Zeus\Content\Content;

/**
 * @author mnoerenberg
 */
interface HttpGetRequestRouterInterface {
	
	/**
	 * @author mnoerenberg
	 * @param HttpGetRequest $request
	 * @return Content|NULL
	 */
	public function route(HttpGetRequest $request);
}
