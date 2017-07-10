<?php
namespace Simovative\Zeus\Http\Response;

use Simovative\Zeus\Content\Redirect;

/**
 * @author mnoerenberg
 */
class HttpResponseRedirect extends HttpResponse {
	
	/**
	 * @author mnoerenberg
	 * @param Redirect $content
	 */
	public function __construct(Redirect $content) {
		$this->addHeader('HTTP/1.1 302 Found');
		$this->addHeader('Location: ' . $content->render());
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Simovative\Zeus\Http\Response\HttpResponse::getBody()
	 * @author mnoerenberg
	 */
	protected function getBody() {
		return '';
	}
}
