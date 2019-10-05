<?php
namespace Simovative\Zeus\Filesystem;

use Simovative\Zeus\Exception\FilesystemException;

/**
 * @author mnoerenberg
 */
class File {
	
	/**
	 * @var string
	 */
	private $path;
	
	/**
	 * @author mnoerenberg
	 * @param string $path
	 * @param boolean $createIfNotExists
	 */
	public function __construct($path, $createIfNotExists = false) {
		$this->path = $path;
		
		if ($createIfNotExists) {
			$this->create();
		}
	}
	
	/**
	 * Returns true if file exists.
	 *
	 * @author mnoerenberg
	 * @return boolean
	 */
	public function exists() {
		return file_exists($this->path);
	}
	
	/**
	 * Creates the file, if it does not exist.
	 *
	 * @author mnoerenberg
	 * @return boolean
	 */
	public function create() {
		if ($this->exists()) {
			return false;
		}
		
		touch($this->path);
		$this->changeMode(0775);
		
		return true;
	}
	
	/**
	 * Writes the given content.
	 *
	 * @author mnoerenberg
	 * @param string $content
	 * @return bool
	 * @throws \Exception
	 */
	public function write($content) {
		if (! $this->exists()) {
			throw new \Exception('file ' . $this->path . ' not found');
		}
		
		file_put_contents($this->path, $content);
		return true;
	}
	
	/**
	 * Removes the files content. Attention!
	 * 
	 * @author mnoerenberg
	 * @throws \Exception
	 * @return void
	 */
	public function clearContent() {
		if (! $this->exists()) {
			throw new \Exception('file not found');
		}
		
		file_put_contents($this->path, '');
	}
	
	/**
	 * Appends the given content.
	 *
	 * @author mnoerenberg
	 * @param string $content
	 * @return boolean
	 * @throws FilesystemException
	 */
	public function append($content) {
		if (! $this->exists()) {
			throw new FilesystemException(sprintf('File does not exist "%s".', $this->getPath()));
		}
		
		file_put_contents($this->path, $content, FILE_APPEND);
		
		return true;
	}
	
	/**
	 * Returns the file content as string.
	 *
	 * @author mnoerenberg
	 * @return string
	 * @throws FilesystemException
	 */
	public function read() {
		if (! $this->exists()) {
			throw new FilesystemException(sprintf('File does not exist "%s".', $this->getPath()));
		}
		
		if ($this->getSize() > 0) {
			return file_get_contents($this->path);
		}
		
		return '';
	}
	
	/**
	 * Returns the size of the file in bytes.
	 *
	 * @author mnoerenberg
	 * @return int
	 * @throws FilesystemException
	 */
	public function getSize() {
		if (! $this->exists()) {
			throw new FilesystemException(sprintf('File does not exist "%s".', $this->getPath()));
		}
		
		return filesize($this->path);
	}
	
	/**
	 * Returns the file extension.
	 * 
	 * @author mnoerenberg
	 * @return string
	 */
	public function getExtension() {
		return pathinfo($this->path, PATHINFO_EXTENSION);
	}
	
	/**
	 * Deletes the file.
	 *
	 * @author mnoerenberg
	 * @return boolean
	 * @throws FilesystemException
	 */
	public function delete() {
		if (! $this->exists()) {
			throw new FilesystemException(sprintf('Directory does not exist "%s".', $this->getPath()));
		}
		
		return unlink($this->path);
	}
	
	/**
	 * Copy the current file to a target destination.
	 *
	 * @author mnoerenberg
	 * @param File $destinationFile
	 * @param boolean $createDir
	 * @return void
	 * @throws FilesystemException
	 */
	public function copy(File $destinationFile, $createDir = true) {
		if ($destinationFile->exists()) {
			throw new FilesystemException(sprintf('Target file already exists "%s".', $destinationFile->getPath()));
		}
		
		// create directory if not exists.
		if ($createDir) {
			$destinationFilesDirectory = $destinationFile->getDirectory();
			if (! $destinationFilesDirectory->exists()) {
				$destinationFilesDirectory->create();
			}
		}
		
		copy($this->path, $destinationFile->getPath());
		$destinationFile->changeMode(0775);
	}
	
	/**
	 * Returns the timestamp of the last change.
	 *
	 * @author mnoerenberg
	 * @return \DateTime|false
	 * @throws FilesystemException
	 */
	public function getLastChangeTimestamp() {
		if (! $this->exists()) {
			throw new FilesystemException(sprintf('File does not exist "%s".', $this->getPath()));
		}
		
		return new \DateTime(filemtime($this->path));
	}
	
	/**
	 * Returns the name of the file.
	 *
	 * @author mnoerenberg
	 * @param boolean $hideExtension - default: false
	 * @return string
	 */
	public function getName($hideExtension = false) {
		if ($hideExtension) {
			return basename($this->path, '.' . $this->getExtension());
		}
		
		return basename($this->path);
	}
	
	/**
	 * Changes the permission mode of the file.
	 *
	 * @author mnoerenberg
	 * @param string $mode
	 * @return void
	 */
	public function changeMode($mode) {
		chmod($this->path, $mode);
	}

	/**
	 * Returns the permission mode of the file.
	 *
	 * @author mnoerenberg
	 * @return string
	 */
	public function getMode() {
		return substr(sprintf('%o', fileperms($this->path)), -4);
	}

	/**
	 * Returns the path of the file.
	 *
	 * @author mnoerenberg
	 * @return string
	 */
	public function getPath() {
		return $this->path;
	}
	
	/**
	 * Returns the directory of the file.
	 *
	 * @author mnoerenberg
	 * @return Directory
	 * @throws FilesystemException
	 */
	public function getDirectory() {
		return new Directory(dirname($this->path));
	}
	
	/**
	 * Returns the number of lines.
	 * 
	 * @author mnoerenberg
	 * @return int
	 */
	public function countLines() {
		$lineCount = 0;
		$handle = fopen($this->path, "r");
		while(!feof($handle)){
			$line = fgets($handle, 4096);
			$lineCount = $lineCount + substr_count($line, PHP_EOL);
		}
		fclose($handle);
		
		return $lineCount;
	}
	
	/**
	 * Get the tail of a file.
	 *
	 * @author http://www.codediesel.com/php/tail-functionality-in-php
	 * @param int $lines
	 * @return string
	 * @throws FilesystemException
	 */
	public function tail($lines = 10) {
		if (! $this->exists()) {
			throw new FilesystemException(sprintf('File does not exist "%s".', $this->getPath()));
		}
		
		$data = '';
		$fp = fopen($this->path, "r");
		$block = 4096;
		$max = filesize($this->path);
		
		for ($len = 0; $len < $max; $len += $block) {
			$seekSize = ($max - $len > $block) ? $block : $max - $len;
			fseek($fp, ($len + $seekSize) * - 1, SEEK_END);
			$data = fread($fp, $seekSize) . $data;
				
			if (substr_count($data, "\n") >= $lines + 1) {
				/* Make sure that the last line ends with a '\n' */
				if (substr($data, strlen($data) - 1, 1) !== "\n") {
					$data .= "\n";
				}
	
				preg_match("!(.*?\n){" . $lines . "}$!", $data, $match);
				fclose($fp);
				return $match[0];
			}
		}
		
		fclose($fp);
		return $data;
	}

	/**
	 * @author Benedikt Schaller
	 * @return string
	 */
	public function getMimeType() {
		$mimeType = new FileMimeType($this);
		return $mimeType->getMimeType();
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return bool
	 */
	public function touch() {
		return touch($this->getPath());
	}
}
