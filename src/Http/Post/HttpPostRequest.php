<?php
namespace Simovative\Zeus\Http\Post;

use Simovative\Zeus\Http\Request\HttpRequest;

/**
 * @author mnoerenberg
 */
class HttpPostRequest extends HttpRequest {
	
	/**
	 * (non-PHPdoc)
	 * @see \Simovative\Zeus\Http\Request\HttpRequest::isPost()
	 * @author mnoerenberg
	 */
	public function isPost() {
		return true;
	}
}

