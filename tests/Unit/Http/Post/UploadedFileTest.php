<?php
declare(strict_types=1);

namespace Simovative\Zeus\Test\Unit\Http\Post;

use PHPUnit\Framework\TestCase;
use Simovative\Zeus\Http\Post\UploadedFile;

/**
 * @author Benedikt Schaller
 */
class UploadedFileTest extends TestCase {
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatFilesArrayIsCorrectlyParsed() {
		$filesArray = [
			'file1' => [
				'name' => 'MyFile.txt',
				'type' => 'text/plain',
				'tmp_name' => '/tmp/php/php1h4j1o',
				'error' => UPLOAD_ERR_OK,
				'size' => 123,
			],
			'file2' => [
				'name' => 'MyFile.jpg',
				'type' => 'image/jpeg',
				'tmp_name' => '/tmp/php/php6hst32',
				'error' => UPLOAD_ERR_OK,
				'size' => 98174,
			],
			'download' => [
				'name' => [
					'file1' => 'MyFile.txt',
					'file2' => 'MyFile.jpg',
				],
				'type' => [
					'file1' => 'text/plain',
					'file2' => 'image/jpeg',
				],
				'tmp_name' => [
					'file1' => '/tmp/php/php1h4j1o',
					'file2' => '/tmp/php/php6hst32',
				],
				'error' => [
					'file1' => UPLOAD_ERR_OK,
					'file2' => UPLOAD_ERR_OK,
				],
				'size' => [
					'file1' => 123,
					'file2' => 98174,
				]
			]
		];
		$uploadedFiles = UploadedFile::createFromGlobal($filesArray);
		self::assertEquals(4, count($uploadedFiles));
		
		self::assertEquals(1, $this->countFilesByInput('file1', $uploadedFiles));
		self::assertEquals(1, $this->countFilesByInput('file2', $uploadedFiles));
		foreach ($uploadedFiles as $uploadedFile) {
			if ($uploadedFile->getInputName() === 'file1') {
				self::assertEquals('/tmp/php/php1h4j1o', $uploadedFile->getPath());
				self::assertEquals('MyFile.txt', $uploadedFile->getLabel());
				self::assertEquals(UPLOAD_ERR_OK, $uploadedFile->getInputIndex());
				self::assertEquals(123, $uploadedFile->getSize());
				self::assertEquals('text/plain', $uploadedFile->getType());
				self::assertFalse($uploadedFile->isValidUploadedFile());
			}
		}
	}
	
	/**
	 * @author Benedikt Schaller
	 * @param string $inputName
	 * @param UploadedFile[] $uploadedFiles
	 * @return int
	 */
	private function countFilesByInput(string $inputName, array $uploadedFiles) {
		$count = 0;
		foreach ($uploadedFiles as $uploadedFile) {
			if ($uploadedFile->getInputName() === $inputName) {
				++$count;
			}
		}
		return $count;
	}
}