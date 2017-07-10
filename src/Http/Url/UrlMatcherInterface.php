<?php
namespace Simovative\Zeus\Http\Url;

/**
 * @author mnoerenberg
 */
interface UrlMatcherInterface {
	
	/**
	 * Returns a component by position.
	 *
	 * @author mnoerenberg
	 * @param int $position
	 * @throws \LogicException
	 * @return string
	 */
	public function getComponent($position);
	
	/**
	 * Returns true if position exists.
	 *
	 * @author mnoerenberg
	 * @param int $position
	 * @return boolean
	 */
	public function hasComponent($position);
	
	/**
	 * Returns true if route is matched.
	 *
	 * @author mnoerenberg
	 * @param Url $url
	 * @param string $route
	 * @return bool
	 */
	public function match(Url $url, $route);
	
	/**
	 * Returns true if any of the given routes is matched.
	 *
	 * @author Benedikt Schaller
	 * @param Url $url
	 * @param string[] $routes
	 * @return bool
	 */
	public function matchAny(Url $url, $routes);
}
