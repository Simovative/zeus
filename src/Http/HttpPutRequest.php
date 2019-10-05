<?php
namespace Simovative\Zeus\Http\Get;

use Simovative\Zeus\Http\Request\HttpRequest;

/**
 * @author Benedikt Schaller
 */
class HttpPutRequest extends HttpRequest {
	
	/**
	 * @inheritdoc
	 * @author Benedikt Schaller
	 */
	public function isPut() {
		return true;
	}
}
