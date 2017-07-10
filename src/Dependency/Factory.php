<?php
namespace Simovative\Zeus\Dependency;

/**
 * @author mnoerenberg
 */
abstract class Factory implements FactoryInterface {
	
	/**
	 * @var MasterFactory
	 */
	private $masterFactory;
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function setMasterFactory(MasterFactory $masterFactory) {
		$this->masterFactory = $masterFactory;
	}
	
	/**
	 * @author mnoerenberg
	 * @return MasterFactory|FrameworkFactory
	 */
	protected function getMasterFactory() {
		return $this->masterFactory;
	}
	
	/**
	 * Returns a value from the application configuration.
	 *
	 * @author Benedikt Schaller
	 * @param string $key
	 * @return mixed|null
	 */
	protected function getConfigurationValue($key) {
		$configuration = $this->getMasterFactory()->getConfiguration();
		return $configuration->get($key);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return string
	 */
	protected function getBasePath() {
		return $this->getMasterFactory()->getConfiguration()->getBasePath();
	}
	
	/**
	 * Determines if this factory should be allowed to overwrite methods already registered by
	 * other factories.
	 *
	 * @author shartmann
	 * @return bool
	 */
	public function isOverwriteAllowed() {
		return false;
	}
}
