<?php
namespace Simovative\Zeus\Http\Response;

use Simovative\Zeus\Content\Content;

/**
 * @author mnoerenberg
 */
class HttpResponseNotFound extends HttpResponsePage {
	
	/**
	 * @author mnoerenberg
	 * @param Content $page
	 * @noinspection PhpMissingParentConstructorInspection
	 */
	public function __construct(Content $page) {
		$this->page = $page;
		$this->addHeader('HTTP/1.1 404 Not Found');
	}
}
