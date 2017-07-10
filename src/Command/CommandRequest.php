<?php
namespace Simovative\Zeus\Command;

/**
 * @author mnoerenberg
 */
class CommandRequest {
	
	/**
	 * @var array|mixed[]
	 */
	private $valueMap;
	
	/**
	 * @author Benedikt Schaller
	 * @param array|mixed[] $valueMap
	 */
	public function __construct(array $valueMap) {
		$this->valueMap = $valueMap;
	}
	
	/**
	 * Will return null, if a value does not exist.
	 *
	 * @author Benedikt Schaller
	 * @param string $key
	 * @return mixed|null
	 */
	public function get($key) {
		if ($this->has($key)) {
			return $this->valueMap[$key];
		}
		return null;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @param string $key
	 * @return bool
	 */
	public function has($key) {
		return array_key_exists($key, $this->valueMap);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return array|\mixed[]
	 */
	public function all() {
		return $this->valueMap;
	}
}
