<?php
namespace Simovative\AC5\Framework\Test\Filesystem;

use org\bovigo\vfs\vfsStream;
use Simovative\Framework\Filesystem\Directory;

/**
 * FrameworkFactory test case.
 * 
 * @uathor mnoerenberg
 * @covers Simovative\Framework\Filesystem\Directory
 */
class DirectoryTest extends \PHPUnit_Framework_TestCase {
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp();
		
		// Test files with vfs
		$root = vfsStream::setup('FilesystemTest', 
									null, 
									array(
										'DirectoryTest' => array(), 
										'ChangeDirectoryTest' => array(), 
									)
								 );
	}
	
	/**
	 * Tests Directory->__construct
	 *
	 * @author mnoerenberg
	 */
	public function testDirectoryExists() {
		$directory = new Directory(vfsStream::url('FilesystemTest/DirectoryTest'), false);
		$this->assertTrue($directory->exists(), 'directory not exists');
	}
	
	/**
	 * Tests Directory->__construct
	 *
	 * @author mnoerenberg
	 */
	public function testCreateDirectoryExists() {
		$directory = new Directory(vfsStream::url('FilesystemTest/UNKNOWN'), true);
		$this->assertTrue($directory->exists(), 'directory not exists');
	}
	
	/**
	 * Tests Directory->getPath
	 *
	 * @author mnoerenberg
	 */
	public function testToGetCorrectPath() {
		$directory = new Directory(vfsStream::url('FilesystemTest/DirectoryTest'), false);
		$this->assertSame(vfsStream::url('FilesystemTest/DirectoryTest'), $directory->getPath());
	}
	
	/**
	 * Tests Directory->getPath
	 *
	 * @author mnoerenberg
	 */
	public function testToGetCorrectRealPath() {
		$expectedDirectoryPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('DirectoryTest');
		$directory = new Directory($expectedDirectoryPath, true);
		$this->assertSame(realpath($expectedDirectoryPath), $directory->getPath(true));
		$this->assertTrue(! is_bool($directory->getPath(true)));
		rmdir($expectedDirectoryPath);
	}
	
	/**
	 * Tests Directory->changeDirectory
	 *
	 * @author mnoerenberg
	 */
	public function testChangeDirectoryRelative() {
		$directory = new Directory(vfsStream::url('FilesystemTest/DirectoryTest'), false);
		$directory = $directory->changeDirectory('../ChangeDirectoryTest');
		$this->assertStringEndsWith('ChangeDirectoryTest', $directory->getPath(), $directory->getPath());
	}
}
