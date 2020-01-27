<?php
namespace Simovative\Zeus\Http\Get;

use Simovative\Zeus\Http\Request\HttpRequestInterface;
use Simovative\Zeus\Http\Response\HttpResponseInterface;

/**
 * @author mnoerenberg
 */
interface HttpGetRequestDispatcherInterface {
	
	/**
	 * @author mnoerenberg
	 * @param HttpRequestInterface $request
	 * @return HttpResponseInterface
	 */
	public function dispatch(HttpRequestInterface $request);
}
