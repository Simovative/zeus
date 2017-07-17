<?php
namespace Simovative\Zeus\Http\Url;

/**
 * @author mnoerenberg
 * @author shartmann
 */
class UrlMatcher implements UrlMatcherInterface {
	
	/**
	 * @var string
	 */
	private $urlPrefix;
	
	/**
	 * @author Benedikt Schaller
	 * @param string $urlPrefix
	 */
	public function __construct($urlPrefix = '') {
		$this->urlPrefix = trim($urlPrefix, '/');
	}
	
	/**
	 * @inheritdoc
	 * @author shartmann
	 */
	public function match(Url $url, $route, $matchPath = true) {
		if($matchPath) {
			$toMatch = '/' . ltrim($this->urlPrefix . '/' . $url->getPath(), '/');
		} else {
			$toMatch = strtok('/', (string) $url) . '/' . $this->urlPrefix . '/' . $url->getPath();
		}
		
		// simple match
		if (strpos($route, '%') === false) {
			return $toMatch === $route;
		}
		
		/*
		 * If the URL/URI contains variables we need to create a suitable pattern first
		 * Currently only %d for integer values and %s for ANY KIND OF strings are supported (Yes, even the weird ones).
		 *
		 * Whatever variable you choose, keep in mind that '/' will ALWAYS terminate your match, meaning that you can't
		 * have any strings containing '/' AS PART OF %s in your routing table.     --sh
		 */
		$search = array('~', '/%s', '/%d');
		$replace = array('\\~', '/([^/]+)', '/([0-9]+)');
		$pattern = '~^' . str_replace($search, $replace, $route) . '/?(\?.*)?$~i';
		return 1 === preg_match($pattern, $toMatch);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritdoc
	 */
	public function matchAny(Url $url, $routes, $matchPath = true) {
		foreach ($routes as $route) {
			if ($this->match($url, $route, $matchPath)) {
				return true;
			}
		}
		return false;
	}
}
