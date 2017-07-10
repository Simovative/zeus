<?php
namespace Simovative\Zeus\Http\Response;

use Simovative\Zeus\Content\Json;

/**
 * @author mnoerenberg
 */
class HttpResponseJson extends HttpResponse {
	
	/**
	 * @var Json
	 */
	private $json;
	
	/**
	 * @author mnoerenberg
	 * @param Json $json
	 */
	public function __construct(Json $json) {
		$this->json = $json;
		$this->addHeader('Content-Type', 'application/json');
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Simovative\Zeus\Http\Response\HttpResponse::getBody()
	 * @author mnoerenberg
	 */
	protected function getBody() {
		return json_encode($this->json->render());
	}
}
