<?php
namespace Simovative\Zeus\Dependency;

use BadFunctionCallException;
use RuntimeException;
use Simovative\Zeus\Configuration\Configuration;
use Simovative\Zeus\Http\HttpRequestFactory;

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
	 * @throws RuntimeException
	 */
	public function __construct(Configuration $configuration) {
		$this->configuration = $configuration;
		$this->register(new FrameworkFactory());
		$this->register(new HttpRequestFactory());
	}
	
	/**
	 * @author mnoerenberg
	 * @return Configuration
	 */
	public function getConfiguration(): Configuration {
		return $this->configuration;
	}
	
	/**
	 * @author mnoerenberg
	 * @author shartmann
	 * @param FactoryInterface $factory
	 * @return void
	 * @throws RuntimeException
	 */
	public function register(FactoryInterface $factory): void {
		$factory->setMasterFactory($this);
		foreach (get_class_methods($factory) as $factoryMethod) {
			if (strpos($factoryMethod, 'get') !== 0 && strpos($factoryMethod, 'create') !== 0) {
				continue;
			}
			if (isset($this->factoryMethods[$factoryMethod]) && true !== $factory->isOverwriteAllowed()) {
				throw new RuntimeException(
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
	 * @return mixed
	 * @throws BadFunctionCallException
	 */
	public function __call(string $method, array $parameters = array()) {
		if (! $this->hasMethod($method)) {
			throw new BadFunctionCallException('method not found: ' . $method);
		}
		return call_user_func_array(array($this->factoryMethods[$method], $method), $parameters);
	}
	
	/**
	 * @author mnoerenberg
	 * @param string $method
	 * @return boolean
	 */
	public function hasMethod(string $method): bool {
		return array_key_exists($method, $this->factoryMethods);
	}
}