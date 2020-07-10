<?php
declare(strict_types=1);

namespace Unit\Http\Post;

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
		$this->assertTrue($postRequest->hasUploadedFiles());
		$this->assertEquals(4, count($postRequest->getUploadedFiles()));
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatFileForInputIsReturned() {
		$inputName = 'testInput3';
		$postRequest = $this->createRequestWithFiles();
		$inputFiles = $postRequest->getUploadedFilesOfInput($inputName);
		$this->assertEquals(2, count($inputFiles));
		foreach ($inputFiles as $inputFile) {
			$this->assertEquals($inputName, $inputFile->getInputName());
		}
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatPostRequestWithoutFilesHasNoFiles() {
		$postRequest = new HttpPostRequest(new Url('/my/test/url'), [], [], []);
		$this->assertFalse($postRequest->hasUploadedFiles());
		$this->assertEquals(0, count($postRequest->getUploadedFiles()));
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
		$postRequest = new HttpPostRequest(new Url('/my/test/url'), [], [], $uploadedFiles);
		return $postRequest;
	}
}
