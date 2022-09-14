<?php
namespace Simovative\Zeus\Http\Options;

use Simovative\Zeus\Http\Request\HttpRequest;

class HttpOptionsRequest extends HttpRequest {
	
	/**
	 * @inheritdoc
	 */
	public function isOption(): bool {
		return true;
	}
}
