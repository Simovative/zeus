<?php
namespace Simovative\Zeus\Session\Storage;

/**
 * @author mnoerenberg
 */
class NativeSessionStorage implements SessionStorageInterface {
	
	/**
	 * @var boolean
	 */
	private $started = false;
	
	/**
	 * @author mnoerenberg
	 * @param \SessionHandlerInterface|NULL $handler - default: null
	 */
	public function __construct($handler = null) {
		ini_set('session.use_cookies', 1);
		
		if ($handler !== null) {
			if (PHP_VERSION_ID >= 50400) {
				session_set_save_handler($handler, false);
			} else {
				session_set_save_handler(
					array($handler, 'open'),
					array($handler, 'close'),
					array($handler, 'read'),
					array($handler, 'write'),
					array($handler, 'destroy'),
					array($handler, 'gc')
				);
			}
		}
	}
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function get($name) {
		if (! $this->has($name)) {
			return null;
		}
		
		return $_SESSION[$name];
	}
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function set($name, $value) {
		if (! $this->started) {
			throw new \RuntimeException('session not started');
		}
		
		$_SESSION[$name] = $value;
	}
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function has($name) {
		if (! $this->started) {
			throw new \RuntimeException('Session not started');
		}
		
		return array_key_exists($name, $_SESSION);
	}
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function remove($name) {
		if (! $this->has($name)) {
			return;
		}
		
		unset($_SESSION[$name]);
	}
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function start() {
		if ($this->started) {
			return;
		}
		
		if (! session_start()) {
			throw new \RuntimeException('session start failed');
		}
		
		$this->started = true;
	}
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function isStarted() {
		return $this->started;
	}
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function destroy() {
		if (! $this->started) {
			throw new \RuntimeException('Session not started');
		}
		
		session_destroy();
	}
	
	/**
	 * @inheritdoc
	 * @author Benedikt Schaller
	 */
	public function all() {
		if (! $this->started) {
			throw new \RuntimeException('Session not started');
		}
		return $_SESSION;
	}
}
