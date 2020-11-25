<?php
namespace Simovative\Zeus\Http\Get;

use Simovative\Zeus\Exception\IncompleteSetupException;
use Simovative\Zeus\Exception\RouteNotFoundException;
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
	 * @throws RouteNotFoundException
	 * @throws IncompleteSetupException
	 */
	public function dispatch(HttpRequestInterface $request);
}
