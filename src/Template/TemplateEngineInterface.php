<?php
namespace Simovative\Zeus\Template;

/**
 * @author mnoerenberg
 */
interface TemplateEngineInterface {
	
	/**
	 * @author mnoerenberg
	 * @param string $placeholder
	 * @param mixed $value
	 * @return void
	 */
	public function assign($placeholder, $value);
	
	/**
	 * @author mnoerenberg
	 * @param string $path
	 * @param mixed[] $placeholderValues
	 * @return string
	 */
	public function render($path, array $placeholderValues = array());
	
	/**
	 * @author bschaller
	 * @param string $templatePath
	 * @return void
	 */
	public function addTemplatePath($templatePath);
}
