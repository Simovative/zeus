<?php
declare(strict_types=1);

namespace Simovative\Zeus\Http\Json;

use function json_encode;
use function json_decode;

/**
 * @author Benedikt Schaller
 */
final class JsonEncodingService implements JsonEncodingServiceInterface {

	private const DEFAULT_OPTIONS = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_PARTIAL_OUTPUT_ON_ERROR;
	private const DEFAULT_DEPTH = 512;
	
	/**
	 * @author Benedikt Schaller
	 * @param mixed $value
	 * @param int|null $options
	 * @param int|null $depth
	 * @return string|null
	 * @throws JsonEncodingException
	 */
	public function encode($value, ?int $options = null, ?int $depth = null) : ?string {
		if ($options === null) {
			$options = self::DEFAULT_OPTIONS;
		}

		if ($depth === null) {
			$depth = self::DEFAULT_DEPTH;
		}

		$encodedValue = json_encode($value, $options, $depth);
		$lastError = json_last_error();
		if ($lastError !== JSON_ERROR_NONE) {
			$errorMessage = json_last_error_msg();
			throw JsonEncodingException::createForEncoding($errorMessage, $value);
		}
		
		if ($encodedValue !== false) {
			return $encodedValue;
		}
		return null;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @param string $jsonString
	 * @param bool $assoc
	 * @param int|null $depth
	 * @param int|null $options
	 * @return mixed|null
	 * @throws JsonEncodingException
	 */
	public function decode(string $jsonString, bool $assoc = false, ?int $depth = null, ?int $options = null) {
		if ($options === null) {
			$options = self::DEFAULT_OPTIONS;
		}

		if ($depth === null) {
			$depth = self::DEFAULT_DEPTH;
		}

		$decodedValue = json_decode($jsonString, $assoc, $depth, $options);
		$lastError = json_last_error();
		if ($lastError !== JSON_ERROR_NONE) {
			$errorMessage = json_last_error_msg();
			throw JsonEncodingException::createForDecoding($errorMessage, $jsonString);
		}
		return $decodedValue;
	}
}
