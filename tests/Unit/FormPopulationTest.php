<?php
namespace Simovative\Zeus\Template;

use Command\CommandValidationResult;

/**
 * Tests the bootstrap form population.
 */
class BootstrapFormPopulationTest extends \PHPUnit_Framework_TestCase {
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatATextFieldValueIsPopulated() {
		$amount = uniqid('amount_');
		$html = file_get_contents(__DIR__ . '/data/bootstrap_form.html');
		$validationResult = new CommandValidationResult(false, array('amount' => $amount));
		$formPopulation = new BootstrapFormPopulation();
		$populatedHtml = $formPopulation->populate($html, $validationResult);
		$this->assertContains($amount, $populatedHtml);
	}
}

