<?php
declare(strict_types=1);

namespace Simovative\Zeus\Stream;

/**
 * @author Benedikt Schaller
 */
interface StreamInterface {
	
	/**
	 * @author Benedikt Schaller
	 * @return string
	 */
	public function __toString(): string;
	
	/**
	 * Returns the remaining contents in a string
	 *
	 * @return string
	 */
	public function getContents(): string;
	
	/**
	 * Read data from the stream.
	 *
	 * @param int $length Read up to $length bytes from the object and return
	 *     them. Fewer than $length bytes may be returned if underlying stream
	 *     call returns fewer bytes.
	 * @return string Returns the data read from the stream, or an empty string
	 *     if no bytes are available.
	 */
	public function read(int $length): string;
	
	
	/**
	 * Returns true if the stream is at the end of the stream.
	 *
	 * @return bool
	 */
	public function eof(): bool;
	
	/**
	 * Closes the stream and any underlying resources.
	 *
	 * @return void
	 */
	public function close(): void;
}