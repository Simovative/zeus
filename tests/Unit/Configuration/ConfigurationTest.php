<?php
namespace Simovative\AC5\Framework\Test\Configuration;

use Simovative\Framework\Configuration\Configuration;
use Simovative\Framework\Filesystem\File;
use org\bovigo\vfs\vfsStream;

/**
 * Configuration test case.
 * @author tpawlow
 * @covers \Simovative\Framework\Configuration\Configuration
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 * @var Configuration
	 */
	private $configuration;

	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp();
		$iterator = 1;
		$this->configuration = new Configuration(array(
			Configuration::APPLICATION_DIRECTORY_PATH => $iterator++, 
			Configuration::APPLICATION_DOMAIN => $iterator++, 
			Configuration::APPLICATION_NAME => $iterator++, 
			Configuration::APPLICATION_VERSION => $iterator++, 
			Configuration::DATA_DIRECTORY_PATH => $iterator++, 
			Configuration::DATABASE_HOST => $iterator++, 
			Configuration::DATABASE_NAME => $iterator++, 
			Configuration::DATABASE_PASSWORD => $iterator++, 
			Configuration::DATABASE_USER => $iterator++, 
			Configuration::DEFAULT_LANGUAGE => $iterator++, 
			Configuration::MODULE_DIRECTORY_PATH => $iterator++, 
			Configuration::PUBLIC_DIRECTORY_PATH => $iterator++, 
			Configuration::TRANSLATION_DIRECTORY_PATH => $iterator++, 
		), __DIR__);
	}

	/**
	 * Tests the configuration values.
	 * @author tpawlow
	 */
	public function testConfigurationValues() {
		$iterator = 1;
		$this->assertEquals($iterator++, $this->configuration->getApplicationDirectoryPath());
		$this->assertEquals($iterator++, $this->configuration->getApplicationDomain());
		$this->assertEquals($iterator++, $this->configuration->getApplicationName());
		$this->assertEquals($iterator++, $this->configuration->getApplicationVersion());
		$this->assertEquals($iterator++, $this->configuration->getDataDirectoryPath());
		$this->assertEquals($iterator++, $this->configuration->getDatabaseHost());
		$this->assertEquals($iterator++, $this->configuration->getDatabaseName());
		$this->assertEquals($iterator++, $this->configuration->getDatabasePassword());
		$this->assertEquals($iterator++, $this->configuration->getDatabaseUser());
		$this->assertEquals($iterator++, $this->configuration->getDefaultLanguage());
		$this->assertEquals($iterator++, $this->configuration->getModuleDirectoryPath());
		$this->assertEquals($iterator++, $this->configuration->getPublicDirectoryPath());
		$this->assertEquals($iterator++, $this->configuration->getTranslationDirectoryPath());
		$this->assertEquals(__DIR__, $this->configuration->getApplicationBaseDirectoryPath());
		$this->assertNotEmpty($this->configuration->getUniqueApplicationKey());
	}

	/**
	 * Tests a configuration from file.
	 * 
	 * @author tpawlow
	 */
	public function testCreateFromFile() {
		// Test files with vfs
		vfsStream::setup('ConfigurationTest', null,	
			array(
				'test.ini' => 'applicationName = "Simo"'
			)
		);
		
		$file = new File(vfsStream::url('ConfigurationTest/test.ini'), __DIR__);
		$fileConfiguration = Configuration::createFromFile($file, __DIR__);
		$this->assertInstanceOf('Simovative\Framework\Configuration\Configuration', $fileConfiguration);
		$this->assertNotEmpty($fileConfiguration->getUniqueApplicationKey());
		$this->assertNotEquals($this->configuration->getUniqueApplicationKey(), $fileConfiguration->getUniqueApplicationKey());
		$this->assertEquals('Simo', $fileConfiguration->getApplicationName());
	}

	/**
	 * Tests a file not found exception.
	 * 
	 * @author tpawlow
	 */
	public function testThrowsExceptionWhenCallingNonExistingMethod() {
		$this->setExpectedException('\Simovative\Framework\Exception\FileNotFoundException');
		Configuration::createFromFile(new File(''), __DIR__);
	}
}

