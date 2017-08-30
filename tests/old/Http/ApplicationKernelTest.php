<?php
namespace Simovative\AC5\Framework\Test\Http;

use Simovative\Framework\Http\ApplicationKernel;
use Simovative\Framework\Http\Request\HttpRequestInterface;
use Simovative\Framework\Dependency\FrameworkFactory;
use Simovative\Framework\Dependency\MasterFactory;
/**
 * ApplicationKernel test case.
 * 
 * @author tpawlow
 * @covers \Simovative\Framework\Http\ApplicationKernel
 */
class ApplicationKernelTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|HttpRequestInterface
	 */
	private $getRequestStub;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|FrameworkFactory
	 */
	private $frameworkFactoryStub;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|ApplicationKernel
	 */
	private $kernelStub;
	
	/**
	 * (non-PHPdoc)
	 * 
	 * @see PHPUnit_Framework_TestCase::setUp()
	 * @author tpawlow
	 */
	protected function setUp() {
		$masterFactoryStub = $this->getMockBuilder('Simovative\Framework\Dependency\MasterFactory')
			->disableOriginalConstructor()
			->setMethods(null)
			->getMock();
		
		
		$this->frameworkFactoryStub->setMasterFactory($masterFactoryStub);
		$this->getRequestStub = $this->getMockBuilder('Simovative\Framework\Http\Get\HttpGetRequest')
			->disableOriginalConstructor()
			->getMock();
		
		$this->kernelStub = $this->getMockForAbstractClass('Simovative\Framework\Http\ApplicationKernel', array(
			$masterFactoryStub 
		));
		$masterFactoryStub->register($this->frameworkFactoryStub);
	}

	/**
	 * Prepares the mocks for the run method.
	 *
	 * @param \Exception $exception
	 * @return void
	 * @author tpawlow
	 */
	private function prepareMocksForRunMethod(\Exception $exception = null) {
		$responseStub = $this->getMockBuilder('Simovative\Framework\Http\Response\HttpResponseInterface')
			->getMock();
			
		if ($exception) {
			$responseStub->method('send')
				->willThrowException($exception);
			
				$this->kernelStub->expects($this->once())
					->method('getApplicationExceptionResponse')
					->willReturn($responseStub);
		} else {
			$responseStub->expects($this->once())
				->method('send');
		}
			
		$dispatcherStub = $this->getMockBuilder('Simovative\Framework\Http\Request\HttpRequestDispatcherInterface')
			->getMock();
			
		$dispatcherStub->method('dispatch')
			->willReturn($responseStub);
		
		$dispatcherLocatorStub = $this->getMockBuilder('Simovative\Framework\Http\Request\HttpRequestDispatcherLocator')
			->setConstructorArgs(array($this->frameworkFactoryStub))
			->setMethods(array('getDispatcherFor'))
			->getMock();
		
		$dispatcherLocatorStub->method('getDispatcherFor')
			->willReturn($dispatcherStub);
		
		$this->frameworkFactoryStub->method('createHttpRequestDispatcherLocator')->willReturn($dispatcherLocatorStub);
	}
	
	/**
	 * Tests the run method with exception.
	 * 
	 * @author tpawlow
	 */
	public function testRunNoModulesApplicationException() {
		$this->setExpectedException('Simovative\Framework\Exception\ApplicationException');
		$this->kernelStub->run($this->getRequestStub);
	}

	/**
	 * Tests the run method for a get request.
	 * 
	 * @author tpawlow
	 */
	public function testRunMethodGet() {
		$moduleStub = $this->getMockForAbstractClass('Simovative\Framework\Module\Module');
		$factoryStub = $this->getMockBuilder('Simovative\Framework\Dependency\FactoryInterface')->getMock();
		$this->kernelStub->method('getModules')->willReturn(array(
			$moduleStub 
		));
		
		$moduleStub->method('createFactory')
			->willReturn($factoryStub);
		
		$moduleStub->expects($this->once())
			->method('registerGetRouters');
		
		$moduleStub->method('createNavigationContainer')
			->willReturn(null);
		
		$this->getRequestStub->method('isGet')
			->willReturn(true);
		
		$this->getRequestStub->method('isPost')
			->willReturn(false);

		$this->prepareMocksForRunMethod();
			
		$responseResult = $this->kernelStub->run($this->getRequestStub);
		$this->assertInstanceOf('Simovative\Framework\Http\Response\HttpResponseInterface', $responseResult);
	}

	/**
	 * Tests the run method for a post request.
	 * 
	 * @author tpawlow
	 */
	public function testRunMethodPost() {
		$moduleStub = $this->getMockForAbstractClass('Simovative\Framework\Module\Module');
		$factoryStub = $this->getMockBuilder('Simovative\Framework\Dependency\FactoryInterface')->getMock();
		$this->kernelStub->method('getModules')->willReturn(array(
			$moduleStub 
		));
		
		$moduleStub->method('createFactory')
			->willReturn($factoryStub);
		
		$moduleStub->expects($this->once())
			->method('registerPostRouters');
		
		$moduleStub->expects($this->once())
			->method('registerModuleControllers');
		
		$moduleStub->method('createNavigationContainer')
			->willReturn(null);
		
		$this->getRequestStub->method('isGet')
			->willReturn(false);
			
		$this->getRequestStub->method('isPost')
			->willReturn(true);;
			
		$this->prepareMocksForRunMethod();
		
		$responseResult = $this->kernelStub->run($this->getRequestStub);
		$this->assertInstanceOf('Simovative\Framework\Http\Response\HttpResponseInterface', $responseResult);
	}

	/**
	 * Tests the run method for an exception.
	 * 
	 * @author tpawlow
	 */
	public function testRunMethodHandleException() {
		$moduleStub = $this->getMockForAbstractClass('Simovative\Framework\Module\Module');
		$factoryStub = $this->getMockBuilder('Simovative\Framework\Dependency\FactoryInterface')->getMock();
		$this->kernelStub->method('getModules')->willReturn(array(
			$moduleStub 
		));
		
		$moduleStub->method('createFactory')
			->willReturn($factoryStub);
		
		$moduleStub->method('createNavigationContainer')
			->willReturn(null);
		
		$this->getRequestStub->method('isGet')
			->willReturn(false);
			
		$this->getRequestStub->method('isPost')
			->willReturn(false);
		
		$exception = new \Exception('TestMessage123');
		$this->prepareMocksForRunMethod($exception);
		
		$this->expectOutputRegex('/TestMessage123/');
		$responseResult = $this->kernelStub->run($this->getRequestStub);
		$this->assertInstanceOf('Simovative\Framework\Http\Response\HttpResponseInterface', $responseResult);
	}
	
}