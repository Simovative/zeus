<?php
namespace Simovative\Zeus\Console\SkeletonBuilder\Model\Helper;

use Simovative\Zeus\Filesystem\File;

/**
 * Helper to determine Application Namespace.
 *
 * @author shartmann
 */
class ApplicationNamespaceFinder {
	
	/**
	 * Tries to get the application Bundle class and wizardly* extract the applications
	 * namespace out of it.
	 *
	 * *using regular expressions, that is
	 *
	 * @author shartmann
	 * @param $projectRoot
	 * @return string
	 * @throws \Exception
	 */
	public function determineNamespace($projectRoot) {
		// check path for application bundle
		$applicationBundlePath = $projectRoot . '/bundles/Application';
		if (false === is_dir($applicationBundlePath)) {
			throw new \Exception('Cannot determine namespace (application bundle not found)');
		}
		
		// read application bundle files
		$files = glob($applicationBundlePath . '/*.php');
		if (false === is_array($files) || empty($files)) {
			throw new \Exception('Cannot determine namespace (application bundle contains no valid files)');
		}
		
		// check files for a namespace
		/*
		 * NOTE: Don't try to use Reflection as the application-bundles classes
		 *       might fail to load without using the correct factory
		 */
		$namespace = null;
		$matched = array();
		foreach ($files as $filePath) {
			$file = new File($filePath);
			if (1 === preg_match(
					'~(?m)namespace\s+(?<namespace>.*)\\\\Application(\s)*;(\s)*$~',
					$file->read(),
					$matched
				)
			) {
				$namespace = $matched['namespace'];
				break;
			}
		}
		if (null === $namespace) {
			throw new \Exception('Cannot determine namespace (could not parse application bundle)');
		}
		return $namespace;
	}
}