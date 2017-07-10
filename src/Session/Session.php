<?php
namespace Simovative\Zeus\Session;

use Simovative\Zeus\Session\Storage\SessionStorageInterface;

/**
 * @author mnoerenberg
 */
class Session implements SessionInterface {
	
	/**
	 * @var SessionStorageInterface
	 */
	private $storage;
	
	/**
	 * @author mnoerenberg
	 * @param SessionStorageInterface $storage
	 */
	public function __construct(SessionStorageInterface $storage) {
		$this->storage = $storage;
	}
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function has($name) {
		if (! $this->isStarted()) {
			$this->start();
		}
		return $this->storage->has($name);
	}
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function get($name) {
		if (! $this->isStarted()) {
			$this->start();
		}
		return $this->storage->get($name);
	}
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function all() {
		if (! $this->isStarted()) {
			$this->start();
		}
		return $this->storage->all();
	}
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function set($name, $value) {
		if (! $this->isStarted()) {
			$this->start();
		}
		$this->storage->set($name, $value);
	}
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function remove($name) {
		if (! $this->isStarted()) {
			$this->start();
		}
		$this->storage->remove($name);
	}
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function clear() {
		$all = $this->storage->all();
		foreach ($all as $key => $value) {
			$this->storage->remove($key);
		}
	}
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function start() {
		$this->storage->start();
	}
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function isStarted() {
		return $this->storage->isStarted();
	}
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function destroy() {
		if (! $this->isStarted()) {
			$this->start();
		}
		$this->storage->destroy();
	}
}
