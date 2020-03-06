<?php
namespace Simovative\Zeus\Http\Get;

use Simovative\Zeus\Content\Content;
use Simovative\Zeus\Http\Request\HttpRequestInterface;

/**
 * @author mnoerenberg
 */
interface HttpGetRequestRouterInterface {
	
	/**
	 * @author mnoerenberg
	 * @param HttpRequestInterface $request
	 * @return Content|NULL
	 */
	public function route(HttpRequestInterface $request);
}
