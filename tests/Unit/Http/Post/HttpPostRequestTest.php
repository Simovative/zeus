<?php
namespace Simovative\AC5\Framework;

use Simovative\Framework\Http\Post\HttpPostRequest;
use Simovative\Framework\Http\Request\HttpRequest;
use Simovative\Framework\Http\Url;

/**
 * HttpPostRequest test case.
 *
 * @covers \Simovative\Framework\Http\Post\HttpPostRequest
 * @covers \Simovative\Framework\Http\Request\HttpRequest
 */
class HttpPostRequestTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var HttpPostRequest
	 */
	private $httpPostRequest;

	/**
	 * @var Url
	 */
	private $url;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp();
		
		$this->url = $this->getMockBuilder('Simovative\\Framework\\Http\\Url')
			->setConstructorArgs(array(''))
			->setMethods(null)
			->getMock();
		
		$this->httpPostRequest = HttpRequest::fromParameters(HttpRequest::TYPE_POST, $this->url, array('test' => 1));
	}

	/**
	 * Test isPost.
	 *
	 * @author Benedikt Schaller
	 */
	public function testIsPostRequest() {
		$this->assertTrue($this->httpPostRequest->isPost());
	}

	/**
	 * Test isGet.
	 *
	 * @author Benedikt Schaller
	 */
	public function testIsNoGetRequest() {
		$this->assertFalse($this->httpPostRequest->isGet());
	}

	/**
	 * Test getUrl.
	 *
	 * @author Benedikt Schaller
	 */
	public function testUrlGetter() {
		$this->assertSame($this->url, $this->httpPostRequest->getUrl());
	}

	/**
	 * Test hasParameter.
	 *
	 * @author Benedikt Schaller
	 */
	public function testHasParameter() {
		$this->assertTrue($this->httpPostRequest->hasParameter('test'));
		$this->assertFalse($this->httpPostRequest->hasParameter('test1'));
	}
}
