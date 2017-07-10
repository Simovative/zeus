<?php
namespace {{namespace}}\Application;

use Simovative\Zeus\Bundle\Bundle;

class {{appPrefix}}Bundle extends Bundle {
	
	/**
	 * @inheritdoc
	 */
	protected function createBundleFactory() {
		return new {{appPrefix}}Factory();
	}
}