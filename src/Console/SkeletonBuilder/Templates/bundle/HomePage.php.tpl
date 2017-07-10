<?php
namespace {{appNamespace}}\{{bundleName}}\Page;

use Simovative\Zeus\Content\Content;
use Simovative\Zeus\Template\TemplateEngineInterface;

class HomePage implements Content {
	
	/**
	 * @var TemplateEngineInterface
	 */
	private $templateEngine;
	
	/**
	 * @param TemplateEngineInterface $templateEngine
	 */
	public function __construct(TemplateEngineInterface $templateEngine) {
		$this->templateEngine = $templateEngine;
	}

	/**
	 * @return mixed
	 */
	public function render() {
		return $this->templateEngine->render('home.tpl');
	}
}