<?php
declare(strict_types=1);

namespace Simovative\Zeus\Http\Json;

/**
 * @author Benedikt Schaller
 */
interface JsonEncodingServiceInterface {

	/**
	 * @author Benedikt Schaller
	 * @param mixed $value
	 * @param int|null $options
	 * @param int|null $depth
	 * @return string|null
	 * @throws JsonEncodingException
	 */
	public function encode($value, ?int $options = null, ?int $depth = null) : ?string;

	/**
	 * @author Benedikt Schaller
	 * @param string $jsonString
	 * @param bool $assoc
	 * @param int|null $depth
	 * @param int|null $options
	 * @return mixed|null
	 * @throws JsonEncodingException
	 */
	public function decode(string $jsonString, bool $assoc = false, ?int $depth = null, ?int $options = null);
}
