<?php
namespace Simovative\Test\Integration\TestBundle\Page;

use Simovative\Zeus\Content\ValidatedPage;
use Simovative\Zeus\Template\FormPopulationInterface;
use Simovative\Zeus\Template\TemplateEngineInterface;

/**
 * @author Benedikt Schaller
 */
class TestPage extends ValidatedPage {
	
	/**
	 * @var TemplateEngineInterface
	 */
	private $templateEngine;
	/**
	 * @var string
	 */
	private $username;
	
	/**
	 * @param FormPopulationInterface $formPopulation
	 * @param TemplateEngineInterface $templateEngine
	 * @param string $username
	 * @author Benedikt Schaller
	 */
	public function __construct(FormPopulationInterface $formPopulation, TemplateEngineInterface $templateEngine, $username) {
		parent::__construct($formPopulation);
		
		$this->templateEngine = $templateEngine;
		$this->username = $username;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritDoc
	 */
	protected function renderContent() {
		$this->templateEngine->assign('username', $this->username);
		return $this->templateEngine->render('test.tpl');
	}
}