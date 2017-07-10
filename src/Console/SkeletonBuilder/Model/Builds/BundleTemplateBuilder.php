<?php
namespace Simovative\Zeus\Console\SkeletonBuilder\Model\Builds;

use Simovative\Zeus\Console\SkeletonBuilder\Model\AbstractTemplateBuilder;
use Simovative\Zeus\Console\SkeletonBuilder\Model\Helper\ApplicationNamespaceFinder;
use Simovative\Zeus\Console\SkeletonBuilder\Model\TemplateEngine;
use Simovative\Zeus\Filesystem\Directory;

/**
 * @author shartmann
 */
class BundleTemplateBuilder extends AbstractTemplateBuilder {
	
	/**
	 * @var string
	 */
	private $templateDir;
	
	/**
	 * @var ApplicationNamespaceFinder
	 */
	private $applicationNamespaceFinder;
	
	/**
	 * BundleTemplateBuilder constructor.
	 *
	 * @author shartmann
	 * @param TemplateEngine $templateEngine
	 * @param ApplicationNamespaceFinder $applicationNamespaceFinder
	 */
	public function __construct(
		TemplateEngine $templateEngine,
		ApplicationNamespaceFinder $applicationNamespaceFinder
	) {
		$this->applicationNamespaceFinder = $applicationNamespaceFinder;
		parent::__construct($templateEngine);
		$this->templateDir = $this->getTemplateRoot() . '/bundle';
	}
	
	/**
	 * @author shartmann
	 * @param string $root
	 * @param string $bundleName
	 * @throws \Exception
	 * @return void
	 */
	public function generate($root, $bundleName) {
		$namespace = $this->applicationNamespaceFinder->determineNamespace($root);
		$bundleName = ucfirst($bundleName);
		$bundlePath = $root . '/bundles/' . $bundleName;
		
		// Prepare Template Engine
		$this->templateEngine->setPlaceholders(
			array(
				'{{appNamespace}}' => $namespace,
				'{{bundleName}}' => $bundleName,
				'{{lcc_name}}' => lcfirst($bundleName)
			)
		);
		
		// Create empty directories
		foreach (array('Command', 'Page') as $path) {
			$dir = new Directory($bundlePath . '/' .$path);
			$dir->create();
		}
		
		// Render templates
		$this->templateEngine->render(
			$this->templateDir . '/Bundle.php.tpl',
			$bundlePath . '/' . $bundleName . 'Bundle.php'
		);
		$this->templateEngine->render(
			$this->templateDir . '/Factory.php.tpl',
			$bundlePath . '/' . $bundleName . 'Factory.php'
		);
		$this->templateEngine->render(
			$this->templateDir . '/GetRequestRouter.php.tpl',
			$bundlePath . '/Routing/' . $bundleName . 'GetRequestRouter.php'
		);
		$this->templateEngine->render(
			$this->templateDir . '/CommandRouter.php.tpl',
			$bundlePath . '/Routing/' . $bundleName . 'CommandRouter.php'
		);
		$this->templateEngine->render(
			$this->templateDir . '/BundleController.php.tpl',
			$bundlePath . '/Routing/' . $bundleName . 'BundleController.php'
		);
		$this->templateEngine->render(
			$this->templateDir . '/HomePage.php.tpl',
			$bundlePath . '/Page/HomePage.php'
		);
		$this->templateEngine->render(
			$this->templateDir . '/home.tpl',
			$bundlePath . '/Template/home.tpl'
		);
	}
}