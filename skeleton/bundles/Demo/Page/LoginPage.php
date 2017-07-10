<?php
namespace Simovative\Skeleton\Demo\Page;

use Simovative\Zeus\Content\ValidatedPage;
use Simovative\Zeus\Template\FormPopulationInterface;
use Simovative\Zeus\Template\TemplateEngineInterface;

/**
 * @author Benedikt Schaller
 */
class LoginPage extends ValidatedPage {
	
	/**
	 * @var TemplateEngineInterface
	 */
	private $templateEngine;
	
	/**
	 * @author Benedikt Schaller
	 * @param FormPopulationInterface $formPopulation
	 * @param TemplateEngineInterface $templateEngine
	 */
	public function __construct(FormPopulationInterface $formPopulation, TemplateEngineInterface $templateEngine) {
		parent::__construct($formPopulation);
		$this->templateEngine = $templateEngine;
	}
	
	/**
	 * Render the content only. The validation results will be populated automatically if necessary.
	 *
	 * @author Benedikt Schaller
	 * @return string
	 */
	protected function renderContent() {
		return $this->templateEngine->render('login.tpl');
	}
}