<?php
namespace Simovative\Zeus\Command;

use Simovative\Zeus\Http\Get\HttpDeleteRequest;
use Simovative\Zeus\Http\Post\HttpPostRequest;

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
	 * @param HttpPostRequest $postRequest
	 * @return CommandRequest
	 */
	public static function fromHttpPostRequest(HttpPostRequest $postRequest) {
		$values = $postRequest->all();
		return new CommandRequest($values);
	}
	
	/**
	 * @author tp
	 * @param HttpDeleteRequest $deleteRequest
	 * @return CommandRequest
	 */
	public static function fromHttpDeleteRequest(HttpDeleteRequest $deleteRequest) {
		$matches = [];
		preg_match('/\/(\w+)$/m', $deleteRequest->getUrl(), $matches);
		$id = null;
		if (array_key_exists(1, $matches)) {
			$id = $matches[1];
		}
		return new CommandRequest(['id' => $id]);
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
