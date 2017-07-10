<?php
namespace Simovative\Zeus\Content;

use Command\CommandValidationResult;
use Simovative\Zeus\Template\FormPopulationInterface;
use Simovative\Zeus\Template\TemplateEngineInterface;

/**
 * A base class for a page that has validated content like a html form.
 *
 * @author Benedikt Schaller
 */
abstract class ValidatedPage implements ValidatedContent {
	
	/**
	 * @var CommandValidationResult
	 */
	private $validationResult;
	/**
	 * @var FormPopulationInterface
	 */
	private $formPopulation;
	
	/**
	 * @author Benedikt Schaller
	 * @param FormPopulationInterface $formPopulation
	 */
	public function __construct(FormPopulationInterface $formPopulation) {
		$this->formPopulation = $formPopulation;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @param CommandValidationResult $validationResult
	 * @return void
	 */
	public function setValidationResult(CommandValidationResult $validationResult) {
		$this->validationResult = $validationResult;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritdoc
	 */
	public function render() {
		$content = $this->renderContent();
		if ($this->validationResult !== null) {
			$content = $this->formPopulation->populate($content, $this->validationResult);
		}
		return $content;
	}
	
	/**
	 * Render the content only. The validation results will be populated automatically if necessary.
	 *
	 * @author Benedikt Schaller
	 * @return string
	 */
	abstract protected function renderContent();
	
	/**
	 * @author Benedikt Schaller
	 * @return CommandValidationResult
	 */
	protected function getValidationResult() {
		return $this->validationResult;
	}
}