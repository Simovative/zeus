<?php
namespace Simovative\Zeus\Template;

use Simovative\Zeus\Command\CommandValidationResult;

/**
 * Manipulates the HTML DOM to add field values and error messages.
 *
 * @author mnoerenberg
 */
interface FormPopulationInterface {
	
	/**
	 * Populates the validation results into the form of the html page.
	 *
	 * @author Benedikt Schaller
	 * @param string $html
	 * @param CommandValidationResult $validationResult
	 * @return string The populated html.
	 */
	public function populate($html, CommandValidationResult $validationResult);
}
