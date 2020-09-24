<?php
declare(strict_types=1);

namespace Simovative\Zeus\Test\Unit\Http\Post;

use Simovative\Zeus\Http\Post\HttpPostRequest;
use PHPUnit\Framework\TestCase;
use Simovative\Zeus\Http\Post\UploadedFile;
use Simovative\Zeus\Http\Url\Url;

/**
 * @author Benedikt Schaller
 */
class HttpPostRequestTest extends TestCase {
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatPostReuqestSavesUploadedFiles() {
		$postRequest = $this->createRequestWithFiles();
		self::assertTrue($postRequest->hasUploadedFiles());
		self::assertEquals(4, count($postRequest->getUploadedFiles()));
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatFileForInputIsReturned() {
		$inputName = 'testInput3';
		$postRequest = $this->createRequestWithFiles();
		$inputFiles = $postRequest->getUploadedFilesOfInput($inputName);
		self::assertEquals(2, count($inputFiles));
		foreach ($inputFiles as $inputFile) {
			self::assertEquals($inputName, $inputFile->getInputName());
		}
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatPostRequestWithoutFilesHasNoFiles() {
		$postRequest = new HttpPostRequest(new Url('/my/test/url'), [], [], [], []);
		self::assertFalse($postRequest->hasUploadedFiles());
		self::assertEquals(0, count($postRequest->getUploadedFiles()));
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return HttpPostRequest
	 */
	private function createRequestWithFiles(): HttpPostRequest {
		$uploadedFiles = [
			new UploadedFile('testInput1', 1, 'testLabel1', 'testType', '/my/test/path', 1, 10000),
			new UploadedFile('testInput2', 1, 'testLabel2', 'testType', '/my/test/path', 1, 12000),
			new UploadedFile('testInput3', 1, 'testLabel3', 'testType', '/my/test/path', 1, 11000),
			new UploadedFile('testInput3', 2, 'testLabel4', 'testType', '/my/test/path', 1, 8000),
		];
		$postRequest = new HttpPostRequest(new Url('/my/test/url'), [], [], [], $uploadedFiles);
		return $postRequest;
	}

    /**
     * @author Benedikt Schaller
     * @return void
     */
    public function testThatPostDataIsReturnedAsBodyForFormDataRequest(): void {
        $testParameters = ['test' => 'value'];
        $postRequest = new HttpPostRequest(
            new Url('/my/test/url'),
            $testParameters,
            ['CONTENT_TYPE' => 'multipart/form-data'],
			[],
            []
        );
        self::assertEquals($testParameters, $postRequest->getParsedBody());
	}

    /**
     * @author Benedikt Schaller
     * @return void
     */
    public function testThatPostDataIsReturnedAsBodyForUrlEncodedRequest(): void {
        $testParameters = ['test' => 'value'];
        $postRequest = new HttpPostRequest(
            new Url('/my/test/url'),
            $testParameters,
            ['CONTENT_TYPE' => 'application/x-www-form-urlencoded'],
			[],
			[]
        );
        self::assertEquals($testParameters, $postRequest->getParsedBody());
    }

    /**
     * @author Benedikt Schaller
     * @return void
     */
    public function testThatPostDataIsNotReturnedAsBodyForOtherRequest(): void {
        $testParameters = ['test' => 'value'];
        $postRequest = new HttpPostRequest(
            new Url('/my/test/url'),
            $testParameters,
            ['CONTENT_TYPE' => 'application/other'],
			[],
            []
        );
		self::assertNotEquals($testParameters, $postRequest->getParsedBody());
    }
}
