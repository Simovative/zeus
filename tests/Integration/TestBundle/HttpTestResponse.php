<?php
namespace Simovative\Test\Integration\TestBundle;

use Simovative\Zeus\Content\Content;
use Simovative\Zeus\Http\Response\HttpResponseInterface;

/**
 * @author Benedikt Schaller
 */
class HttpTestResponse implements HttpResponseInterface {
	
	/**
	 * @var Content
	 */
	private $content;
	
	/**
	 * @author Benedikt Schaller
	 * @param Content $content
	 */
	public function __construct(Content $content) {
		$this->content = $content;
	}
	
	/**
	 * @inheritDoc
	 */
	public function send() {}
	
	/**
	 * @return Content
	 * @author Benedikt Schaller
	 */
	public function getContent() {
		return $this->content;
	}
}