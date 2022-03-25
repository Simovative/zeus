<?php
namespace {{appNamespace}}\{{bundleName}}\Routing;

use {{appNamespace}}\{{bundleName}}\{{bundleName}}Factory;
use Simovative\Zeus\Http\Get\HttpGetRequestRouterInterface;
use Simovative\Zeus\Http\Request\HttpRequestInterface;
use Simovative\Zeus\Http\Url\UrlMatcherInterface;

class {{bundleName}}GetRequestRouter implements HttpGetRequestRouterInterface {
	
	/**
	 * @var {{bundleName}}Factory
	 */
	private ${{lcc_name}}Factory;
	
	/**
	 * @var UrlMatcherInterface
	 */
	private $urlMatcher;
	
	/**
	 * @param {{bundleName}}Factory $demoFactory
	 * @param UrlMatcherInterface $urlMatcher
	 */
	public function __construct({{bundleName}}Factory ${{lcc_name}}Factory, UrlMatcherInterface $urlMatcher) {
		$this->{{lcc_name}}Factory = ${{lcc_name}}Factory;
		$this->urlMatcher = $urlMatcher;
	}
	
	/**
	 * @inheritdoc
	 */
	public function route(HttpRequestInterface $request) {
		// Todo: Remove the comments and add sane routes
		// if ($this->urlMatcher->matchAny($request->getUrl(), array('/', '', '/{{lcc_name}}/home'))) {
			return $this->{{lcc_name}}Factory->createHomePage();
		// }
		// return null;
	}
}