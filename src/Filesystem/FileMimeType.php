<?php /** @noinspection PhpComposerExtensionStubsInspection */
namespace Simovative\Zeus\Filesystem;

/**
 * @author Benedikt Schaller
 */
class FileMimeType {
	
	private const FILE_PROTOCOL_FILE = 'file';
	
	/**
	 * @var File
	 */
	private $file;
	
	/**
	 * @author Benedikt Schaller
	 * @param File $file
	 */
	public function __construct(File $file) {
		$this->file = $file;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return string|null
	 */
	private function getMimeTypeByExtension(): ?string {
		$mimeTypes = array(
			
			'txt' => 'text/plain',
			'htm' => 'text/html',
			'html' => 'text/html',
			'php' => 'text/html',
			'css' => 'text/css',
			'js' => 'application/javascript',
			'json' => 'application/json',
			'xml' => 'application/xml',
			'swf' => 'application/x-shockwave-flash',
			'flv' => 'video/x-flv',
			
			// images
			'png' => 'image/png',
			'jpe' => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'jpg' => 'image/jpeg',
			'gif' => 'image/gif',
			'bmp' => 'image/bmp',
			'ico' => 'image/vnd.microsoft.icon',
			'tiff' => 'image/tiff',
			'tif' => 'image/tiff',
			'svg' => 'image/svg+xml',
			'svgz' => 'image/svg+xml',
			
			// archives
			'zip' => 'application/zip',
			'rar' => 'application/x-rar-compressed',
			'exe' => 'application/x-msdownload',
			'msi' => 'application/x-msdownload',
			'cab' => 'application/vnd.ms-cab-compressed',
			
			// audio/video
			'mp3' => 'audio/mpeg',
			'qt' => 'video/quicktime',
			'mov' => 'video/quicktime',
			
			// adobe
			'pdf' => 'application/pdf',
			'psd' => 'image/vnd.adobe.photoshop',
			'ai' => 'application/postscript',
			'eps' => 'application/postscript',
			'ps' => 'application/postscript',
			
			// ms office
			'doc' => 'application/msword',
			'docx' => 'application/msword',
			'rtf' => 'application/rtf',
			'xls' => 'application/vnd.ms-excel',
			'xlsx' => 'application/vnd.ms-excel',
			'ppt' => 'application/vnd.ms-powerpoint',
			'pptx' => 'application/vnd.ms-powerpoint',
			
			// open office
			'odt' => 'application/vnd.oasis.opendocument.text',
			'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
		);
		
		$fileExtension = $this->file->getExtension();
		$fileExtension = strtolower($fileExtension);
		if (array_key_exists($fileExtension, $mimeTypes)) {
			return $mimeTypes[$fileExtension];
		}
		return 'application/octet-stream';
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return string
	 */
	private function getMimeTypeByFileInfo(): string {
		$fileInfo = finfo_open(FILEINFO_MIME);
		$mimeType = finfo_file($fileInfo, $this->file->getPath());
		finfo_close($fileInfo);
		if (false === $mimeType) {
			return 'application/octet-stream';
		}
		return $mimeType;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return string
	 */
	public function getMimeType(): string {
		if (function_exists('finfo_open') && self::FILE_PROTOCOL_FILE === $this->getFileProtocol()) {
			return $this->getMimeTypeByFileInfo();
		}
		return $this->getMimeTypeByExtension();
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return string
	 */
	private function getFileProtocol(): string {
		$protocolStart = strpos($this->file->getPath(), '://');
		if (false === $protocolStart) {
			return self::FILE_PROTOCOL_FILE;
		}
		return substr($this->file->getPath(), 0, $protocolStart);
	}
}