<?php
namespace {{namespace}}\Application;

use Simovative\Zeus\Dependency\Factory;
use Simovative\Zeus\Http\Url\UrlMatcher;
use Simovative\Zeus\Template\TemplateEngineInterface;

class {{appPrefix}}Factory extends Factory {
	
	const URL_PREFIX = '/';
	
	/**
	 * @return null
	 */
	public function createApplicationState() {
		return null;
	}
	
	/**
	 * @return UrlMatcher
	 */
	public function createUrlMatcher() {
		return new UrlMatcher(self::URL_PREFIX);
	}
	
	/**
	 * @return TemplateEngineInterface
	 */
	public function createTemplateEngine() {
		$templateEngine = $this->getMasterFactory()->createSmartyTemplateEngine();
		$templateEngine->assign('url_prefix', self::URL_PREFIX);
		return $templateEngine;
	}

	/**
 	 * Allow Application Factory to overwrite various Framework Methods
	 * @return bool
	 */
	public function isOverwriteAllowed() {
		return true;
	}

}