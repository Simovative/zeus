<?php
declare(strict_types=1);

namespace Simovative\Zeus\Stream;

/**
 * @author Benedikt Schaller
 */
class Stream implements StreamInterface {
	
	/**
	 * @var string
	 */
	private $streamFilename;
	
	/**
	 * @var string
	 */
	private $mode;
	
	/**
	 * @var false|resource|null
	 */
	private $handle;
	
	/**
	 * @author Benedikt Schaller
	 * @param string $streamFilename The filename of the stream to open.
	 * @param string $mode The fopen mode to use when opening the stream.
	 */
	public function __construct(string $streamFilename, string $mode) {
		$this->streamFilename = $streamFilename;
		$this->mode = $mode;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	private function open(): void {
		$this->handle = fopen($this->streamFilename, $this->mode);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return bool
	 */
	private function isOpen(): bool {
		return ($this->handle !== null && is_resource($this->handle));
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return false|resource
	 */
	private function getHandle() {
		if (false === $this->isOpen()) {
			$this->open();
		}
		return $this->handle;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritDoc
	 */
	public function __toString(): string {
		return $this->getContents();
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritDoc
	 */
	public function getContents(): string {
		return (string) stream_get_contents($this->getHandle());
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritDoc
	 */
	public function read(int $length): string {
		return (string) stream_get_contents($this->getHandle(), $length);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return bool
	 */
	public function eof(): bool {
		if (false === $this->isOpen()) {
			return false;
		}
		return feof($this->getHandle());
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritDoc
	 */
	public function close(): void {
		if (false === $this->isOpen()) {
			return;
		}
		$handle = $this->getHandle();
		fclose($handle);
	}
}