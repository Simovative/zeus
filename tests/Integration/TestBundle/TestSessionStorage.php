<?php
namespace Simovative\Test\Integration\TestBundle;

use Simovative\Zeus\Session\Storage\SessionStorageInterface;

/**
 * @author Benedikt Schaller
 */
class TestSessionStorage implements SessionStorageInterface {
	
	/**
	 * @var array
	 */
	private $sessionValues;
	
	/**
	 * @author Benedikt Schaller
	 * @param array $sessionValues
	 */
	public function __construct(array $sessionValues) {
		$this->sessionValues = $sessionValues;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritDoc
	 */
	public function all() {
		return $this->sessionValues;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritDoc
	 */
	public function get($name) {
	    if ($this->has($name)) {
            return $this->sessionValues[$name];
        }
        return null;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritDoc
	 */
	public function set($name, $value) {
		$this->sessionValues[$name] = $value;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritDoc
	 */
	public function has($name) {
		return array_key_exists($name, $this->sessionValues);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritDoc
	 */
	public function remove($name) {
		unset($this->sessionValues[$name]);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritDoc
	 */
	public function start() {
		// do nothing
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritDoc
	 */
	public function isStarted() {
		return true;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritDoc
	 */
	public function destroy() {
		$this->sessionValues = [];
	}
}