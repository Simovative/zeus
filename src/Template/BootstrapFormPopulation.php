<?php
/** @noinspection PhpComposerExtensionStubsInspection */
namespace Simovative\Zeus\Template;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Simovative\Zeus\Command\CommandValidationResult;

/**
 * Manipulates the HTML DOM to add field values and error messages.
 *
 * @author mnoerenberg
 */
class BootstrapFormPopulation implements FormPopulationInterface {
	
	/**
	 * @author Benedikt Schaller
	 * @inheritdoc
	 */
	public function populate($html, CommandValidationResult $validationResult) {
		libxml_use_internal_errors(true);
		$domDocument = new DOMDocument();
		$domDocument->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8"));
		libxml_use_internal_errors(true);
		$domDocument->encoding = 'UTF-8';
		
		// add field errors
		foreach ($validationResult->getErrors() as $fieldName => $textMessage) {
			$this->populateErrorFeedback($domDocument, $fieldName, $textMessage);
		}
		
		// add field values
		foreach ($validationResult->getValues() as $fieldName => $fieldValue) {
			$this->populateTextFieldValue($domDocument, $fieldName, $fieldValue);
			$this->populateTextareaFieldValue($domDocument, $fieldName, $fieldValue);
			$this->populateCheckboxFieldValue($domDocument, $fieldName, $fieldValue);
			$this->populateSelectFieldValue($domDocument, $fieldName, $fieldValue);
		}
		
		// add has-success feedback
		if ($validationResult->isValid()) {
			// if you have more than one form, it effects the others on the same page.
			//$this->populateSuccessFeedback();
		}
		
		return $this->getHtml($domDocument, $html);
	}
	
	
	/**
	 * Returns the populated html content.
	 *
	 * @author mnoerenberg
	 * @param DOMDocument $document
	 * @param string $originalHtml
	 * @return string
	 */
	private function getHtml(DOMDocument $document, $originalHtml) {
		$html = $document->saveHTML();
		
		$strPos = mb_strpos($originalHtml, '<html', null, 'UTF-8');
		if ($strPos === false) {
			$html = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace( array('<html>', '</html>', '<body>', '</body>'), array('', '', '', ''), $html));
		}
		return $html;
	}
	
	/**
	 * Populates a value to all text fields with the given name.
	 *
	 * @author mnoerenberg
	 * @param DOMDocument $document
	 * @param string $fieldName
	 * @param mixed $fieldValue
	 * @return void
	 */
	private function populateTextFieldValue(DOMDocument $document, $fieldName, $fieldValue) {
		$xpath = new DOMXPath($document);
		$inputs = $xpath->query('//input[@name="' . $fieldName . '"]');
		foreach ($inputs as $input) {
			/* @var $input DOMElement */
			if (in_array($input->getAttribute('type'), array('checkbox', 'radio', 'password'))) {
				continue;
			}
			$input->setAttribute('value', $fieldValue);
		}
	}
	
	/**
	 * Populates a value to all textareas with the given name.
	 *
	 * @author mnoerenberg
	 * @param DOMDocument $document
	 * @param string $fieldName
	 * @param mixed $fieldValue
	 * @return void
	 */
	private function populateTextareaFieldValue(DOMDocument $document, $fieldName, $fieldValue) {
		$xpath = new DOMXPath($document);
		$areas = $xpath->query('//textarea[@name="' . $fieldName . '"]');
		
		foreach ($areas as $area) {
			/* @var $area DOMElement */
			$area->nodeValue = $fieldValue;
		}
	}
	
	/**
	 * Populates a value to all select fields with the given name.
	 *
	 * @author mnoerenberg
	 * @param DOMDocument $document
	 * @param string $fieldName
	 * @param mixed $fieldValue
	 * @return void
	 */
	private function populateSelectFieldValue(DOMDocument $document, $fieldName, $fieldValue) {
		if (is_array($fieldValue)) {
			$fieldName .= '[]';
		} else {
			$fieldValue = array($fieldValue);
		}
		$xpath = new DOMXPath($document);
		$options = $xpath->query('//select[@name="' . $fieldName . '"]/option|//select[@name="' . $fieldName . '"]/optgroup/option');
		foreach ($options as $option) {
			/* @var $option DOMElement */
			if ($option->getAttribute('selected') === 'selected') {
				$option->removeAttribute('selected');
			}
			$optionValue = $option->getAttribute('value');
			if (in_array($optionValue, $fieldValue)) {
				$option->setAttribute('selected', 'selected');
			}
		}
	}
	
	/**
	 * Populates a value to all checkbox fields with the given name.
	 *
	 * @author mnoerenberg
	 * @param DOMDocument $document
	 * @param string $fieldName
	 * @param mixed $fieldValue
	 * @return void
	 */
	private function populateCheckboxFieldValue(DOMDocument $document, $fieldName, $fieldValue) {
		if (is_array($fieldValue)) {
			$fieldName .= '[]';
		} else {
			$fieldValue = array($fieldValue);
		}
		$xpath = new DOMXPath($document);
		$inputs = $xpath->query('//input[@name="' . $fieldName . '"][@type="checkbox"]');
		foreach ($inputs as $input) {
			/* @var $input DOMElement */
			if (in_array($input->getAttribute('value'), $fieldValue)) {
				$input->setAttribute('checked', 'checked');
			}
		}
	}
	
	/**
	 * Populates a the message to a field with the given name.
	 *
	 * @author mnoerenberg
	 * @param DOMDocument $document
	 * @param string $fieldName
	 * @param string $textMessage
	 * @return void
	 */
	public function populateErrorFeedback(DOMDocument $document, $fieldName, $textMessage) {
		$xpath = new DOMXPath($document);
		$inputs = $xpath->query('//input[@name="' . $fieldName . '"]');
		$selects = $xpath->query('//select[@name="' . $fieldName . '"]');
		$textareas = $xpath->query('//textarea[@name="' . $fieldName . '"]');
		foreach (array($inputs, $selects, $textareas) as $fields) {
			foreach ($fields as $field) {
				/* @var $field DOMElement */
				$formGroup = $field->parentNode;
				if (strpos($formGroup->getAttribute('class'), 'form-group') === false) {
					$formGroup = $field->parentNode->parentNode;
					if (strpos($formGroup->getAttribute('class'), 'form-group') === false) {
						$formGroup = $field->parentNode->parentNode->parentNode;
					}
				}
				
				if (! empty($textMessage)) {
					$helpText = $document->createElement("p", $textMessage);
					$helpText->setAttribute('class', 'help-block');
					
					$parentField = $field->parentNode;
					if (strstr($parentField->getAttribute('class'), 'input-group') == true) {
						$parentField = $field->parentNode->parentNode;
					}
					
					$parentField->appendChild($helpText);
				}
				
				$formGroup->setAttribute('class', $formGroup->getAttribute('class') . ' has-error');
			}
		}
	}
	
	/**
	 * Adds has-success to all input/select/textarea form-group which hasn't css class has-error.
	 *
	 * @author mnoerenberg
	 * @param DOMDocument $document
	 * @return void
	 */
	private function populateSuccessFeedback(DOMDocument $document) {
		$xpath = new DOMXPath($document);
		$inputs = $xpath->query("//form//input");
		$selects = $xpath->query('//form//select');
		$textarea = $xpath->query('//form//textarea');
		foreach (array($inputs, $selects, $textarea) as $fields) {
			foreach ($fields as $field) {
				/* @var $field DOMElement */
	
				$formGroup = $field->parentNode;
				if (strpos($formGroup->getAttribute('class'), 'form-group') === false) {
					$formGroup = $field->parentNode->parentNode;
					if (strpos($formGroup->getAttribute('class'), 'form-group') === false) {
						$formGroup = $field->parentNode->parentNode->parentNode;
					}
				}
	
				if (strpos($formGroup->getAttribute('class'), 'has-error') !== false) {
					continue;
				}
	
				if (strpos($field->getAttribute('class'), 'hidden') !== false) {
					continue;
				}
	
				if (strpos($field->getAttribute('type'), 'hidden') !== false) {
					continue;
				}
	
				$helpText = $document->createElement("p", '&nbsp;');
				$helpText->setAttribute('class', 'help-block');
	
				//$field->parentNode->appendChild($helpText);
				$formGroup->setAttribute('class', $formGroup->getAttribute('class') . ' has-success');
			}
		}
	}
}
