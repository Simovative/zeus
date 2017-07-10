<?php
namespace Simovative\Zeus\Configuration;

/**
 * @author mnoerenberg
 */
class Configuration {
	
	/**
	 * @var string[]
	 */
	protected $configs;
	
	/**
	 * @var string
	 */
	protected $basePath;
	
	/**
	 * @author mnoerenberg
	 * @param string[] $configs
	 * @param string $basePath
	 */
	public function __construct(array $configs, $basePath) {
		$this->configs = $configs;
		$this->basePath = $basePath;
	}
	
	/**
	 * @author mnoerenberg
	 * @return string
	 */
	public function getBasePath() {
		return $this->basePath;
	}
	
	/**
	 * @author mnoerenberg
	 * @param string $key
	 * @return mixed|null
	 */
	public function get($key) {
		if ($this->has($key)) {
			return $this->configs[$key];
		}
		return null;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @param string $key
	 * @return bool
	 */
	private function has($key) {
		return array_key_exists($key, $this->configs);
	}
}