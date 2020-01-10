<?php
declare(strict_types=1);

namespace tests\Unit\Http\Url;

use PHPUnit\Framework\TestCase;
use Simovative\Zeus\Http\Url\Url;

/**
 * @author tp
 */
class UrlTest extends TestCase {
	
	private const URL = 'https://www.simovative.com:443/api/v1/event/20?param=1&user=1';
	
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
	 * @dataProvider provideNumberAndComponent
	 * @author tp
	 * @param int $componentNumber
	 * @param string|null $expectedComponent
	 * @return void
	 */
	public function testThatUrlComponentIsCorrect(int $componentNumber, ?string $expectedComponent): void {
		self::assertEquals($expectedComponent, $this->url->getPathComponent($componentNumber));
	}
	
	/**
	 * @author tp
	 * @return array
	 */
	public function provideNumberAndComponent(): array {
		return array(
			array(0, null),
			array(1, 'api'),
			array(2, 'v1'),
			array(3, 'event'),
			array(4, '20'),
			array(5, null)
		);
	}
}
