<?php
namespace Simovative\Zeus\Dependency;

use Simovative\Zeus\Configuration\Configuration;

/**
 * @author mnoerenberg
 * @final
 */
final class MasterFactory {
	
	/**
	 * @var string[]
	 */
	private $factoryMethods = array();
	
	/**
	 * @var Configuration
	 */
	private $configuration;
	
	/**
	 * @author mnoerenberg
	 * @param Configuration $configuration
	 */
	public function __construct(Configuration $configuration) {
		$this->configuration = $configuration;
		$this->register(new FrameworkFactory());
	}
	
	/**
	 * @author mnoerenberg
	 * @return Configuration
	 */
	public function getConfiguration() {
		return $this->configuration;
	}
	
	/**
	 * @author mnoerenberg
	 * @author shartmann
	 *
	 * @throws \Exception
	 * @param FactoryInterface $factory
	 * @return void
	 */
	public function register(FactoryInterface $factory) {
		$factory->setMasterFactory($this);
		foreach (get_class_methods($factory) as $factoryMethod) {
			if (substr($factoryMethod, 0, 3) != 'get' && substr($factoryMethod, 0, 6) != 'create') {
				continue;
			}
			if (isset($this->factoryMethods[$factoryMethod]) && true !== $factory->isOverwriteAllowed()) {
				throw new \Exception(
					'Illegal overwrite of method ' . $factoryMethod . ' while registering factory'
				);
			}
			$this->factoryMethods[$factoryMethod] = $factory;
		}
	}
	
	/**
	 * @author mnoerenberg
	 * @param string $method
	 * @param mixed[] $parameters
	 * @throws \Exception
	 * @return mixed
	 */
	public function __call($method, array $parameters = array()) {
		if (! $this->hasMethod($method)) {
			throw new \BadFunctionCallException('method not found: ' . $method);
		}
		return call_user_func_array(array($this->factoryMethods[$method], $method), $parameters);
	}
	
	/**
	 * @author mnoerenberg
	 * @param string $method
	 * @return boolean
	 */
	public function hasMethod($method) {
		return array_key_exists($method, $this->factoryMethods);
	}
}