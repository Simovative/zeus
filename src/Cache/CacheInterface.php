<?php
namespace Simovative\Zeus\Cache;

/**
 * @author mnoerenberg
 */
interface CacheInterface {
	
	/**
	 * Sets a value to the cache.
	 *
	 * @author mnoerenberg
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	public function set($key, $value);
	
	/**
	 * Returns a value from the cache, or NULL if not cached.
	 *
	 * @author mnoerenberg
	 * @param string $key
	 * @return mixed
	 */
	public function get($key);
	
	/**
	 * Checks if the variable with the given key is already cached.
	 *
	 * @author mnoerenberg
	 * @param string $key
	 * @return boolean
	 */
	public function exists($key);
	
	/**
	 * Removes a variable from the cache.
	 *
	 * @author mnoerenberg
	 * @param string $key
	 * @return boolean
	 */
	public function remove($key);
}
