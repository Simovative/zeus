<?php
namespace Simovative\Zeus\Http\Request;

use Simovative\Zeus\Http\Get\HttpGetRequest;
use Simovative\Zeus\Http\Post\HttpPostRequest;
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
		$protocol = 'http://';
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
			$protocol = 'https://';
		}
		$currentUrl = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
// 		$currentUrl = str_replace('index.php', '', $currentUrl); // fix to redirect to /
		
		switch ($_SERVER['REQUEST_METHOD']) {
			case 'POST':
				return new HttpPostRequest(new Url($currentUrl), $_REQUEST);
			case 'GET':
				return new HttpGetRequest(new Url($currentUrl), $_REQUEST);
		}
		
		throw new \LogicException('request method not allowed.');
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Simovative\Zeus\Http\Request\HttpRequestInterface::has()
	 * @author mnoerenberg
	 */
	public function has($name) {
		return isset($this->parameters[$name]);
	}

	/**
	 * (non-PHPdoc)
	 * @see \Simovative\Zeus\Http\Request\HttpRequestInterface::get()
	 * @author mnoerenberg
	 */
	public function get($name, $default = null) {
		if (! $this->has($name)) {
			return $default;
		}
		
		return $this->parameters[$name];
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Simovative\Zeus\Http\Request\HttpRequestInterface::all()
	 * @author mnoerenberg
	 */
	public function all() {
		return $this->parameters;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Simovative\Zeus\Http\Request\HttpRequestInterface::getUrl()
	 * @author mnoerenberg
	 */
	public function getUrl() {
		return $this->url;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Simovative\Zeus\Http\Request\HttpRequestInterface::isGet()
	 * @author mnoerenberg
	 */
	public function isGet() {
		return false;
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see \Simovative\Zeus\Http\Request\HttpRequestInterface::isPost()
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