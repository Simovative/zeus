<?php
namespace Simovative\Skeleton\Application;

use Simovative\Zeus\Bundle\Bundle;

/**
 * @author Benedikt Schaller
 */
class ApplicationBundle extends Bundle {
	
	/**
	 * @author mnoerenberg
	 * @inheritdoc
	 */
	protected function createBundleFactory() {
		return new ApplicationFactory();
	}
}