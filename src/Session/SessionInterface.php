<?php
namespace Simovative\Zeus\Session;

/**
 * @author mnoerenberg
 */
interface SessionInterface {
	
	/**
	 * @author mnoerenberg
	 * @param string $name
	 * @return mixed
	 */
	public function get($name);
	
	/**
	 * @author mnoerenberg
	 * @return string[]
	 */
	public function all();
	
	/**
	 * @author mnoerenberg
	 * @param string $name
	 * @param mixed $value
	 * @return void
	 */
	public function set($name, $value);
	
	/**
	 * @author mnoerenberg
	 * @param string $name
	 * @return boolean
	 */
	public function has($name);
	
	/**
	 * @author mnoerenberg
	 * @param string $name
	 * @return void
	 */
	public function remove($name);
	
	/**
	 * @author mnoerenberg
	 * @return void
	 */
	public function clear();
	
	/**
	 * @author mnoerenberg
	 * @return void
	 */
	public function start();
	
	/**
	 * @author mnoerenberg
	 * @return boolean
	 */
	public function isStarted();
	
	/**
	 * @author mnoerenberg
	 * @return void
	 */
	public function destroy();
}
