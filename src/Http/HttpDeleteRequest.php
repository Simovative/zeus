<?php
namespace Simovative\Zeus\Http;

use Simovative\Zeus\Http\Request\HttpRequest;

/**
 * @author Benedikt Schaller
 */
class HttpDeleteRequest extends HttpRequest {
	
	/**
	 * @inheritdoc
	 * @author Benedikt Schaller
	 */
	public function isDelete() {
		return true;
	}
}
