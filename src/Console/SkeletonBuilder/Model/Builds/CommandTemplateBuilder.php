<?php
namespace Simovative\Zeus\Console\SkeletonBuilder\Model\Builds;

use Simovative\Zeus\Console\SkeletonBuilder\Model\AbstractTemplateBuilder;
use Simovative\Zeus\Console\SkeletonBuilder\Model\Helper\ApplicationNamespaceFinder;
use Simovative\Zeus\Console\SkeletonBuilder\Model\TemplateEngine;
use Simovative\Zeus\Filesystem\Directory;

/**
 * @author shartmann
 */
class CommandTemplateBuilder extends AbstractTemplateBuilder {
	
	/**
	 * @var ApplicationNamespaceFinder
	 */
	private $applicationNamespaceFinder;
	
	/**
	 * @var string
	 */
	private $templateDir;
	
	/**
	 * CommandTemplateBuilder constructor.
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
		$this->templateDir = $this->getTemplateRoot() . '/command';
	}
	
	/**
	 * @author shartmann
	 * @param string $root path to project root
	 * @param string $name name of the new command
	 * @param string $bundle the name of the bundle
	 * @throws \Exception
	 * @return void
	 */
	public function generate($root, $name, $bundle) {
		$bundle = ucfirst($bundle);
		$name = ucfirst($name);
		$namespace = $this->applicationNamespaceFinder->determineNamespace($root);
		$commandDir = $root . '/bundles/' . $bundle . '/Command';
		
		$directory = new Directory($root . '/bundles/' . $bundle);
		if (false === $directory->exists()) {
			throw new \LogicException('Please create bundle ' . $bundle . ' first');
		}
		
		// Prepare Template Engine
		$this->templateEngine->setPlaceholders(
			array(
				'{{appNamespace}}' => $namespace,
				'{{bundleName}}' => $bundle,
				'{{name}}' => $name
			)
		);
		
		// Render templates
		$this->templateEngine->render(
			$this->templateDir . '/Command.php.tpl',
			$commandDir . '/' . $name . 'Command.php'
		);
		$this->templateEngine->render(
			$this->templateDir . '/CommandBuilder.php.tpl',
			$commandDir . '/' . $name . 'CommandBuilder.php'
		);
		$this->templateEngine->render(
			$this->templateDir . '/CommandHandler.php.tpl',
			$commandDir . '/' . $name . 'CommandHandler.php'
		);
		$this->templateEngine->render(
			$this->templateDir . '/CommandValidator.php.tpl',
			$commandDir . '/' . $name . 'CommandValidator.php'
		);
		$this->templateEngine->render(
			$this->templateDir . '/factoryMethods.php.tpl',
			$commandDir . '/' . $name . 'CommandFactoryMethods.txt'
		);
	}
	
}