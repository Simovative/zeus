<?php
namespace Simovative\Zeus\Http\Response;

use Simovative\Zeus\Content\Content;

/**
 * @author shartmann
 */
class HttpResponseError extends HttpResponse {
	
	/**
	 * @var Content
	 */
	private $content;
	
	/**
	 * @author shartmann
	 * @param Content $content
	 */
	public function __construct(Content $content) {
		$this->content = $content;
		$this->addHeader('HTTP/1.1 500 Server Error');
	}
	
	/**
	 * @author mnoerenberg
	 * @return string
	 */
	protected function getBody() {
		return $this->content->render();
	}
}
