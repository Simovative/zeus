<?php
namespace Simovative\Test\Integration\TestBundle;

use Simovative\Zeus\Content\Content;
use Simovative\Zeus\Http\Response\HttpResponseInterface;
use Simovative\Zeus\Http\Response\HttpResponseLocatorInterface;

/**
 * @author Benedikt Schaller
 */
class TestResponseLocator implements HttpResponseLocatorInterface {
	
	/**
	 * @inheritDoc
	 */
	public function getResponseFor(Content $content): HttpResponseInterface {
		return new HttpTestResponse($content);
	}
}