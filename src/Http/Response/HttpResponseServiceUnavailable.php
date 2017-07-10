<?php
namespace Simovative\Zeus\Http\Response;

use Simovative\Zeus\Content\Page;

/**
 * @author mnoerenberg
 */
class HttpResponseServiceUnavailable extends HttpResponsePage {
	
	/**
	 * @author mnoerenberg
	 * @param Page $page
	 */
	public function __construct(Page $page) {
		$this->page = $page;
		$this->addHeader('HTTP/1.1 503 Service Unavailable');
	}
}
