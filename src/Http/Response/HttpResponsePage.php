<?php
namespace Simovative\Zeus\Http\Response;

use Simovative\Zeus\Content\Content;
use Simovative\Zeus\Content\Page;

/**
 * @author mnoerenberg
 */
class HttpResponsePage extends HttpResponse {
	
	/**
	 * @var Content
	 */
	protected $page;
	
	/**
	 * @author mnoerenberg
	 * @param Content $page
	 */
	public function __construct(Content $page) {
		$this->page = $page;
		$this->addHeader('HTTP/1.1 200 OK');
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Simovative\Zeus\Http\Response\HttpResponse::getBody()
	 * @author mnoerenberg
	 */
	protected function getBody() {
		return $this->page->render();
	}
}
