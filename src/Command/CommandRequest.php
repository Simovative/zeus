<?php
namespace Simovative\Zeus\Command;

use Simovative\Zeus\Http\Request\HttpRequestInterface;
use Simovative\Zeus\Stream\StreamInterface;

/**
 * @author Benedikt Schaller
 */
class CommandRequest {
	
	/**
	 * @var array|mixed[]
	 */
	private $valueMap;

    /**
     * @var null|array|StreamInterface
     */
    private $body;

    /**
     * @param array|mixed[] $valueMap
     * @param null|array|StreamInterface $body
     * @author Benedikt Schaller
     */
	public function __construct(array $valueMap, $body) {
		$this->valueMap = $valueMap;
        $this->body = $body;
    }
	
	/**
	 * @author Benedikt Schaller
	 * @param HttpRequestInterface $httpRequest
	 * @return CommandRequest
	 */
	public static function fromHttpRequest(HttpRequestInterface $httpRequest) {
		$values = $httpRequest->all();
		$body = $httpRequest->getParsedBody();
		if ($body instanceof StreamInterface) {
			$body = $body->getContents();
		}
		return new CommandRequest($values, $body);
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

    /**
     * @return null|array|StreamInterface
     */
    public function getBody() {
        return $this->body;
    }
}
