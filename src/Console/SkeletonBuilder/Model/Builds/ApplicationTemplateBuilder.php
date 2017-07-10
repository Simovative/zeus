<?php
namespace Simovative\Zeus\Console\SkeletonBuilder\Model\Builds;

use Simovative\Zeus\Console\SkeletonBuilder\Model\AbstractTemplateBuilder;
use Simovative\Zeus\Console\SkeletonBuilder\Model\TemplateEngine;

/**
 * Assembles basic structure for application
 *
 * @author shartmann
 */
class ApplicationTemplateBuilder extends AbstractTemplateBuilder {
	
	/**
	 * @var string
	 */
	private $templateDir;
	
	/**
	 * ApplicationTemplateBuilder constructor.
	 *
	 * @author shartmann
	 * @param TemplateEngine $templateEngine
	 */
	public function __construct(TemplateEngine $templateEngine) {
		parent::__construct($templateEngine);
		$this->templateDir = $this->getTemplateRoot() . '/application';
	}
	
	/**
	 * Assembles Application skeleton.
	 *
	 * @author shartmann
	 * @param string $root path to project root
	 * @param string $namespace the applications namespace
	 * @param string $prefix an optional prefix for the application bundles classes
	 * @return void
	 */
	public function generate($root, $namespace, $prefix = '') {
		// assemble variables
		$prefix = ucfirst($prefix);
		$prefixedNamespace = $prefix . ucfirst($namespace);
		$bundlePath = $root . '/bundles/Application';
		$appPrefix = 'Application';
		if ('' !== $prefix) {
			$appPrefix = $prefix . 'Application';
		}
		
		// setup render engine
		$this->templateEngine->setPlaceholders(
			array(
				'{{namespace}}' => $namespace,
				'{{escapedNamespace}}' => str_replace('\\', '\\\\', $namespace),
				'{{prefixedNamespace}}' => $prefixedNamespace,
				'{{appPrefix}}' => $appPrefix,
				'{{prefix}}' => $prefix
			)
		);
		
		// build structure
		$this->templateEngine->render(
			$this->templateDir . '/ApplicationBundle.php.tpl',
			$bundlePath . '/' . $appPrefix . 'Bundle.php'
		);
		$this->templateEngine->render(
			$this->templateDir . '/ApplicationFactory.php.tpl',
			$bundlePath . '/' . $appPrefix . 'Factory.php'
		);
		$this->templateEngine->render(
			$this->templateDir . '/Kernel.php.tpl',
			$bundlePath . '/' . $prefix . 'Kernel.php'
		);
		$this->templateEngine->render(
			$this->templateDir . '/index.php.tpl',
			$root . '/public/index.php'
		);
		$this->templateEngine->render(
			$this->templateDir . '/setup.html',
			$bundlePath . '/setup.html'
		);
	}
}