<?php
namespace Simovative\Zeus\Http\Request;

use Simovative\Zeus\Http\Routing\RouteInterface;

/**
 * @author mnoerenberg
 */
interface HttpRequestDispatcherLocatorInterface {

	public function getDispatcherFor(RouteInterface $route): HttpRequestDispatcherInterface;
}
