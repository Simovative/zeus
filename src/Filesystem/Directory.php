<?php
namespace Simovative\Zeus\Filesystem;

use Simovative\Zeus\Exception\FilesystemException;

/**
 * @author mnoerenberg
 */
class Directory {
	
	const MODE_DEFAULT = 0775;

	/**
	 * @var string
	 */
	private $path;
	
	/**
	 * @var File[]
	 */
	private $files = array();
	
	/**
	 * @var Directory[]
	 */
	private $directories = array();
	
	/**
	 * @author mnoerenberg
	 * @param string $path
	 * @param boolean $createIfNotExists
	 * @throws FilesystemException
	 */
	public function __construct($path, $createIfNotExists = false) {
		if ($path == '') {
			throw new FilesystemException('Path of directory ist empty.');
		}
		
		$this->path = $path;
		
		if ($createIfNotExists === true && ! $this->exists()) {
			$this->create();
		}
	}
	
	/**
	 * Returns the path as given to the constructor.
	 *
	 * @author mnoerenberg
	 * @return string|boolean
	 */
	public function getPath() {
		return $this->path;
	}
	
	/**
	 * Returns the name of the current folder.
	 *
	 * @author mnoerenberg
	 * @return string
	 */
	public function getName() {
		return basename($this->getPath());
	}
	
	/**
	 * Returns true if the directory exists.
	 *
	 * @author mnoerenberg
	 * @return boolean
	 */
	public function exists() {
		return file_exists($this->getPath()) && is_dir($this->getPath());
	}
	
	/**
	 * Creates the directory path if it does not exist.
	 *
	 * @author mnoerenberg
	 * @param int $mode The mode to create the directory with.
	 * @return void
	 * @throws FilesystemException
	 */
	public function create($mode = self::MODE_DEFAULT) {
		if (! $this->exists()) {
			if (false === mkdir($this->getPath(), $mode, true)) {
				throw new FilesystemException(
					sprintf('Could not create directory "%s". Permission denied.', $this->getPath())
				);
			}
		}
	}
	
	/**
	 * Change directory relative to current path.
	 *
	 * @author mnoerenberg
	 * @param string $directoryName
	 * @param bool $createIfNotExist
	 * @return Directory
	 * @throws FilesystemException
	 */
	public function change($directoryName, $createIfNotExist = true) {
		return new self($this->getPath() . '/' . $directoryName, $createIfNotExist);
	}
	
	/**
	 * Deletes the directory.
	 *
	 * @author mnoerenberg
	 * @param boolean $preserveDirectory - default: false - If true, only the files and folders it contains.
	 * @return boolean
	 * @throws FilesystemException
	 */
	public function delete($preserveDirectory = false) {
		if (! $this->exists()) {
			throw new FilesystemException(sprintf('Directory does not exist "%s".', $this->getPath()));
		}
		
		foreach ($this->getFiles() as $index => $file) {
			$file->delete();
		}
		
		foreach ($this->getDirectories() as $index => $directory) {
			$directory->delete();
		}
		
		if (! $preserveDirectory) {
			return rmdir($this->getPath());
		}
		
		return true;
	}
	
	/**
	 * Returns the objects in directory.
	 * 
	 * @author mnoerenberg
	 * @return string[]
	 */
	private function getObjects() {
		return array_diff(scandir($this->path), array('.', '..'));
	}
	
	/**
	 * Returns all files recursively in the current directory.
	 * 
	 * @author mnoerenberg
	 * @throws \Exception
	 * @return File []
	 */
	public function getFilesRecursive() {
		if (! $this->exists()) {
			throw new FilesystemException(sprintf('Directory does not exist "%s".', $this->getPath()));
		}
		
		// create file iterator for all files in bundles asset sub directories.
		$flags = \FilesystemIterator::KEY_AS_PATHNAME | \FilesystemIterator::CURRENT_AS_FILEINFO | \FilesystemIterator::SKIP_DOTS;
		$directoryIterator = new \RecursiveDirectoryIterator($this->path, $flags);
		$fileIterator = new \RecursiveIteratorIterator($directoryIterator);
		
		// for each found file in bundle assets subdirectories.
		$files = array();
		/** @noinspection PhpUnusedLocalVariableInspection */
		foreach ($fileIterator as $file) {
			$files[] = new File(realpath($fileIterator->key()));
		}
		
		return $files;
	}
	
	/**
	 * Returns the files in current directory.
	 *
	 * @author mnoerenberg
	 * @return File[]
	 * @throws FilesystemException
	 */
	public function getFiles() {
		if (! $this->exists()) {
			throw new FilesystemException(sprintf('Directory does not exist "%s".', $this->getPath()));
		}
		
		if (! empty($this->files)) {
			return $this->files;
		}
		
		foreach ($this->getObjects() as $objectName) {
			$filePath = $this->path . '/' . $objectName;
			
			if (is_dir($filePath)) {
				continue;
			}
			
			$this->files[] = new File($filePath);
		}
		
		return $this->files;
	}
	
	/**
	 * Returns the sub directories of the directory.
	 *
	 * @author mnoerenberg
	 * @return Directory[]
	 * @throws FilesystemException
	 */
	public function getDirectories() {
		if (! $this->exists()) {
			throw new FilesystemException(sprintf('Directory does not exist "%s".', $this->getPath()));
		}
		
		if (! empty($this->directories)) {
			return $this->directories;
		}
		
		foreach ($this->getObjects() as $objectName) {
			$directoryPath = $this->path . '/' . $objectName;
			
			if (! is_dir($directoryPath)) {
				continue;
			}
			
			$this->directories[] = new Directory($directoryPath);
		}
		
		return $this->directories;
	}
	
	/**
	 * Returns the directory path..
	 * 
	 * @author mnoerenberg
	 * @return string
	 */
	public function __toString() {
		return (string) $this->path;
	}
}
