<?php
declare(strict_types=1);

namespace tests\Unit\Http\Url;

use PHPUnit\Framework\TestCase;
use Simovative\Zeus\Http\Url\Url;

/**
 * @author tp
 */
class UrlTest extends TestCase {
	
	private const URL = 'https://username:password@www.simovative.com:443/api/v1/event/20?param=1&user=1#myFragment';
	
	/**
	 * @var Url
	 */
	private $url;
	
	/**
	 * @author tp
	 * @inheritDoc
	 */
	protected function setUp() {
		parent::setUp();
		$this->url = new Url(self::URL);
	}
	
	/**
	 * @author tpawlow
	 * @return void
	 */
	public function testThatExceptionIsThrownForZeroComponent(): void {
		$this->expectException(\InvalidArgumentException::class);
		$this->url->getPathComponent(0);
	}
	
	/**
	 * @author tpawlow
	 * @return void
	 */
	public function testThatExceptionIsThrownForNotExistingComponent(): void {
		$this->expectException(\InvalidArgumentException::class);
		$this->url->getPathComponent(5);
	}
	
	/**
	 * @dataProvider provideNumberAndComponent
	 * @author tp
	 * @param int $componentNumber
	 * @param string|null $expectedComponent
	 * @return void
	 */
	public function testThatUrlComponentIsCorrect(int $componentNumber, ?string $expectedComponent): void {
		self::assertSame($expectedComponent, $this->url->getPathComponent($componentNumber));
	}
	
	/**
	 * @author tp
	 * @return array
	 */
	public function provideNumberAndComponent(): array {
		return array(
			array(1, 'api'),
			array(2, 'v1'),
			array(3, 'event'),
			array(4, '20')
		);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatSchemeIsParsed() {
		$this->assertSame('https', $this->url->getScheme());
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatHostIsParsed() {
		$this->assertSame('www.simovative.com', $this->url->getHost());
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatFragmentIsParsed() {
		$this->assertSame('myFragment', $this->url->getFragment());
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatPasswordIsParsed() {
		$this->assertSame('password', $this->url->getPassword());
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatUsernameIsParsed() {
		$this->assertSame('username', $this->url->getUser());
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatPortIsParsed() {
		$this->assertSame(443, $this->url->getPort());
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatPathIsParsed() {
		$this->assertSame('/api/v1/event/20', $this->url->getPath());
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatQueryIsParsed() {
		$this->assertSame('param=1&user=1', $this->url->getQuery());
	}
}
