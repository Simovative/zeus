<?php
namespace Simovative\AC5\Framework;

use Simovative\Framework\Configuration\Configuration;
use Simovative\Framework\Dependency\FactoryInterface;
use Simovative\Framework\Dependency\FrameworkFactory;
use Simovative\Framework\Dependency\MasterFactory;

/**
 * MasterFactory test case.
 *
 * @covers \Simovative\Framework\Dependency\MasterFactory
 */
class MasterFactoryTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var MasterFactory|FrameworkFactory
	 */
	private $MasterFactory;

	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp();
		
		$this->MasterFactory = new MasterFactory(new Configuration(array(), __DIR__));
	}

	/**
	 * Tests MasterFactory->register()
	 */
	public function testRegisterAtChildFactory() {
		/* @var $factory FactoryInterface|\PHPUnit_Framework_MockObject_MockObject */
		$factory = $this->getMock('Simovative\\Framework\\Dependency\\FactoryInterface');
		$factory->expects($this->once())->method('setMasterFactory')->with($this->MasterFactory);
		$this->MasterFactory->register($factory);
	}
	
	public function testCallChildFactoryMethod() {
		/* @var $factory FrameworkFactory|\PHPUnit_Framework_MockObject_MockObject */
		$factory = $this->getMockBuilder('Simovative\\Framework\\Dependency\\FrameworkFactory')->disableOriginalConstructor()->getMock();
		$this->MasterFactory->register($factory);
		$factory->expects($this->once())->method('getHttpPostRequestCommandRouter');
		$this->MasterFactory->getHttpPostRequestCommandRouter();
	}
	
	/**
	 *
	 * Enter description here ...
	 *
	 * @author Benedikt Schaller
	 */
	public function testThrowsExceptionWhenCallingNonExistingMethod() {
		$this->setExpectedException('\Simovative\Framework\Exception\ApplicationException');
		/** @noinspection PhpUndefinedMethodInspection */
		$this->MasterFactory->doesNotExist();
	}
}

