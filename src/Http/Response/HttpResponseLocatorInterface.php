<?php
namespace Simovative\Zeus\Http\Response;

use Simovative\Zeus\Content\Content;

/**
 * @author Benedikt Schaller
 */
interface HttpResponseLocatorInterface {
	
	/**
	 * @author Benedikt Schaller
	 * @param Content $content
	 * @throws \LogicException
	 * @return HttpResponseInterface
	 */
	public function getResponseFor(Content $content): HttpResponseInterface;
}