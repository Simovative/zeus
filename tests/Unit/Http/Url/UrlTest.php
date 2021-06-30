<?php
declare(strict_types=1);

namespace Simovative\Zeus\Tests\Unit\Http\Url;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Simovative\Zeus\Http\Url\Url;

/**
 * @author tp
 */
class UrlTest extends TestCase {
	
    private const URL_WITH_CREDENTIALS = 'https://username:password@www.simovative.com:443/api/v1/event/20?param=1&user=1#myFragment';
    
    private const URL = 'https://www.simovative.com/api/v1/event/20?param=1&user=1#myFragment';
    private const URL_HTTPS = 'on';
    private const URL_REQUEST_URI = '/api/v1/event/20?param=1&user=1#myFragment';
    private const URL_SERVER_PORT = 443;
    private const URL_SERVER_PROTOCOL = 'HTTPS/1.1';
    private const URL_HTTP_HOST = 'www.simovative.com:443';
    private const URL_PROTOCOL = 'https';
    private const URL_HTTP_HOST_WITHOUT_PORT = 'www.simovative.com';
	
	/**
	 * @var Url
	 */
	private $url;
	
	/**
	 * @author tp
	 * @inheritDoc
	 */
	protected function setUp(): void {
		parent::setUp();
        $this->url = new Url(self::URL_WITH_CREDENTIALS);
	}
	
	/**
	 * @author tp
	 * @return void
	 */
	public function testThatExceptionIsThrownForZeroComponent(): void {
		$this->expectException(InvalidArgumentException::class);
		$this->url->getPathComponent(0);
	}
	
	/**
	 * @author tp
	 * @return void
	 */
	public function testThatExceptionIsThrownForNotExistingComponent(): void {
		$this->expectException(InvalidArgumentException::class);
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
		self::assertSame('https', $this->url->getScheme());
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatHostIsParsed() {
		self::assertSame('www.simovative.com', $this->url->getHost());
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatFragmentIsParsed() {
		self::assertSame('myFragment', $this->url->getFragment());
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatPasswordIsParsed() {
		self::assertSame('password', $this->url->getPassword());
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatUsernameIsParsed() {
		self::assertSame('username', $this->url->getUser());
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatPortIsParsed() {
		self::assertSame(443, $this->url->getPort());
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatPathIsParsed() {
		self::assertSame('/api/v1/event/20', $this->url->getPath());
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatQueryIsParsed() {
		self::assertSame('param=1&user=1', $this->url->getQuery());
	}
    
    /**
     * @author Benedikt Schaller
     * @return void
     */
    public function testThatUrlIsCorrectlyParsedFromStandardServerArray(): void
    {
        $serverArray = [
            'HTTP_HOST' => self::URL_HTTP_HOST,
            'HTTPS' => self::URL_HTTPS,
            'REQUEST_URI' => self::URL_REQUEST_URI,
            'SERVER_PORT' => self::URL_SERVER_PORT,
            'SERVER_PROTOCOL' => self::URL_SERVER_PROTOCOL,
        ];
        $url = Url::createFromServerArray($serverArray);
        self::assertEquals(self::URL, $url->__toString());
    }
    
    /**
     * @author Benedikt Schaller
     * @return void
     */
    public function testThatUrlIsCorrectlyParsedFromForwardedServerArray(): void
    {
        $serverArray = [
            'HTTP_HOST' => 'somethingelse',
            'HTTPS' => 'noton',
            'REQUEST_URI' => self::URL_REQUEST_URI,
            'SERVER_PORT' => 8888,
            'SERVER_PROTOCOL' => 'shouldnotbeused',
            'HTTP_X_FORWARDED_PROTO' => self::URL_PROTOCOL,
            'HTTP_X_FORWARDED_HOST' => self::URL_HTTP_HOST_WITHOUT_PORT,
            'HTTP_X_FORWARDED_PORT' => self::URL_SERVER_PORT,
        ];
        $url = Url::createFromServerArray($serverArray);
        self::assertEquals(self::URL, $url->__toString());
    }
}
