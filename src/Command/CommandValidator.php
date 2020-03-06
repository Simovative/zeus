<?php
namespace Simovative\Zeus\Command;

/**
 * @author mnoerenberg
 */
abstract class CommandValidator implements CommandValidatorInterface {
	
	/**
	 * @var string[]
	 */
	private $errors = array();
	
	/**
	 * @var boolean
	 */
	private $isValid = null;
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	private function getErrors() {
		return $this->errors;
	}
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	private function isValid() {
		if ($this->isValid === null) {
			$this->isValid = count($this->getErrors()) == 0;
		}
		return $this->isValid;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @param CommandRequest $request
	 * @return CommandValidationResult
	 */
	public function validate(CommandRequest $request) {
		$this->validateRequest($request);
		$isValid = $this->isValid();
		$errors = $this->getErrors();
		$values = $request->all();
		return new CommandValidationResult($isValid, $values, $errors);
	}
	
	/**
	 * Should only be called to the result to false, if no errors were added.
	 *
	 * @author mnoerenberg
	 * @param boolean $valid
	 * @return void
	 */
	protected function setValid($valid) {
		$this->isValid = $valid;
	}
	
	/**
	 * @author mnoerenberg
	 * @param string $fieldName
	 * @param string $message - default: ''
	 */
	protected function addError($fieldName, $message = '') {
		$this->errors[$fieldName] = $message;
	}
	
	/**
	 * Should validate the given request and add errors if necessary.
	 *
	 * @author Benedikt Schaller
	 * @param CommandRequest $commandRequest
	 * @return void
	 */
	abstract protected function validateRequest(CommandRequest $commandRequest);
}
