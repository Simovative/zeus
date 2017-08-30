<?php
namespace Simovative\AC5\Framework\Test\Dependency;

use Simovative\Framework\Dependency\FrameworkFactory;
use org\bovigo\vfs\vfsStream;
use Simovative\Framework\Configuration\Configuration;

/**
 * FrameworkFactory test case.
 * 
 * @uathor mnoerenberg
 * @covers Simovative\Framework\Dependency\FrameworkFactory
 * @covers Simovative\Framework\Dependency\Factory
 * @covers Simovative\Framework\Dependency\FactoryInterface
 */
class FrameworkFactoryTest extends \PHPUnit_Framework_TestCase {
	
	/**
	 * @var FrameworkFactory
	 */
	private $frameworkFactory;
	
	/**
	 * Prepares the environment before running a test.
	 * 
	 * @author mnoerenberg
	 */
	protected function setUp() {
		parent::setUp();
		$this->frameworkFactory = new FrameworkFactory();
		
		// Test files with vfs
		$root = vfsStream::setup('FrameworkFactoryTest', 
									null, 
									array(
										'TranslationDirectory' => array(), 
										'ModuleDirectory' => array(), 
										'ApplicationDirectory' => array(
																		'template' => array(),
																	),
										'TemplateDirectory' => array(),
										'DataDirectory' => array(),
										'PublicDirectory' => array(),
									)
								 );
		
		$configuration = $this->getMockBuilder('Simovative\Framework\Configuration\Configuration')
			->setConstructorArgs(
				array(
					array(
						Configuration::TRANSLATION_DIRECTORY_PATH => vfsStream::url('FrameworkFactoryTest/TranslationDirectory'),
						Configuration::MODULE_DIRECTORY_PATH => vfsStream::url('FrameworkFactoryTest/ModuleDirectory'),
						Configuration::APPLICATION_DIRECTORY_PATH => vfsStream::url('FrameworkFactoryTest/ApplicationDirectory'),
						Configuration::DATA_DIRECTORY_PATH => vfsStream::url('FrameworkFactoryTest/DataDirectory'),
						Configuration::PUBLIC_DIRECTORY_PATH => vfsStream::url('FrameworkFactoryTest/PublicDirectory'),
						Configuration::DEFAULT_LANGUAGE => 'de-DE',
						Configuration::DATABASE_USER => 'user',
						Configuration::DATABASE_HOST => 'host',
						Configuration::DATABASE_PASSWORD => 'secret',
						Configuration::DATABASE_NAME => 'dbname',
					),
					__DIR__
				)
			)
			->setMethods(null)
			->getMock();
		
		$session = $this->getMockBuilder('Simovative\Framework\Session\Session')
				->disableOriginalConstructor()
				->getMock();
		$session->method('getLocale')->willReturn('');
		
		$masterFactory = $this->getMockBuilder('Simovative\Framework\Dependency\MasterFactory')
					->setConstructorArgs(array($configuration))
					->setMethods(array('getSession'))
					->getMock();
		$masterFactory->method('getSession')->willReturn($session);
		
		$masterFactory->register($this->frameworkFactory);
		$this->frameworkFactory->setMasterFactory($masterFactory);
	}
	
	/**
	 * Tests FrameworkFactory->getHttpPostRequestRouter()
	 * 
	 * @author mnoerenberg
	 */
	public function testCreatesHttpPostRequestRouter() {
		$this->assertInstanceOf('Simovative\\Framework\\Http\\Post\\HttpPostRequestCommandRouterInterface', $this->frameworkFactory->getHttpPostRequestCommandRouter());
	}
	
	/**
	 * Tests FrameworkFactory->getHttpPostRequestRouter()
	 * 
	 * @author mnoerenberg
	 */
	public function testManagesSingleInstanceOfHttpPostRequestRouter() {
		$this->assertSame($this->frameworkFactory->getHttpPostRequestCommandRouter(), $this->frameworkFactory->getHttpPostRequestCommandRouter());
	}
	
	/**
	 * Tests FrameworkFactory->getHttpGetRequestRouter()
	 * 
	 * @author mnoerenberg
	 */
	public function testCreatesHttpGetRequestRouter() {
		$this->assertInstanceOf('Simovative\\Framework\\Get\\HttpGetRequestRouterInterface', $this->frameworkFactory->getHttpGetRequestRouter());
	}
	
	/**
	 * Tests FrameworkFactory->getHttpGetRequestRouter()
	 * 
	 * @author mnoerenberg
	 */
	public function testSingleInstanceOfHttpGetRequestRouter() {
		$this->assertSame($this->frameworkFactory->getHttpGetRequestRouter(), $this->frameworkFactory->getHttpGetRequestRouter());
	}
	
	/**
	 * Tests FrameworkFactory->createHttpResponseLocator()
	 *
	 * @author mnoerenberg
	 */
	public function testCreatesHttpResponseLocator() {
		$this->assertInstanceOf('Simovative\\Framework\\Http\\Response\\HttpResponseLocator', $this->frameworkFactory->createHttpResponseLocator());
	}
	
	/**
	 * Tests FrameworkFactory->createHttpGetDispatcher()
	 *
	 * @author mnoerenberg
	 */
	public function testCreatesHttpGetDispatcher() {
		$this->assertInstanceOf('Simovative\\Framework\\Http\\Request\\HttpRequestDispatcherInterface', $this->frameworkFactory->createHttpGetDispatcher());
	}
	
	/**
	 * Tests FrameworkFactory->createCommandDispatcher()
	 *
	 * @author mnoerenberg
	 */
	public function testCreatesCommandDispatcher() {
		$this->assertInstanceOf('Simovative\\Framework\\Http\\Request\\HttpRequestDispatcherInterface', $this->frameworkFactory->createCommandDispatcher());
	}
	
	/**
	 * Tests FrameworkFactory->createHttpRequestDispatcherLocator()
	 *
	 * @author mnoerenberg
	 */
	public function testCreateHttpRequestDispatcherLocator() {
		$this->assertInstanceOf('Simovative\Framework\Http\Request\HttpRequestDispatcherLocator', $this->frameworkFactory->createHttpRequestDispatcherLocator());
	}
	
	/**
	 * Tests FrameworkFactory->createApplicationController()
	 *
	 * @author mnoerenberg
	 */
	public function testCreateApplicationController() {
		$this->assertInstanceOf('Simovative\Framework\Command\ApplicationController', $this->frameworkFactory->createApplicationController());
	}
	
	/**
	 * Tests FrameworkFactory->createApplicationController()
	 *
	 * @author mnoerenberg
	 */
	public function testSingleInstanceOfApplicationController() {
		$this->assertSame($this->frameworkFactory->createApplicationController(), $this->frameworkFactory->createApplicationController());
	}
	
	/**
	 * Tests FrameworkFactory->createSymfonyCliApplication()
	 *
	 * @author mnoerenberg
	 */
	public function testCreateSymfonyCliApplication() {
		$this->assertInstanceOf('Symfony\Component\Console\Application', $this->frameworkFactory->createSymfonyCliApplication('UNKNOWN', 'UNKNOWN'));
	}
	
	/**
	 * Tests FrameworkFactory->getNavigationContainer()
	 *
	 * @author mnoerenberg
	 */
	public function testCreateNavigationContainer() {
		$this->assertInstanceOf('Simovative\Framework\Navigation\NavigationContainer', $this->frameworkFactory->getNavigationContainer());
	}
	
	/**
	 * Tests FrameworkFactory->getNavigationContainer()
	 *
	 * @author mnoerenberg
	 */
	public function testSingleInstanceOfNavigationContainer() {
		$this->assertSame($this->frameworkFactory->getNavigationContainer(), $this->frameworkFactory->getNavigationContainer());
	}
	
	/**
	 * Tests FrameworkFactory->getTranslator()
	 *
	 * @author mnoerenberg
	 */
	public function testCreateTranslator() {
		$this->assertInstanceOf('Simovative\Framework\Locale\Translator', $this->frameworkFactory->getTranslator());
	}
	
	/**
	 * Tests FrameworkFactory->getTranslator()
	 *
	 * @author mnoerenberg
	 */
	public function testSingleInstanceOfTranslator() {
		$this->assertSame($this->frameworkFactory->getTranslator(), $this->frameworkFactory->getTranslator());
	}
	
	/**
	 * Tests FrameworkFactory->getTemplateEngine()
	 *
	 * @author mnoerenberg
	 */
	public function testCreateTemplateEngine() {
		$this->assertInstanceOf('Simovative\Framework\Template\TemplateEngine', $this->frameworkFactory->getTemplateEngine());
	}
	
	/**
	 * Tests FrameworkFactory->getTemplateEngine()
	 *
	 * @author mnoerenberg
	 */
	public function testSingleInstanceOfTemplateEngine() {
		$this->assertSame($this->frameworkFactory->getTemplateEngine(), $this->frameworkFactory->getTemplateEngine());
	}
	
	/**
	 * Tests FrameworkFactory->getPublicDirectory()
	 *
	 * @author mnoerenberg
	 */
	public function testCreatePublicDirectory() {
		$this->assertInstanceOf('Simovative\Framework\Filesystem\Directory', $this->frameworkFactory->getPublicDirectory());
	}
	
	/**
	 * Tests FrameworkFactory->getApplicationDirectory()
	 *
	 * @author mnoerenberg
	 */
	public function testCreateApplicationDirectory() {
		$this->assertInstanceOf('Simovative\Framework\Filesystem\Directory', $this->frameworkFactory->getApplicationDirectory());
	}
}
