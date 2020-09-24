<?php

namespace Simovative\Zeus\Tests\Unit\Http\Json;

use PHPUnit\Framework\TestCase;
use Simovative\Zeus\Http\Json\JsonEncodingException;
use Simovative\Zeus\Http\Json\JsonEncodingService;
use stdClass;

class JsonEncodingServiceTest extends TestCase {
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 * @throws JsonEncodingException
	 */
	public function testThatJsonCanBeDecodedAsArray(): void {
		$jsonRequestString = '
			{
				"data": {
					"type": "addresses",
					"attributes": {
						"city": "München",
						"street": "Musterstrasse 1",
						"zip": "81670"
					}
				}
			}
		';
		$jsonEncodingService = new JsonEncodingService();
		$decodedArray = $jsonEncodingService->decode($jsonRequestString, true);
		self::assertIsArray($decodedArray);
		$expectedArray = [
			"data" => [
				"type" => "addresses",
				"attributes" => [
					"city" => "München",
					"street" => "Musterstrasse 1",
					"zip" => "81670"
				]
			]
		];
		self::assertEquals($expectedArray, $decodedArray);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 * @throws JsonEncodingException
	 */
	public function testThatJsonCanBeDecodedAsObject(): void {
		$jsonRequestString = '
			{
				"data": {
					"type": "addresses",
					"attributes": {
						"city": "München",
						"street": "Musterstrasse 1",
						"zip": "81670"
					}
				}
			}
		';
		$jsonEncodingService = new JsonEncodingService();
		$decodedObject = $jsonEncodingService->decode($jsonRequestString, false);
		self::assertIsObject($decodedObject);
		$expectedObject = new stdClass();
		$expectedObject->data = new stdClass();
		$expectedObject->data->type = 'addresses';
		$expectedObject->data->attributes = new stdClass();
		$expectedObject->data->attributes->city = 'München';
		$expectedObject->data->attributes->street = 'Musterstrasse 1';
		$expectedObject->data->attributes->zip = '81670';
		self::assertEquals($expectedObject, $decodedObject);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 * @throws JsonEncodingException
	 */
	public function testThatJsonCanBeDecodedAsObjectAndArrays(): void {
		$jsonRequestString = '
			{
				"data": {
					"type": "addresses",
					"attributes": [
						"city",
						"street",
						"zip"
					]
				}
			}
		';
		$jsonEncodingService = new JsonEncodingService();
		$decodedObject = $jsonEncodingService->decode($jsonRequestString, false);
		self::assertIsObject($decodedObject);
		$expectedObject = new stdClass();
		$expectedObject->data = new stdClass();
		$expectedObject->data->type = 'addresses';
		$expectedObject->data->attributes = [
			"city",
			"street",
			"zip"
		];
		self::assertEquals($expectedObject, $decodedObject);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatJsonEncodingDepthIsConsidered(): void {
		$jsonRequestString = '
			{
				"data": {
					"type": "addresses",
					"attributes": [
						"city",
						"street",
						"zip"
					]
				}
			}
		';
		$jsonEncodingService = new JsonEncodingService();
		$this->expectException(JsonEncodingException::class);
		$jsonEncodingService->decode($jsonRequestString, true, 2);
	}
}