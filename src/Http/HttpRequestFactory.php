<?php
declare(strict_types=1);

namespace Simovative\Zeus\Http;

use Simovative\Zeus\Dependency\Factory;
use Simovative\Zeus\Exception\RequestMethodNotAllowedException;
use Simovative\Zeus\Http\Get\HttpGetRequest;
use Simovative\Zeus\Http\Get\HttpOptionRequest;
use Simovative\Zeus\Http\Post\HttpPostRequest;
use Simovative\Zeus\Http\Post\UploadedFile;
use Simovative\Zeus\Http\Request\HttpRequestInterface;
use Simovative\Zeus\Http\Url\Url;
use Simovative\Zeus\Stream\PhpInputStream;
use Simovative\Zeus\Stream\StreamInterface;

/**
 * @author Benedikt Schaller
 */
class HttpRequestFactory extends Factory {
	
	private const SERVER_PARAMETER_CONTENT_TYPE = 'CONTENT_TYPE';
	
	private const CONTENT_TYPE_JSON = 'application/json';
	
	/**
	 * @author Benedikt Schaller
	 * @return HttpRequestInterface
	 * @throws RequestMethodNotAllowedException
	 * @throws Json\JsonEncodingException
	 */
	public function createRequestFromGlobals() {
		$currentUrl = Url::createFromServerArray($_SERVER);
		$parsedBody = $this->createParsedBody($_SERVER);
		
		switch ($_SERVER['REQUEST_METHOD']) {
			case 'POST':
				$uploadedFiles = UploadedFile::createFromGlobal($_FILES);
				return new HttpPostRequest($currentUrl, $_REQUEST, $_SERVER, $parsedBody, $uploadedFiles);
			case 'GET':
				return new HttpGetRequest($currentUrl, $_GET, $_SERVER, $parsedBody);
			case 'PUT':
				return new HttpPutRequest($currentUrl, $_REQUEST, $_SERVER, $parsedBody);
			case 'PATCH':
				return new HttpPatchRequest($currentUrl, $_REQUEST, $_SERVER, $parsedBody);
			case 'DELETE':
				return new HttpDeleteRequest($currentUrl, $_REQUEST, $_SERVER, $parsedBody);
			case 'HEAD':
				return new HttpHeadRequest($currentUrl, $_REQUEST, $_SERVER, $parsedBody);
            case 'OPTION':
                return new HttpOptionRequest($currentUrl, $_REQUEST, $_SERVER, $parsedBody);
		}
		
		throw new RequestMethodNotAllowedException();
	}
	
	/**
	 * @author Benedikt Schaller
	 * @param array $serverParameters
	 * @return bool
	 */
	private function isJsonContent(array $serverParameters): bool {
        $contentType = $serverParameters[self::SERVER_PARAMETER_CONTENT_TYPE] ?? null;
        return $contentType === self::CONTENT_TYPE_JSON;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @param mixed[] $serverParameters
	 * @return array|StreamInterface|null
	 * @throws Json\JsonEncodingException
	 */
	private function createParsedBody(array $serverParameters) {
		$parsedBody = new PhpInputStream();
		if ($this->isJsonContent($serverParameters)) {
			$streamContents = $parsedBody->getContents();
			if (empty($streamContents)) {
				return null;
			}
			$parsedBody = $this->getMasterFactory()->createJsonEncodingService()->decode(
				$streamContents,
				false
			);
		}
		return $parsedBody;
	}
	
}
