<?php
namespace Simovative\Zeus\Console\SkeletonBuilder\Model\Builds;

use Simovative\Zeus\Console\SkeletonBuilder\Model\AbstractTemplateBuilder;
use Simovative\Zeus\Console\SkeletonBuilder\Model\Helper\ApplicationNamespaceFinder;
use Simovative\Zeus\Console\SkeletonBuilder\Model\TemplateEngine;
use Simovative\Zeus\Filesystem\Directory;

/**
 * @author shartmann
 */
class PageTemplateBuilder extends AbstractTemplateBuilder {
	
	const TYPE_PAGE = 0;
	const TYPE_VALIDATED_PAGE = 1;
	
	/**
	 * @var ApplicationNamespaceFinder
	 */
	private $applicationNamespaceFinder;
	
	/**
	 * @var string
	 */
	private $templatePath;
	
	/**
	 * PageTemplateBuilder constructor.
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
		$this->templatePath = $this->getTemplateRoot() . '/page';
	}
	
	/**
	 * @author shartmann
	 * @param string $root Path to project root
	 * @param string $name name of the new page
	 * @param string $bundle name of the bundle
	 * @param int $type type of page, see class constants
	 * @throws \Exception
	 * @return void
	 */
	public function generate($root, $name, $bundle, $type = self::TYPE_PAGE) {
		$bundle = ucfirst($bundle);
		$name = ucfirst($name);
		$namespace = $this->applicationNamespaceFinder->determineNamespace($root);
		$pageDir = $root . '/bundles/' . $bundle . '/Page';
		
		$directory = new Directory($root . '/bundles/' . $bundle);
		if (false === $directory->exists()) {
			throw new \LogicException('Create Bundle first');
		}
		
		$this->templateEngine->setPlaceholders(
			array(
				'{{appNamespace}}' => $namespace,
				'{{bundleName}}' => $bundle,
				'{{name}}' => $name
			)
		);
		
		switch ($type) {
			case self::TYPE_PAGE:
				$this->renderPage($name, $pageDir);
				return;
			case self::TYPE_VALIDATED_PAGE:
				$this->renderValidatedPage($name, $pageDir);
				return;
			default:
				throw new \InvalidArgumentException('Unknown page type');
		}
	}
	
	/**
	 * @author shartmann
	 * @param string $name name of the page
	 * @param string $pageDir directory where to put the page
	 * @return void
	 */
	private function renderPage($name, $pageDir) {

		$this->templateEngine->addPlaceholder('{{type}}', 'Page');
		$this->templateEngine->addPlaceholder('{{constructorParameter}}', '$this->getMasterFactory()->createTemplateEngine()');

		$this->templateEngine->render(
			$this->templatePath . '/Page.php.tpl',
			$pageDir . '/' . $name . 'Page.php'
		);
		$this->templateEngine->render(
			$this->templatePath . '/factoryMethods.php.tpl',
			$pageDir . '/' . $name . 'PageFactoryMethods.txt'
		);
	}
	
	/**
	 * @author shartmann
	 * @param string $name name of the page
	 * @param string $pageDir directory where to put the page
	 * @return void
	 */
	private function renderValidatedPage($name, $pageDir) {

		$this->templateEngine->addPlaceholder('{{type}}', 'Form');
		$this->templateEngine->addPlaceholder('{{constructorParameter}}', '$this->getMasterFactory()->createFormPopulation(), $this->getMasterFactory()->createTemplateEngine()');

		$this->templateEngine->render(
			$this->templatePath . '/ValidatedPage.php.tpl',
			$pageDir . '/' . $name . 'Form.php'
		);
		$this->templateEngine->render(
			$this->templatePath . '/factoryMethods.php.tpl',
			$pageDir . '/' . $name . 'FormFactoryMethods.txt'
		);
	}
}