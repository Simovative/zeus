<?php
namespace Simovative\Zeus\Dependency;

/**
 * @author mnoerenberg
 */
interface FactoryInterface {
	
	/**
	 * @author mnoerenberg
	 * @param MasterFactory $masterFactory
	 * @return void
	 */
	public function setMasterFactory(MasterFactory $masterFactory);
	
	/**
	 * Determines if this factory should be allowed to overwrite methods already registered by
	 * other factories.
	 *
	 * @author shartmann
	 * @return bool
	 */
	public function isOverwriteAllowed();
	
}
