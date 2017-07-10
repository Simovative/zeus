<?php
namespace Simovative\AC5\Framework;

use Simovative\Framework\Template\FormPopulation;

/**
 * FormPopulation test case.
 */
class FormPopulationTest extends \PHPUnit_Framework_TestCase {

	public function testPopulatesTextInput() {
		$amount = uniqid('amount_');
		$formPopulation = new FormPopulation(file_get_contents(__DIR__ . '/data/form.html'), array('amount' => $amount));
		$this->assertContains($amount, $formPopulation->populate());
	}
}

