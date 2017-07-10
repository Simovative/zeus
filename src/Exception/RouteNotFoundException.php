<?php
namespace Simovative\Zeus\Exception;

use Simovative\Zeus\Http\Url\Url;

/**
 * @author mnoerenberg
 */
class RouteNotFoundException extends \Exception {
	
	/**
	 * @author mnoerenberg
	 * @param Url $url
	 */
	public function __construct(Url $url) {
		parent::__construct('Route not found: ' . $url);
	}
}
