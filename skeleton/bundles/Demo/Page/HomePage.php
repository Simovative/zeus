<?php
namespace Simovative\Skeleton\Demo\Page;

use Simovative\Zeus\Content\Content;
use Simovative\Zeus\Template\TemplateEngineInterface;

/**
 * @author Benedikt Schaller
 */
class HomePage implements Content {
	
	/**
	 * @var TemplateEngineInterface
	 */
	private $templateEngine;
	/**
	 * @var string
	 */
	private $username;
	
	/**
	 * @author Benedikt Schaller
	 * @param TemplateEngineInterface $templateEngine
	 * @param string $username
	 */
	public function __construct(TemplateEngineInterface $templateEngine, $username) {
		$this->templateEngine = $templateEngine;
		$this->username = $username;
	}
	
	/**
	 *
	 * Renders the given content.
	 *
	 * @author mnoerenberg
	 * @return mixed
	 */
	public function render() {
		$this->templateEngine->assign('username', $this->username);
		return $this->templateEngine->render('home.tpl');
	}
}