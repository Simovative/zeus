<?php
namespace Simovative\Zeus\Http\Get;

use Simovative\Zeus\Http\Request\HttpRequest;

class HttpOptionRequest extends HttpRequest {
	
	/**
	 * @inheritdoc
	 */
	public function isOption(): bool {
		return true;
	}
}
