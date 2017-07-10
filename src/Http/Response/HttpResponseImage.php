<?php
namespace Simovative\Zeus\Http\Response;

use Simovative\Zeus\Content\Image;
use Simovative\Zeus\Filesystem\File;

/**
 * @author mnoerenberg
 */
class HttpResponseImage extends HttpResponse {
	
	/**
	 * @var File
	 */
	private $file;
	
	/**
	 * @author mnoerenberg
	 * @param Image $image
	 * @throws \Exception
	 */
	public function __construct(Image $image) {
		$this->file = $image->render();	
		$this->addHeader('HTTP/1.1 200 OK');
		$this->addHeader('Content-Type: image/' . $this->getContentType($this->file));
		$this->addHeader('Content-Length: ' . $this->file->getSize());
	}
	
	/**
	 * @author mnoerenberg
	 * @return string
	 */
	private function getContentType(File $file) {
		switch ($file->getExtension()) {
			case 'jpg': return 'jpeg'; break; 
			case 'jpeg': return 'jpeg'; break; 
			case 'png': return 'png'; break; 
			case 'bmp': return 'bmp'; break; 
			case 'gif': return 'gif'; break; 
			default: return 'jpeg'; break;
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Simovative\Zeus\Http\Response\HttpResponse::getBody()
	 * @author mnoerenberg
	 */
	protected function getBody() {
		return $this->file->read();
	}
}
