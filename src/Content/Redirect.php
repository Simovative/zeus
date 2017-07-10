<?php
namespace Simovative\Zeus\Content;

use Simovative\Zeus\Http\Url\Url;

/**
 * @author mnoerenberg
 */
class Redirect implements Content {
	
	/**
	 * @var Url
	 */
	private $url;
	
	/**
	 * @author mnoerenberg
	 * @param Url $url
	 */
	public function __construct(Url $url) {
		$this->url = $url;
	}
	
	/**
	 * @author mnoerenberg
	 * @return string
	 */
	public function render() {
		return (string) $this->url;
	}
}
