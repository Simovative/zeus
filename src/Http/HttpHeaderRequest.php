<?php
namespace Simovative\Zeus\Http\Get;

use Simovative\Zeus\Http\Request\HttpRequest;

/**
 * @author Benedikt Schaller
 */
class HttpHeaderRequest extends HttpRequest {
	
	/**
	 * @inheritdoc
	 * @author Benedikt Schaller
	 */
	public function isHeader() {
		return true;
	}
}
