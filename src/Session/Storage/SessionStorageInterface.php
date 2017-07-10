<?php
namespace Simovative\Zeus\Session\Storage;

/**
 * @author mnoerenberg
 */
interface SessionStorageInterface {
	
	/**
	 * Returns all session values.
	 *
	 * @author Benedikt Schaller
	 * @return array|mixed[]
	 */
	public function all();
	
	/**
	 * Returns a session value by name.
	 *
	 * @author mnoerenberg
	 * @param string $name
	 * @return mixed
	 */
	public function get($name);
	
	/**
	 * Sets a session value.
	 *
	 * @author mnoerenberg
	 * @param string $name
	 * @param mixed $value
	 * @return void
	 */
	public function set($name, $value);
	
	/**
	 * Returns true if session value exists.
	 *
	 * @author mnoerenberg
	 * @param string $name
	 * @return boolean
	 */
	public function has($name);
	
	/**
	 * Removes a session value.
	 *
	 * @author mnoerenberg
	 * @param string $name
	 * @return void
	 */
	public function remove($name);
	
	/**
	 * Starts the session.
	 *
	 * @author mnoerenberg
	 * @return void
	 */
	public function start();
	
	/**
	 * Returns true if session is started.
	 *
	 * @author mnoerenberg
	 * @return boolean
	 */
	public function isStarted();
	
	/**
	 * Destroys the session.
	 *
	 * @author mnoerenberg
	 * @return void
	 */
	public function destroy();
}
