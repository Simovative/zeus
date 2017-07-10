<?php
namespace Simovative\Zeus\Content;

use Simovative\Zeus\Filesystem\File as FilesystemFile;

/**
 * @author Benedikt Schaller
 */
class File implements Content {

	/**
	 * @var FilesystemFile
	 */
	private $file;

	/**
	 * @author Benedikt Schaller
	 * @param FilesystemFile $file
	 */
	public function __construct(FilesystemFile $file) {
		$this->file = $file;
	}

	/**
	 * Returns the File.
	 *
	 * @author Benedikt Schaller
	 * @return FilesystemFile
	 */
	public function getFile() {
		return $this->file;
	}

	/**
	 *
	 * Renders the given content.
	 *
	 * @author mnoerenberg
	 * @return mixed
	 */
	public function render() {
		//return $this->file->read();
	}
}
