<?php
namespace Simovative\Zeus\Http\Response;

use Simovative\Zeus\Content\File;

/**
 * @author mnoerenberg
 */
class HttpResponseFile extends HttpResponse {
	
	/**
	 * @author mnoerenberg
	 * @param File $file
	 */
	public function __construct(File $file) {
		$this->addHeader('X-Sendfile: ' . $file->getFile()->getPath());
		$this->addHeader('HTTP/1.1 200 OK');
		$this->addHeader('Content-Type: ' . $this->detectMimeType($file));
		$this->addHeader('Content-Length: ' . $file->getFile()->getSize());
		$this->addHeader('Content-Disposition: attachment; filename="' . $file->getFile()->getName() . '"');
	}
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	protected function getBody() {
	}

	/**
	 * @author Benedikt Schaller
	 * @param File $file
	 * @return string
	 */
	private function detectMimeType(File $file) {
		if ($file->getFile()->getExtension() === 'css') {
			return 'text/css';
		}
		return $file->getFile()->getMimeType();
	}
}
