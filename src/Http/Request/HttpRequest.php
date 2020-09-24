<?php
namespace Simovative\Zeus\Http\Request;

use Simovative\Zeus\Http\Url\Url;
use Simovative\Zeus\Stream\StreamInterface;

/**
 * @author mnoerenberg
 */
abstract class HttpRequest implements HttpRequestInterface {
	
	private const SERVER_PARAMETER_CONTENT_TYPE = 'CONTENT_TYPE';
	
	/**
	 * @var Url
	 */
	private $url;
	
	/**
     * @var string[]
     */
	private $parameters;
	
	/**
	 * @var mixed[]
	 */
	private $serverParameters;
	
	/**
	 * @var null|mixed[]|StreamInterface
	 */
	private $parsedBody;
	
	/**
	 * @author mnoerenberg
	 * @param Url $url
	 * @param mixed[] $parameters
	 * @param mixed[] $serverParameters
	 * @param null|StreamInterface|mixed[] $parsedBody
	 */
	public function __construct(Url $url, array $parameters, array $serverParameters, $parsedBody) {
		$this->url = $url;
		$this->parameters = $parameters;
		$this->serverParameters = $serverParameters;
		$this->parsedBody = $parsedBody;
	}
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function has($name) {
		return isset($this->parameters[$name]);
	}

	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function get($name, $default = null) {
		if (! $this->has($name)) {
			return $default;
		}
		
		return $this->parameters[$name];
	}
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function all() {
		return $this->parameters;
	}
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function getUrl() {
		return $this->url;
	}
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function isGet() {
		return false;
	}
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function isPost() {
		return false;
	}
	
	/**
	 * @inheritdoc
	 * @author Benedikt Schaller
	 */
	public function isPut() {
		return false;
	}
	
	/**
	 * @inheritdoc
	 * @author Benedikt Schaller
	 */
	public function isPatch() {
		return false;
	}
	
	/**
	 * @inheritdoc
	 * @author Benedikt Schaller
	 */
	public function isDelete() {
		return false;
	}
	
	/**
	 * @inheritdoc
	 * @author Benedikt Schaller
	 */
	public function isHead() {
		return false;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritDoc
	 */
	public function getParsedBody() {
		return $this->parsedBody;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return string|null
	 */
	public function getContentType(): ?string {
		if (array_key_exists(self::SERVER_PARAMETER_CONTENT_TYPE, $this->serverParameters)) {
			return $this->serverParameters[self::SERVER_PARAMETER_CONTENT_TYPE];
		}
		return null;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return mixed[]
	 */
	public function getServerParams(): array {
		return $this->serverParameters;
	}
}