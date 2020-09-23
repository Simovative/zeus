<?php
declare(strict_types=1);

namespace Simovative\Zeus\Http\Json;

use Exception;

/**
 * @author Benedikt Schaller
 */
class JsonEncodingException extends Exception {
	
	/**
	 * @author Benedikt Schaller
	 * @param string $errorMessage
	 * @param mixed $valueToEncode
	 * @return JsonEncodingException
	 */
	public static function createForEncoding(string $errorMessage, $valueToEncode): JsonEncodingException {
		switch (true) {
			case is_string($valueToEncode):
				$valueStringRepresentation = $valueToEncode;
				break;
			case is_numeric($valueToEncode):
				$valueStringRepresentation = (string) $valueToEncode;
				break;
			case $valueToEncode === null:
				$valueStringRepresentation = 'null';
				break;
			case is_array($valueToEncode):
				$valueStringRepresentation = print_r($valueToEncode, true);
				break;
			/** @noinspection PhpMissingBreakStatementInspection */
			case is_object($valueToEncode):
				if (in_array('__toString', get_class_methods($valueToEncode), true)) {
					$valueStringRepresentation = $valueToEncode->__toString();
					break;
				}
				
				$testResult = @(string) $valueToEncode;
				if (false === empty($testResult)) {
					$valueStringRepresentation = $testResult;
					break;
				}
				// Important, dont add break here, because we use the default case for objects that can not be represented as string
			default:
				$valueStringRepresentation = get_class($valueToEncode);
				if ($valueStringRepresentation === false) {
					$valueStringRepresentation = 'value can not be displayed as string';
				}
				break;
		}
		return new self(
			'Error while trying to encode value to json: ' . $errorMessage .
			PHP_EOL . 'Value to encode: ' . $valueStringRepresentation
		);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @param string $errorMessage
	 * @param string $jsonString
	 * @return JsonEncodingException
	 */
	public static function createForDecoding(string $errorMessage, string $jsonString): JsonEncodingException {
		return new self(
			'Error while trying to decode json string: ' . $errorMessage .
			PHP_EOL . 'Value to decode: ' . $jsonString
		);
	}
}