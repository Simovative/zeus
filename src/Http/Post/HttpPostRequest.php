<?php
namespace Simovative\Zeus\Http\Post;

use Simovative\Zeus\Http\Request\HttpRequest;
use Simovative\Zeus\Http\Url\Url;

/**
 * @author mnoerenberg
 */
class HttpPostRequest extends HttpRequest {
	
	/**
	 * @var UploadedFile[]
	 */
	private $uploadedFiles;
	
	/**
	 * HttpPostRequest constructor.
	 * @param Url $url
	 * @param array|\mixed[] $parameters
	 * @param UploadedFile[] $uploadedFiles
	 */
	protected function __construct(Url $url, $parameters, $uploadedFiles) {
		parent::__construct($url, $parameters);
		$this->uploadedFiles = $uploadedFiles;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Simovative\Zeus\Http\Request\HttpRequest::isPost()
	 * @author mnoerenberg
	 */
	public function isPost() {
		return true;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return UploadedFile[]
	 */
	public function getUploadedFiles() {
		return $this->uploadedFiles;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return bool
	 */
	public function hasUploadedFiles() {
		return (0 < count($this->uploadedFiles));
	}
	
	/**
	 * @author Benedikt Schaller
	 * @param string $inputName
	 * @return UploadedFile[]
	 */
	public function getUploadedFilesOfInput($inputName) {
		$files = array();
		foreach ($this->uploadedFiles as $uploadedFile) {
			if ($uploadedFile->getInputName() != $inputName) {
				continue;
			}
			$files[$uploadedFile->getInputIndex()] = $uploadedFile;
		}
		ksort($files);
		return $files;
	}
}

