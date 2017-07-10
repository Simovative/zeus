<?php
namespace Simovative\Zeus\Console\SkeletonBuilder\Model;

/**
 * All template builders need at least a TemplateEngine
 *
 * @author shartmann
 */
abstract class AbstractTemplateBuilder {
	
	/**
	 * @var TemplateEngine
	 */
	protected $templateEngine;
	
	/**
	 * @var string
	 */
	private $templateRoot;
	
	/**
	 * AbstractTemplateBuilder constructor.
	 *
	 * @author shartmann
	 * @param TemplateEngine $templateEngine
	 */
	public function __construct(TemplateEngine $templateEngine) {
		$this->templateEngine = $templateEngine;
		$this->templateRoot = __DIR__ . '/../Templates';
	}
	
	/**
	 * @author shartmann
	 * @return string
	 */
	protected function getTemplateRoot() {
		return $this->templateRoot;
	}
	
}