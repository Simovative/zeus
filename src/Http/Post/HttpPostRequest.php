<?php
namespace Simovative\Zeus\Http\Post;

use Simovative\Zeus\Http\Request\HttpRequest;
use Simovative\Zeus\Http\Url\Url;

/**
 * @author mnoerenberg
 */
class HttpPostRequest extends HttpRequest {
	
	/**
	 * HttpPostRequest constructor.
	 * @param Url $url
	 * @param array|\mixed[] $parameters
	 * @param UploadedFile[] $uploadedFiles
	 */
	protected function __construct(Url $url, $parameters, $uploadedFiles) {
		parent::__construct($url, $parameters);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Simovative\Zeus\Http\Request\HttpRequest::isPost()
	 * @author mnoerenberg
	 */
	public function isPost() {
		return true;
	}
}

