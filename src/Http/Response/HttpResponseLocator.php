<?php
namespace Simovative\Zeus\Http\Response;

use Simovative\Zeus\Content\File;
use Simovative\Zeus\Content\Json;
use Simovative\Zeus\Content\Image;
use Simovative\Zeus\Content\Content;
use Simovative\Zeus\Content\NotFoundPage;
use Simovative\Zeus\Content\Redirect;
use Simovative\Zeus\Content\NoContent;

/**
 * @author mnoerenberg
 */
class HttpResponseLocator implements HttpResponseLocatorInterface {
	
	/**
	 * @author mnoerenberg
	 * @param Content $content
	 * @return HttpResponseInterface
	 * @throws \Exception
	 */
	public function getResponseFor(Content $content): HttpResponseInterface {
		// redirect
		if ($content instanceof Redirect) {
			return new HttpResponseRedirect($content);
		}
		// no content
		if ($content instanceof NoContent) {
			return new HttpResponseNoContent();
		}
		// json
		if ($content instanceof Json) {
			return new HttpResponseJson($content);
		}
		// image
		if ($content instanceof Image) {
			return new HttpResponseImage($content);
		}
		// file
		if ($content instanceof File) {
			return new HttpResponseFile($content);
		}
		
		// Not found page
		if ($content instanceof NotFoundPage) {
			return new HttpResponseNotFound($content);
		}
		
		// Direct http response to make special cases easy to implement
		if ($content instanceof HttpResponseInterface) {
			return $content;
		}
		
		return new HttpResponsePage($content);
	}
}
