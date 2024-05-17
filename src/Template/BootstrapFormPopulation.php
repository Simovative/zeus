<?php
/** @noinspection PhpComposerExtensionStubsInspection */
namespace Simovative\Zeus\Template;

use DOMDocument;
use DOMElement;
use DOMException;
use DOMXPath;
use Simovative\Zeus\Command\CommandValidationResult;

/**
 * Manipulates the HTML DOM to add field values and error messages.
 *
 * @author mnoerenberg
 */
class BootstrapFormPopulation implements FormPopulationInterface {
	
	private const ATTRIBUTE_SELECTED = 'selected';
	private const CSS_CLASS_FORM_GROUP = 'form-group';
	private const ATTRIBUTE_CLASS = 'class';
	
	/**
	 * @var bool
	 */
	private bool $isSuccessFeedbackPopulated;
	
	/**
	 * @author Benedikt Schaller
	 * @param bool $isSuccessFeedbackPopulated
	 */
	public function __construct(bool $isSuccessFeedbackPopulated) {
		$this->isSuccessFeedbackPopulated = $isSuccessFeedbackPopulated;
	}

    /**
     * @author Benedikt Schaller
     * @inheritdoc
     * @throws DOMException
     */
	public function populate($html, CommandValidationResult $validationResult): string
    {
		libxml_use_internal_errors(true);
		$domDocument = new DOMDocument();
        // Replacement for mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        $html = mb_encode_numericentity($html, [0x80, 0x10fffff, 0, 0x1fffff], "UTF-8");
		$domDocument->loadHTML($html);
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
		if ($this->isSuccessFeedbackPopulated && $validationResult->isValid()) {
			// if you have more than one form, it effects the others on the same page.
			// This is disabled by default
			$this->populateSuccessFeedback($domDocument);
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
	private function getHtml(DOMDocument $document, string $originalHtml): string
    {
		$html = $document->saveHTML();
		
		$strPos = mb_strpos($originalHtml, '<html', 0, 'UTF-8');
		if ($strPos === false) {
            /** @noinspection HtmlRequiredLangAttribute */
            $html = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace(['<html>', '</html>', '<body>', '</body>'], ['', '', '', ''], $html));
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
	private function populateTextFieldValue(DOMDocument $document, string $fieldName, $fieldValue): void
    {
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
	private function populateTextareaFieldValue(DOMDocument $document, string $fieldName, $fieldValue): void
    {
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
	private function populateSelectFieldValue(DOMDocument $document, string $fieldName, $fieldValue): void
    {
		if (is_array($fieldValue)) {
			$fieldName .= '[]';
		} else {
			$fieldValue = array($fieldValue);
		}
		$xpath = new DOMXPath($document);
		$options = $xpath->query('//select[@name="' . $fieldName . '"]/option|//select[@name="' . $fieldName . '"]/optgroup/option');
		foreach ($options as $option) {
			/* @var $option DOMElement */
			if ($option->getAttribute(self::ATTRIBUTE_SELECTED) === self::ATTRIBUTE_SELECTED) {
				$option->removeAttribute(self::ATTRIBUTE_SELECTED);
			}
			$optionValue = $option->getAttribute('value');
			if (in_array($optionValue, $fieldValue, false)) {
				$option->setAttribute(self::ATTRIBUTE_SELECTED, self::ATTRIBUTE_SELECTED);
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
	private function populateCheckboxFieldValue(DOMDocument $document, string $fieldName, $fieldValue): void
    {
		if (is_array($fieldValue)) {
			$fieldName .= '[]';
		} else {
			$fieldValue = array($fieldValue);
		}
		$xpath = new DOMXPath($document);
		$inputs = $xpath->query('//input[@name="' . $fieldName . '"][@type="checkbox"]');
		foreach ($inputs as $input) {
			/* @var $input DOMElement */
			if (in_array($input->getAttribute('value'), $fieldValue, false)) {
				$input->setAttribute('checked', 'checked');
			}
		}
	}

    /**
     * Populates a message to a field with the given name.
     *
     * @author mnoerenberg
     * @param DOMDocument $document
     * @param string $fieldName
     * @param string $textMessage
     * @return void
     * @throws DOMException
     */
	public function populateErrorFeedback(DOMDocument $document, string $fieldName, string $textMessage): void
    {
		$xpath = new DOMXPath($document);
		$inputs = $xpath->query('//input[@name="' . $fieldName . '"]');
		$selects = $xpath->query('//select[@name="' . $fieldName . '"]');
		$textAreas = $xpath->query('//textarea[@name="' . $fieldName . '"]');
		foreach (array($inputs, $selects, $textAreas) as $fields) {
			foreach ($fields as $field) {
				/* @var $field DOMElement */
				$formGroup = $this->findFormGroup($field);
				if ($formGroup === null) {
					continue;
				}
				
				if (! empty($textMessage)) {
					$this->assignTextMessage($document, $textMessage, $field);
				}
				
				$formGroup->setAttribute(self::ATTRIBUTE_CLASS, $formGroup->getAttribute(self::ATTRIBUTE_CLASS) . ' has-error');
			}
		}
	}

    /**
     * @author Benedikt Schaller
     * @param DOMDocument $document
     * @param string $textMessage
     * @param DOMElement $field
     * @return void
     * @throws DOMException
     */
	private function assignTextMessage(DOMDocument $document, string $textMessage, DOMElement $field): void {
		$helpText = $document->createElement("p", $textMessage);
		$helpText->setAttribute(self::ATTRIBUTE_CLASS, 'help-block');
		
		$parentField = $field->parentNode;
        if (
            $parentField instanceof DOMElement
            && strpos(
                $parentField->getAttribute(self::ATTRIBUTE_CLASS),
                'input-group'
            ) !== false
        ) {
            $parentField = $field->parentNode->parentNode;
        }

		
		$parentField->appendChild($helpText);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @param DOMElement $field
	 * @return DOMElement|null
	 */
	private function findFormGroup(DOMElement $field): ?DOMElement
    {
		$formGroup = $field->parentNode;
		if ($formGroup instanceof DOMElement) {
            if (strpos($formGroup->getAttribute(self::ATTRIBUTE_CLASS), self::CSS_CLASS_FORM_GROUP) === false) {
                $parentFormGroup = $field->parentNode->parentNode;
                if ($parentFormGroup instanceof DOMElement && strpos($parentFormGroup->getAttribute(self::ATTRIBUTE_CLASS), self::CSS_CLASS_FORM_GROUP) === false) {
                    $parentParentFormGroup = $field->parentNode->parentNode->parentNode;
                    if ($parentParentFormGroup instanceof DOMElement) {
                        return $parentParentFormGroup;
                    }
                }
            }

            return $formGroup;
		}
		return null;
	}
	
	/**
	 * Adds has-success to all input/select/textarea form-group which hasn't css class has-error.
	 *
	 * @author mnoerenberg
	 * @param DOMDocument $document
	 * @return void
	 */
	private function populateSuccessFeedback(DOMDocument $document): void
    {
		$xpath = new DOMXPath($document);
		$inputs = $xpath->query("//form//input");
		$selects = $xpath->query('//form//select');
		$textarea = $xpath->query('//form//textarea');
		foreach (array($inputs, $selects, $textarea) as $fields) {
			foreach ($fields as $field) {
				/* @var $field DOMElement */
				$this->assignSuccessClass($field);
			}
		}
	}
	
	/**
	 * @author Benedikt Schaller
	 * @param DOMElement $field
	 * @return void
	 */
	private function assignSuccessClass(DOMElement $field): void
    {
		$formGroup = $this->findFormGroup($field);
		if ($formGroup === null) {
			return;
		}
		
		if (strpos($formGroup->getAttribute(self::ATTRIBUTE_CLASS), 'has-error') !== false) {
			return;
		}
		
		if (strpos($field->getAttribute(self::ATTRIBUTE_CLASS), 'hidden') !== false) {
			return;
		}
		
		if (strpos($field->getAttribute('type'), 'hidden') !== false) {
			return;
		}
		
		$formGroup->setAttribute(self::ATTRIBUTE_CLASS, $formGroup->getAttribute(self::ATTRIBUTE_CLASS) . ' has-success');
	}
}
