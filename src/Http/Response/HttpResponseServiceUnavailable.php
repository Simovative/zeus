<?php
namespace Simovative\Zeus\Http\Response;

use Simovative\Zeus\Content\Content;

/**
 * @author mnoerenberg
 */
class HttpResponseServiceUnavailable extends HttpResponsePage {
	
	/**
	 * @param Content $page
     * @author mnoerenberg
	 * @noinspection MagicMethodsValidityInspection
     * @noinspection PhpMissingParentConstructorInspection
     */
	public function __construct(Content $page) {
		$this->page = $page;
		$this->addHeader('HTTP/1.1 503 Service Unavailable');
	}
}
