<?php
namespace Simovative\Zeus\Http\Url;

/**
 * @author mnoerenberg
 */
class UrlMatcher implements UrlMatcherInterface {
	
	/**
	 * @var Url
	 */
	private $url;

	/**
	 * @var string[]
	 */
	private $components;
	
	/**
	 * @var string
	 */
	private $urlPrefix;
	
	/**
	 * @author Benedikt Schaller
	 * @param string $urlPrefix
	 */
	public function __construct($urlPrefix = '') {
		$this->urlPrefix = $urlPrefix;
	}
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function getComponent($position) {
		if ($this->hasComponent($position)) {
			$components = $this->getComponents();
			return $components[$position];
		}
			
		throw new \LogicException('position does not exist.');
	}
	
	/**
	 * @inheritdoc
	 * @author Benedikt Schaller
	 */
	public function hasComponent($position) {
		if (array_key_exists($position, $this->getComponents())) {
			return true;
		}
		return false;
	}

	/**
	 * Returns the components of the url.
	 *
	 * @author Benedikt Schaller
	 * @return string[]|array
	 */
	private function getComponents() {
		if ($this->components === null) {
			$this->components = explode('/', $this->url->getPath());
		}
		return $this->components;
	}
	
	/**
	 * Returns true if size of route components is equal to url components.
	 * 
	 * @author mnoerenberg
	 * @param string[] $routeComponents
	 * @return boolean
	 */
	private function hasEqualSize($routeComponents) {
		return count($routeComponents) == count($this->getComponents());
	}
	
	/**
	 * Returns true if route is equal to url.
	 * 
	 * @author mnoerenberg
	 * @param string $route
	 * @return boolean
	 */
	private function isEqual($route) {
		return $this->urlPrefix . $route == $this->url->getPath();
	}

	/**
	 * Change the url to match.
	 *
	 * @author Benedikt Schaller
	 * @param $url
	 * @return void
	 */
	private function changeUrl($url) {
		if ($url === $this->url) {
			return;
		}
		$this->url = $url;
		$this->components = null;
	}
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function match(Url $url, $route) {
		$this->changeUrl($url);

		// return false if route is static and is not equal.
		if (strpos($route, '%') === false) {
			return $this->isEqual($route);
		}
		
		// check size
		$routeComponents = explode('/', $route);
		if (! $this->hasEqualSize($routeComponents)) {
			return false;
		}
		
// 		$diff = array_diff($this->components, $routeComponents);
		
		// for each component
		foreach ($routeComponents as $index => $routeComponent) {
			$urlComponent = $this->components[$index];
			
			if ($routeComponent == '%d') {
				if (! is_numeric($urlComponent)) {
					return false;
				}
				continue;
			}
			
			if ($routeComponent == '%s') {
				continue;
			}
			
			if ($routeComponent != $urlComponent) {
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritdoc
	 */
	public function matchAny(Url $url, $routes) {
		foreach ($routes as $route) {
			if ($this->match($url, $route)) {
				return true;
			}
		}
		return false;
	}
}
