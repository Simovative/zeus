<?php
namespace Simovative\Zeus\Command;

use Simovative\Zeus\Http\Request\HttpRequestInterface;

/**
 * @author Benedikt Schaller
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
	 * @author Benedikt Schaller
	 * @param HttpRequestInterface $httpRequest
	 * @return CommandRequest
	 */
	public static function fromHttpRequest(HttpRequestInterface $httpRequest) {
		$values = $httpRequest->all();
		return new CommandRequest($values);
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
	 * @return mixed[]
	 */
	public function all() {
		return $this->valueMap;
	}
}
