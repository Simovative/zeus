<?php
namespace Simovative\Zeus\Http\Request;

use Exception;
use Simovative\Zeus\Content\Content;
use Simovative\Zeus\Exception\RouteNotFoundException;

/**
 * @author mnoerenberg
 */
interface HttpRequestDispatcherInterface {
	
	/**
	 * @author mnoerenberg
	 * @param HttpRequestInterface $request
	 * @return Content
	 * @throws RouteNotFoundException
	 * @throws Exception
	 */
	public function dispatch(HttpRequestInterface $request);
}
