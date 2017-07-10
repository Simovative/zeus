<?php
namespace Simovative\Zeus\Http\Post;

use Simovative\Zeus\Content\Content;
use Simovative\Zeus\Http\Request\HttpRequestInterface;

/**
 * @author mnoerenberg
 */
interface HttpPostRequestDispatcherInterface {
	
	/**
	 * @author mnoerenberg
	 * @param HttpPostRequest|HttpRequestInterface $request
	 * @return Content
	 */
	public function dispatch(HttpPostRequest $request);
}
