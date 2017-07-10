<?php
namespace Simovative\Zeus\Console\SkeletonBuilder\Model;

use Simovative\Zeus\Filesystem\Directory;
use Simovative\Zeus\Filesystem\File;

/**
 * "TemplateEngine" might sound a little far fetched. This class
 * basically just loads a file and replaces placeholders in the
 * most simple way (meaning str_replace). There is no mechanism
 * to escape something or to prevent a string from being replaced,
 * so use this thing with care.
 * It is kept simple on purpose, because any more sophisticated
 * solution would have been overkill.
 *
 * @author shartmann
 */
class TemplateEngine {
	
	/**
	 * Key-Value store for placeholders to be replaced
	 *
	 * @var array
	 */
	private $placeholders = array();
	
	/**
	 * @author shartmann
	 * @param array $placeholders placeholders (key) and replacements (value)
	 * @return $this
	 */
	public function setPlaceholders(array $placeholders) {
		$this->placeholders = $placeholders;
		return $this;
	}
	
	/**
	 * @author shartmann
	 * @param string $needle
	 * @param string $replacement
	 * @return $this
	 */
	public function addPlaceholder($needle, $replacement) {
		$this->placeholders[$needle] = $replacement;
		return $this;
	}
	
	/**
	 * Loads source template, replaces placeholders and puts the rendered file to its destination.
	 * NOTE: Any folders not already present of the path will be created along the way.
	 *
	 * @author shartmann
	 * @param string $source Template to load
	 * @param string $destination Path and filename of where to put the rendered file
	 * @return void
	 */
	public function render($source, $destination) {
		$this->generatePath($destination);
		$file = new File($destination);
		$file->create();
		$file->write($this->readTemplate($source));
	}
	
	/**
	 * @author shartmann
	 * @param string $file
	 */
	private function generatePath($file) {
		$directory = new Directory(dirname($file));
		$directory->create();
	}
	
	/**
	 * @author shartmann
	 * @param string $source
	 * @return string
	 */
	private function readTemplate($source) {
		$file = new File($source);
		return str_replace(
			array_keys($this->placeholders),
			array_values($this->placeholders),
			$file->read()
		);
	}
	
}