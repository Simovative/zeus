<?php
namespace Simovative\AC5\Framework\Test\Http;

use Simovative\Framework\Http\UrlMatcher;
/**
 * Configuration test case.
 * 
 * @author tpawlow
 * @covers \Simovative\Framework\Http\UrlMatcher
 */
class UrlMatcherTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|\Simovative\Framework\Http\Url
	 */
	private $urlStub;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|\Simovative\Framework\Http\Url
	 */
	private $noPathUrlStub;
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see PHPUnit_Framework_TestCase::setUp()
	 * @author tpawlow
	 */
	protected function setUp() {
		$this->urlStub = $this->getMockBuilder('Simovative\Framework\Http\Url')
			->setConstructorArgs(array('https://framework.local/path1/path2?param1=1'))
			->setMethods(null)
			->getMock();
		
		$this->noPathUrlStub = $this->getMockBuilder('Simovative\Framework\Http\Url')
			->setConstructorArgs(array('https://framework.local?param1=1'))
			->setMethods(null)
			->getMock();
	}
	
	/**
	 * Tests doesStartWith method.
	 * 
	 * @author tpawlow
	 */
	public function testDoesStartWith() {
		$matcher = new UrlMatcher($this->urlStub);
		$this->assertTrue($matcher->doesStartWith('path1'));
		$this->assertFalse($matcher->doesStartWith('path2'));
		
		$noPathMatcher = new UrlMatcher($this->noPathUrlStub);
		$this->assertFalse($noPathMatcher->doesStartWith('path1'));
	}
	
	/**
	 * Tests getNumberOfComponents method.
	 * 
	 * @author tpawlow
	 */
	public function testGetNumberOfComponents() {
		$matcher = new UrlMatcher($this->urlStub);
		$this->assertEquals(2, $matcher->getNumberOfComponents());
	}
	
	/**
	 * Tests getComponent method.
	 * 
	 * @author tpawlow
	 */
	public function testGetComponent() {
		$matcher = new UrlMatcher($this->urlStub);
		$this->assertEquals('path1', $matcher->getComponent(1));
		
		$noPathMatcher = new UrlMatcher($this->noPathUrlStub);
		$this->setExpectedException('Simovative\Framework\Exception\ApplicationException');
		$noPathMatcher->getComponent(1);
	}
	
	/**
	 * Tests match method.
	 * 
	 * @author tpawlow
	 */
	public function testMatch() {
		$matcher = new UrlMatcher($this->urlStub);
		$this->assertTrue($matcher->match(1, 'path1'));
		$this->assertFalse($matcher->match(1, 'path2'));
		$this->assertFalse($matcher->match(3, 'path3'));
		$this->assertFalse($matcher->match(1, 'path1', 3));
	}
	
	/**
	 * Tests isEqual method.
	 * 
	 * @author tpawlow
	 */
	public function testIsEqual() {
		$matcher = new UrlMatcher($this->urlStub);
		$this->assertTrue($matcher->isEqual('https://framework.local/path1/path2'));
		$this->assertFalse($matcher->isEqual(''));
	}
}
