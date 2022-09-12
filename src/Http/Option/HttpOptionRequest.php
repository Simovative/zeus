<?php
namespace Simovative\Zeus\Http\Get;

use Simovative\Zeus\Http\Request\HttpRequest;

/**
 * @author mnoerenberg
 */
class HttpOptionRequest extends HttpRequest {
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function isOption(): bool {
		return true;
	}
}
