<?php
namespace {{appNamespace}}\{{bundleName}}\Page;

use Simovative\Zeus\Content\ValidatedPage;
use Simovative\Zeus\Template\FormPopulationInterface;
use Simovative\Zeus\Template\TemplateEngineInterface;

class {{name}}Form extends ValidatedPage {
	
	/**
	 * @var TemplateEngineInterface
	 */
	private $templateEngine;
	
	/**
	 * @param FormPopulationInterface $formPopulation
	 * @param TemplateEngineInterface $templateEngine
	 */
	public function __construct(FormPopulationInterface $formPopulation, TemplateEngineInterface $templateEngine) {
		parent::__construct($formPopulation);
		$this->templateEngine = $templateEngine;
	}

	/**
	* @return string
	*/
	protected function renderContent() {
		return $this->templateEngine->render('{{name}}.tpl');
	}
}