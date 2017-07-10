<?php
namespace {{appNamespace}}\{{bundleName}}\Routing;

use {{appNamespace}}\{{bundleName}}\{{bundleName}}Factory;
use Simovative\Zeus\Http\Post\HttpPostRequest;
use Simovative\Zeus\Command\CommandRouterInterface;
use Simovative\Zeus\Http\Url\UrlMatcherInterface;

class {{bundleName}}CommandRouter implements CommandRouterInterface {
	
	/**
	 * @var {{bundleName}}Factory
	 */
	private ${{lcc_name}}Factory;
	
	/**
	 * @var UrlMatcherInterface
	 */
	private $urlMatcher;
	
	
	/**
	 * @param {{bundleName}}Factory ${{lcc_name}}Factory
	 * @param UrlMatcherInterface $urlMatcher
	 */
	public function __construct({{bundleName}}Factory ${{lcc_name}}Factory, UrlMatcherInterface $urlMatcher) {
		$this->{{lcc_name}}Factory = ${{lcc_name}}Factory;
		$this->urlMatcher = $urlMatcher;
	}
	
	/**
	 * @inheritdoc
	 */
	public function route(HttpPostRequest $request) {
		return null;
	}
}