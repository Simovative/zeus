<?php
namespace Simovative\Zeus\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Simovative\Zeus\Command\CommandValidationResult;
use Simovative\Zeus\Template\BootstrapFormPopulation;

/**
 * Tests the bootstrap form population.
 */
class BootstrapFormPopulationTest extends TestCase {
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatATextFieldValueIsPopulated() {
		$amount = uniqid('amount_', true);
		$html = file_get_contents(__DIR__ . '/data/bootstrap_form.html');
		$validationResult = new CommandValidationResult(false, array('amount' => $amount));
		$formPopulation = new BootstrapFormPopulation(false);
		$populatedHtml = $formPopulation->populate($html, $validationResult);
		self::assertStringContainsString($amount, $populatedHtml);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatATextareaValueIsPopulated() {
		$comment = uniqid('comment_', true);
		$html = file_get_contents(__DIR__ . '/data/bootstrap_form.html');
		$validationResult = new CommandValidationResult(false, array('comment' => $comment));
		$formPopulation = new BootstrapFormPopulation(false);
		$populatedHtml = $formPopulation->populate($html, $validationResult);
		self::assertStringContainsString($comment, $populatedHtml);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatASelectValueIsPopulated() {
		$html = file_get_contents(__DIR__ . '/data/bootstrap_form.html');
		$validationResult = new CommandValidationResult(false, array('addressId' => 7998));
		$formPopulation = new BootstrapFormPopulation(false);
		$populatedHtml = $formPopulation->populate($html, $validationResult);
		self::assertStringContainsString(' value="7998" selected', $populatedHtml);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatOtherSelectValueAreDeselectedOnPopulation() {
		$html = file_get_contents(__DIR__ . '/data/bootstrap_form.html');
		$validationResult = new CommandValidationResult(false, array('addressId' => 7998));
		$formPopulation = new BootstrapFormPopulation(false);
		$populatedHtml = $formPopulation->populate($html, $validationResult);
		self::assertStringNotContainsString(' selected="selected" value="0"', $populatedHtml);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatACheckboxValueIsPopulated() {
		$html = file_get_contents(__DIR__ . '/data/bootstrap_form.html');
		$validationResult = new CommandValidationResult(false, array('method' => ['paypal']));
		$formPopulation = new BootstrapFormPopulation(false);
		$populatedHtml = $formPopulation->populate($html, $validationResult);
		self::assertStringContainsString(' value="paypal" name="method[]" checked', $populatedHtml);
	}
}

