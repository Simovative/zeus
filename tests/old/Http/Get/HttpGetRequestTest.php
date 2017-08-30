<?php
namespace Simovative\AC5\Framework;

use Simovative\Framework\Http\Get\HttpGetRequest;
use Simovative\Framework\Http\Request\HttpRequest;
use Simovative\Framework\Http\Url;

/**
 * HttpGetRequest test case.
 *
 * @covers \Simovative\Framework\Http\Get\HttpGetRequest
 * @covers \Simovative\Framework\Http\Request\HttpRequest
 */
class HttpGetRequestTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var HttpGetRequest
	 */
	private $httpGetRequest;
	
	/**
	 * @var Url
	 */
	private $url;

	/**
	 * Prepares the environment before running a test.
	 * 
	 * @author Benedikt Schaller
	 */
	protected function setUp() {
		parent::setUp();
		
		$this->url = $this->getMockBuilder('Simovative\\Framework\\Http\\Url')
			->setConstructorArgs(array(''))
			->setMethods(null)
			->getMock();
		
		$this->httpGetRequest = HttpRequest::fromParameters(HttpRequest::TYPE_GET, $this->url, array('test' => 1));
	}

	/**
	 * Tests isGet.
	 *
	 * @author Benedikt Schaller
	 */
	public function testIsGetRequest() {
		$this->assertTrue($this->httpGetRequest->isGet());
	}
	
	/**
	 * Test isPost.
	 *
	 * @author Benedikt Schaller
	 */
	public function testIsNoPostRequest() {
		$this->assertFalse($this->httpGetRequest->isPost());
	}
	
	/**
	 * Test getUrl.
	 *
	 * @author Benedikt Schaller
	 */
	public function testUrlGetter() {
		$this->assertSame($this->url, $this->httpGetRequest->getUrl());
	}
	
	/**
	 * Test hasParameter.
	 *
	 * @author Benedikt Schaller
	 */
	public function testHasExistingParameter() {
		$this->assertTrue($this->httpGetRequest->hasParameter('test'));
	}
	
	/**
	 * Test hasParameter.
	 *
	 * @author Benedikt Schaller
	 */
	public function testHasNonExistingParameter() {
		$this->assertFalse($this->httpGetRequest->hasParameter('does not exist'));
	}
}
