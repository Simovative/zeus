<?php
namespace Simovative\Zeus\Http\Get;

use Simovative\Zeus\Http\Request\HttpRequest;

/**
 * @author mnoerenberg
 */
class HttpGetRequest extends HttpRequest {
	
	/**
	 * (non-PHPdoc)
	 * @see \Simovative\Zeus\Http\Request\HttpRequest::isGet()
	 * @author mnoerenberg
	 */
	public function isGet() {
		return true;
	}
}
