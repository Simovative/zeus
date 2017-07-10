<?php
namespace Simovative\Zeus\Content;

use Command\CommandValidationResult;

/**
 * Interface for validated content.
 *
 * @author Benedikt Schaller
 */
interface ValidatedContent extends Content {
	
	
	/**
	 * @author Benedikt Schaller
	 * @param CommandValidationResult $validationResult
	 * @return void
	 */
	public function setValidationResult(CommandValidationResult $validationResult);
}