<?php
namespace Simovative\Zeus\Http\Post;

use Simovative\Zeus\Http\Request\HttpRequest;
use Simovative\Zeus\Http\Url\Url;
use Simovative\Zeus\Stream\StreamInterface;

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
	 *
	 * @param Url $url
	 * @param array|mixed[] $parameters
	 * @param array $serverParameters
	 * @param UploadedFile[] $uploadedFiles
	 */
	public function __construct(Url $url, array $parameters, array $serverParameters, array $uploadedFiles) {
		parent::__construct($url, $parameters, $serverParameters);
		$this->uploadedFiles = $uploadedFiles;
	}
	
	/**
	 * @author mnoerenberg
	 * @inheritDoc
	 */
	public function isPost() {
		return true;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return UploadedFile[]
	 */
	public function getUploadedFiles(): array {
		return $this->uploadedFiles;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return bool
	 */
	public function hasUploadedFiles(): bool {
		return (0 < count($this->uploadedFiles));
	}
	
	/**
	 * @author Benedikt Schaller
	 * @param string $inputName
	 * @return UploadedFile[]
	 */
	public function getUploadedFilesOfInput($inputName): array {
		$files = array();
		foreach ($this->uploadedFiles as $uploadedFile) {
			if ($uploadedFile->getInputName() !== $inputName) {
				continue;
			}
			$files[$uploadedFile->getInputIndex()] = $uploadedFile;
		}
		ksort($files);
		return $files;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return array|StreamInterface
	 */
	public function getParsedBody() {
		// to apply to PSR-7 we need to return the Post-Parameters if a normal content type is given
		$normalFormContents = [
			'application/x-www-form-urlencoded',
			'multipart/form-data'
		];
		if (in_array($this->getContentType(), $normalFormContents, true)) {
			return $this->all();
		}
		
		return parent::getParsedBody();
	}
}

