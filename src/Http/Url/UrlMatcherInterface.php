<?php
namespace Simovative\Zeus\Http\Url;

/**
 * @author mnoerenberg
 */
interface UrlMatcherInterface {
	/**
	 * Returns true if route is matched.
	 *
	 * @author mnoerenberg
	 * @author shartmann
	 * @param Url $url
	 * @param string $route
	 * @param bool $matchPath if set to true the server name is ignored
	 * @return bool
	 */
	public function match(Url $url, $route, $matchPath);
	
	/**
	 * Returns true if any of the given routes is matched.
	 *
	 * @author Benedikt Schaller
	 * @author shartmann
	 * @param Url $url
	 * @param string[] $routes
	 * @param bool $matchPath if set to true the server name is ignored
	 * @return bool
	 */
	public function matchAny(Url $url, $routes, $matchPath = true);
}
