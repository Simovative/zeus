<?php
namespace Simovative\Zeus\Http\Request;

use Simovative\Zeus\Content\Content;

/**
 * @author mnoerenberg
 */
interface HttpRequestDispatcherInterface {
	
	/**
	 * @author mnoerenberg
	 * @param HttpRequestInterface $request
	 * @return Content
	 */
	public function dispatch(HttpRequestInterface $request);
}
