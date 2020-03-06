<?php
namespace Simovative\Zeus\Command;

/**
 * Result of a command validation.
 *
 * @author Benedikt Schaller
 */
class CommandValidationResult {
	
	/**
	 * @var bool
	 */
	private $isValid;
	/**
	 * @var array|\string[]
	 */
	private $values;
	/**
	 * @var array|\string[]
	 */
	private $errors;
	
	/**
	 * @author Benedikt Schaller
	 * @param bool $isValid
	 * @param string[] $values Field names mapped to its values.
	 * @param string[] $errors Field names mapped to its validation error message.
	 */
	public function __construct($isValid, $values = array(), $errors = array()) {
		$this->isValid = $isValid;
		$this->values = $values;
		$this->errors = $errors;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return bool
	 */
	public function isValid() {
		return $this->isValid;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return array|\string[]
	 */
	public function getValues() {
		return $this->values;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return array|\string[]
	 */
	public function getErrors() {
		return $this->errors;
	}
}