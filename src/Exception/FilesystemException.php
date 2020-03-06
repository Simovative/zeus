<?php
namespace Simovative\Zeus\Exception;

use Exception;

/**
 * @author Benedikt Schaller
 */
class FilesystemException extends Exception {
	
	/**
	 * @author Benedikt Schaller
	 * @param string $directory
	 * @return FilesystemException
	 */
	public static function createDirectoryDoesNotExist(string $directory): FilesystemException {
		return new self(sprintf('Directory does not exist "%s"', $directory));
	}
	
	/**
	 * @author Benedikt Schaller
	 * @param string $file
	 * @return FilesystemException
	 */
	public static function createFileDoesNotExist(string $file): FilesystemException {
		return new self(sprintf('File does not exist "%s"', $file));
	}
}