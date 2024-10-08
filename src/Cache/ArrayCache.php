<?php
namespace Simovative\Zeus\Cache;

/**
 * @author mnoerenberg
 */
class ArrayCache implements CacheInterface {

	/**
	 * @var string[]
	 */
	private $cache = array();

	/**
	 * (non-PHPdoc)
	 * @see \Simovative\Zeus\Cache\CacheInterface::set()
	 * @author mnoerenberg
	 */
	public function set($key, $value) {
		$this->cache[$key] = $value;
	}

	/**
	 * (non-PHPdoc)
	 * @see \Simovative\Zeus\Cache\CacheInterface::get()
	 * @author mnoerenberg
	 */
	public function get($key) {
		if (! $this->exists($key)) {
			return null;
		}

		return $this->cache[$key];
	}

	/**
	 * (non-PHPdoc)
	 * @see \Simovative\Zeus\Cache\CacheInterface::exists()
	 * @author mnoerenberg
	 */
	public function exists($key) {
		return array_key_exists($key, $this->cache);
	}

	/**
	 * (non-PHPdoc)
	 * @see \Simovative\Zeus\Cache\CacheInterface::remove()
	 * @author mnoerenberg
	 */
	public function remove($key) {
		if ($this->exists($key)) {
			unset($this->cache[$key]);
		}
	}
}
