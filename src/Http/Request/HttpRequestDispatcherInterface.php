<?php
namespace Simovative\Zeus\Http\Request;

use Psr\Http\Message\ResponseInterface;
use Simovative\Zeus\Http\Response\HttpResponseInterface;
use Simovative\Zeus\Http\Routing\RouteInterface;

interface HttpRequestDispatcherInterface {
	
	/**
	 * @param RouteInterface $route
	 * @return HttpResponseInterface|ResponseInterface
	 */
	public function dispatch(RouteInterface $route);
}
