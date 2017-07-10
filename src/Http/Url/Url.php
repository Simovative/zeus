<?php
namespace Simovative\Zeus\Http\Url;

/**
 * @author mnoerenberg
 */
class Url {
	
	/**
	 * @var string
	 */
	private $url;
	
	/**
	 * @author mnoerenberg
	 * @param string $url
	 */
	public function __construct($url) {
		$this->url = $url;
	}
	
	/**
	 * Returns the path of the url.
	 * 
	 * @author mnoerenberg
	 * @return string
	 */
	public function getPath() {
		return parse_url($this->url, PHP_URL_PATH);
	}

	/**
	 * @author mnoerenberg
	 * @return string
	 */
	public function __toString() {
		return (string) $this->url;
	}
}
