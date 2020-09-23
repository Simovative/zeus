<?php
declare(strict_types=1);

namespace Simovative\Zeus\Http\Json;

/**
 * @author mnoerenberg
 */
interface JsonEncodingServiceInterface {

	/**
	 * @author mnoerenberg
	 * @param mixed $value
	 * @param int|null $options
	 * @param int|null $depth
	 * @return string|null
	 */
	public function encode($value, ?int $options = null, ?int $depth = null) : ?string;

	/**
	 * @author mnoerenberg
	 * @param string $jsonString
	 * @param bool $assoc
	 * @param int|null $depth
	 * @param int|null $options
	 * @return mixed|null
	 */
	public function decode(string $jsonString, bool $assoc = false, ?int $depth = null, ?int $options = null);
}
