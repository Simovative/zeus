<?php
namespace Simovative\Zeus\Http\Request;

use Simovative\Zeus\Http\Get\HttpDeleteRequest;
use Simovative\Zeus\Http\Get\HttpGetRequest;
use Simovative\Zeus\Http\Get\HttpPatchRequest;
use Simovative\Zeus\Http\Get\HttpPutRequest;
use Simovative\Zeus\Http\Post\HttpPostRequest;
use Simovative\Zeus\Http\Post\UploadedFile;
use Simovative\Zeus\Http\Url\Url;

/**
 * @author mnoerenberg
 */
abstract class HttpRequest implements HttpRequestInterface {
	
	/**
	 * @var Url
	 */
	private $url;
	
	/**
     * @var string[]
     */
	private $parameters;
	
	/**
     * @author mnoerenberg
     * @param Url $url
     * @param mixed[] $parameters
     */
	protected function __construct(Url $url, array $parameters = array()) {
		$this->url = $url;
		$this->parameters = $parameters;
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
				return new HttpPostRequest($currentUrl, $_REQUEST, $uploadedFiles);
			case 'GET':
				return new HttpGetRequest($currentUrl, $_REQUEST);
			case 'PUT':
				return new HttpPutRequest($currentUrl, $_REQUEST);
			case 'PATCH':
				return new HttpPatchRequest($currentUrl, $_REQUEST);
			case 'DELETE':
				return new HttpDeleteRequest($currentUrl, $_REQUEST);
		}
		
		throw new \LogicException('request method not allowed.');
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
}