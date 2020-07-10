<?php
namespace Simovative\Zeus\Http\Request;

use LogicException;
use Simovative\Zeus\Http\Get\HttpGetRequest;
use Simovative\Zeus\Http\HttpDeleteRequest;
use Simovative\Zeus\Http\HttpHeaderRequest;
use Simovative\Zeus\Http\HttpPatchRequest;
use Simovative\Zeus\Http\HttpPutRequest;
use Simovative\Zeus\Http\Post\HttpPostRequest;
use Simovative\Zeus\Http\Post\UploadedFile;
use Simovative\Zeus\Http\Url\Url;
use Simovative\Zeus\Stream\PhpInputStream;
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
	 * @var array
	 */
	private $serverParameters;
	
	/**
	 * @author mnoerenberg
	 * @param Url $url
	 * @param mixed[] $parameters
	 * @param array $serverParameters
	 */
	public function __construct(Url $url, array $parameters = [], array $serverParameters = []) {
		$this->url = $url;
		$this->parameters = $parameters;
		$this->serverParameters = $serverParameters;
	}
	
	/**
	 * Returns a request by globals.
	 *
	 * @author mnoerenberg
	 * @return HttpRequestInterface
	 */
	public static function createFromGlobals() {
		$currentUrl = Url::createFromServerArray($_SERVER);
		
		switch ($_SERVER['REQUEST_METHOD']) {
			case 'POST':
				$uploadedFiles = UploadedFile::createFromGlobal($_FILES);
				return new HttpPostRequest($currentUrl, $_REQUEST, $_SERVER, $uploadedFiles);
			case 'GET':
				return new HttpGetRequest($currentUrl, $_GET, $_SERVER);
			case 'PUT':
				return new HttpPutRequest($currentUrl, $_REQUEST, $_SERVER);
			case 'PATCH':
				return new HttpPatchRequest($currentUrl, $_REQUEST, $_SERVER);
			case 'DELETE':
				return new HttpDeleteRequest($currentUrl, $_REQUEST, $_SERVER);
			case 'HEADER':
				return new HttpHeaderRequest($currentUrl, $_REQUEST, $_SERVER);
		}
		
		throw new LogicException('request method not allowed.');
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
	public function isHeader() {
		return false;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritDoc
	 * @return StreamInterface|array
	 */
	public function getParsedBody() {
		return new PhpInputStream();
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
	 * @return array
	 */
	public function getServerParams(): array {
		return $this->serverParameters;
	}
}