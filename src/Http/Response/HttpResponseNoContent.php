<?php
namespace Simovative\Zeus\Http\Response;

/**
 * @author mnoerenberg
 */
class HttpResponseNoContent extends HttpResponse {
	
	/**
	 * @author mnoerenberg
	 */
	public function __construct() {
		$this->addHeader('HTTP/1.1 204 No Content');
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Simovative\Zeus\Http\Response\HttpResponse::getBody()
	 */
	protected function getBody() {
		return '';
	}
}
