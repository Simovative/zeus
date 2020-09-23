<?php
declare(strict_types=1);

namespace Simovative\Zeus\Http\Json;

use function json_encode;
use function json_decode;

/**
 * @author mnoerenberg
 */
final class JsonEncodingService implements JsonEncodingServiceInterface {

	private const DEFAULT_OPTIONS = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_PARTIAL_OUTPUT_ON_ERROR;
	private const DEFAULT_DEPTH = 512;

	/**
	 * @author mnoerenberg
	 * @param mixed $value
	 * @param int|null $options
	 * @param int|null $depth
	 * @return string|null
	 */
	public function encode($value, ?int $options = null, ?int $depth = null) : ?string {
		if ($options === null) {
			$options = self::DEFAULT_OPTIONS;
		}

		if ($depth === null) {
			$depth = self::DEFAULT_DEPTH;
		}

		$value = json_encode($value, $options, $depth);

		if ($value !== false) {
			return $value;
		}

		return null;
	}

	/**
	 * @author mnoerenberg
	 * @param string $jsonString
	 * @param bool $assoc
	 * @param int|null $depth
	 * @param int|null $options
	 * @return mixed|null
	 */
	public function decode(string $jsonString, bool $assoc = false, ?int $depth = null, ?int $options = null) {
		if ($options === null) {
			$options = self::DEFAULT_OPTIONS;
		}

		if ($depth === null) {
			$depth = self::DEFAULT_DEPTH;
		}

		return json_decode($jsonString, $assoc, $depth, $options);
	}
}
