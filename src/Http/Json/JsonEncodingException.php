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
		$valueStringRepresentation = self::getVariableStringRepresentation($valueToEncode);
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
	
	/**
	 * @author Benedikt Schaller
	 * @param mixed $variable
	 * @return string
	 */
	private static function getVariableStringRepresentation($variable): string {
		switch (gettype($variable)) {
			case 'boolean':
			case 'integer':
			case 'double':
			case 'string':
				return (string) $variable;
			case 'NULL':
				return 'null';
			case 'array':
				return print_r($variable, true);
			case 'object':
				if (in_array('__toString', get_class_methods($variable), true)) {
					return $variable->__toString();
				}
				
				return get_class($variable);
			default:
				return gettype($variable);
		}
	}
}