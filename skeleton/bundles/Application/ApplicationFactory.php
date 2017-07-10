<?php
namespace Simovative\Skeleton\Application;

use Simovative\Zeus\Dependency\Factory;
use Simovative\Zeus\Http\Url\UrlMatcher;
use Simovative\Zeus\Template\TemplateEngineInterface;

/**
 * @author Benedikt Schaller
 */
class ApplicationFactory extends Factory {
	
	const URL_PREFIX = '';
	
	/**
	 * @author Benedikt Schaller
	 * @return null
	 */
	public function createApplicationState() {
		return new ApplicationState($this->getMasterFactory()->getSession());
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return UrlMatcher
	 */
	public function createUrlMatcher() {
		return new UrlMatcher(self::URL_PREFIX);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return TemplateEngineInterface
	 */
	public function createDemoTemplateEngine() {
		$templateEngine = $this->getMasterFactory()->createSmartyTemplateEngine();
		$templateEngine->assign('url_prefix', self::URL_PREFIX);
		return $templateEngine;
	}
	
	/**
	 * Allow Application Factory to overwrite various Framework Methods
	 *
	 * @author shartmann
	 * @return bool
	 */
	public function isOverwriteAllowed() {
		return true;
	}
}