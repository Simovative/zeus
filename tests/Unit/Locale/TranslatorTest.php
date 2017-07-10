<?php

namespace Simovative\AC5\Framework\Test\Locale;

use org\bovigo\vfs\vfsStream;
use Simovative\Framework\Cache\CacheInterface;
use Simovative\Framework\Filesystem\Directory;
use Simovative\Framework\Locale\Translator;

/**
 * The TranslatorTest class.
 *
 * @author kkaya
 * @covers \Simovative\Framework\Locale\Translator
 */
class TranslatorTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Tests the case where key not exists.
	 *
	 * @author kkaya
	 * @return void
	 */
	public function testKeyNotExists() {
		$translationDirectoryStub = $this->getTranslationDirectoryStub();
		$cacheStub = $this->getCacheStub();

		$translator = new Translator($translationDirectoryStub, $cacheStub, 'en_GB');
		$this->assertEquals('name', $translator->translate('name'));
	}

	/**
	 * Tests the case where key exists in the cache.
	 *
	 * @author kkaya
	 * @return void
	 */
	public function testKeyExistsInCache() {
		$translationDirectoryStub = $this->getTranslationDirectoryStub();
		$cacheStub = $this->getCacheStub();
		$cacheStub
			->method('exists')
			->with($this->equalTo('en_GB-name'))
			->willReturn(true);
		$cacheStub->method('get')->willReturn('Last name');

		$translator = new Translator($translationDirectoryStub, $cacheStub, 'en_GB');
		$this->assertEquals('Last name', $translator->translate('name'));
	}

	/**
	 * Tests the case where key not exists in the cache.
	 *
	 * @author kkaya
	 * @return void
	 */
	public function testKeyNotExistsInCache() {
		$map = array(
			array('en_GB-name', false),
			array('en_GB', true)
		);

		$translationDirectoryStub = $this->getTranslationDirectoryStub();
		$cacheStub = $this->getCacheStub();
		$cacheStub
			->method('exists')
			->will($this->returnValueMap($map));

		$translator = new Translator($translationDirectoryStub, $cacheStub, 'en_GB');
		$this->assertEquals('name', $translator->translate('name'));
	}

	/**
	 * Tests the case where the translation directory not exists.
	 *
	 * @author kkaya
	 * @return void
	 */
	public function testTranslationDirectoryNotExists() {
		$translationDirectoryStub = $this->getTranslationDirectoryStub();
		$translationDirectoryStub->method('exists')->willReturn(false);

		$cacheStub = $this->getCacheStub();
		$cacheStub->method('exists')->willReturn(false);

		$translator = new Translator($translationDirectoryStub, $cacheStub, 'en_GB');
		$this->assertEquals('name', $translator->translate('name'));
	}

	/**
	 * Tests the case where the translation file not exists.
	 *
	 * @author kkaya
	 * @return void
	 */
	public function testTranslationFileNotExists() {
		vfsStream::setup('testDirectory');
		$translationDirectoryStub = $this->getTranslationDirectoryStub();
		$translationDirectoryStub->method('exists')->willReturn(true);
		$translationDirectoryStub->method('getPath')->willReturn(vfsStream::url('testDirectory'));

		$cacheStub = $this->getCacheStub();
		$cacheStub->method('exists')->willReturn(false);

		$translator = new Translator($translationDirectoryStub, $cacheStub, 'en_GB');
		$this->assertEquals('name', $translator->translate('name'));
	}

	/**
	 * Tests the translation from a file.
	 *
	 * @author kkaya
	 * @return void
	 */
	public function testTranslationFromFile() {
		vfsStream::setup('testDirectory', null,
			array(
				'en_GB.properties' => '	[Profile]
										name = "First name"'
			)
		);

		$translationDirectoryStub = $this->getTranslationDirectoryStub();
		$translationDirectoryStub->method('exists')->willReturn(true);
		$translationDirectoryStub->method('getPath')->willReturn(vfsStream::url('testDirectory'));

		$cacheStub = $this->getCacheStub();
		$cacheStub->method('exists')->willReturn(false);

		$translator = new Translator($translationDirectoryStub, $cacheStub, 'en_GB');
		$this->assertEquals('First name', $translator->translate('name'));
	}

	/**
	 * Tests the translation reference from a file.
	 *
	 * @author kkaya
	 * @return void
	 */
	public function testTranslationReference() {
		vfsStream::setup('testDirectory', null,
			array(
				'en_GB.properties' => '
										[Profile]
										profile = "Profile"
										profiles.add = "{@profile} add"
									'
			)
		);

		$translationDirectoryStub = $this->getTranslationDirectoryStub();
		$translationDirectoryStub->method('exists')->willReturn(true);
		$translationDirectoryStub->method('getPath')->willReturn(vfsStream::url('testDirectory'));

		$cacheStub = $this->getCacheStub();
		$cacheStub->method('exists')->willReturn(false);

		$translator = new Translator($translationDirectoryStub, $cacheStub, 'en_GB');
		$this->assertEquals('Profile add', $translator->translate('profiles.add'));
	}

	/**
	 * Tests the case where a reference is mistyped.
	 *
	 * @author kkaya
	 * @return void
	 */
	public function testTranslationReferenceMistyping() {
		vfsStream::setup('testDirectory', null,
			array(
				'en_GB.properties' => '
										[Profile]
										profile = "Profile"
										profiles.add = "{@profilee} add"
									'
			)
		);

		$translationDirectoryStub = $this->getTranslationDirectoryStub();
		$translationDirectoryStub->method('exists')->willReturn(true);
		$translationDirectoryStub->method('getPath')->willReturn(vfsStream::url('testDirectory'));

		$cacheStub = $this->getCacheStub();
		$cacheStub->method('exists')->willReturn(false);

		$translator = new Translator($translationDirectoryStub, $cacheStub, 'en_GB');
		$this->assertEquals('profilee add', $translator->translate('profiles.add'));
	}

	/**
	 * @author kkaya
	 * @return \PHPUnit_Framework_MockObject_MockObject|Directory
	 */
	private function getTranslationDirectoryStub() {
		return $this->getMockBuilder('\Simovative\Framework\Filesystem\Directory')
			->disableOriginalConstructor()
			->getMock();
	}

	/**
	 * @author kkaya
	 * @return \PHPUnit_Framework_MockObject_MockObject|CacheInterface
	 */
	private function getCacheStub() {
		return $this->getMock('\Simovative\Framework\Cache\CacheInterface');
	}
}