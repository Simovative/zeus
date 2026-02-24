<?php

declare(strict_types=1);

namespace Simovative\Zeus\Tests\Unit\Http;

use PHPUnit\Framework\TestCase;
use Simovative\Zeus\Configuration\Configuration;
use Simovative\Zeus\Dependency\MasterFactory;
use Simovative\Zeus\Http\Post\HttpPostRequest;
use Simovative\Zeus\Stream\PhpInputStream;
use Simovative\Zeus\Tests\Unit\Stream\PhpInputStreamMock;

class HttpRequestFactoryTest extends TestCase
{
    private array $originalServer;
    private array $originalRequest;
    private array $originalFiles;

    /**
     * @dataProvider contentTypeDataProvider
     */
    public function testThatJsonContentTypesAreParsedAsJson(string $contentType): void
    {
        $masterFactory = new MasterFactory(new Configuration([], __DIR__));

        PhpInputStreamMock::$content = '{"data": {"type": "articles", "id": "1"}}';

        $_SERVER = array_merge($_SERVER, [
            'REQUEST_METHOD' => 'POST',
            'CONTENT_TYPE' => $contentType,
            'REQUEST_URI' => '/api/v1/articles',
            'HTTP_HOST' => 'localhost',
            'SERVER_PROTOCOL' => 'HTTP/1.1'
        ]);
        $_REQUEST = [];
        $_FILES = [];

        $request = $masterFactory->createRequestFromGlobals();

        $this->assertInstanceOf(HttpPostRequest::class, $request);

        $parsedBody = $request->getParsedBody();
        $this->assertEquals('articles', $parsedBody->data->type);
        $this->assertEquals('1', $parsedBody->data->id);
    }

    /**
     * @dataProvider badContentTypeDataProvider
     */
    public function testItIgnoresWeirdContentTypes(string $contentType): void
    {
        $masterFactory = new MasterFactory(new Configuration([], __DIR__));

        PhpInputStreamMock::$content = '{"data": {"type": "articles", "id": "1"}}';

        $_SERVER = array_merge($_SERVER, [
            'REQUEST_METHOD' => 'POST',
            'CONTENT_TYPE' => $contentType,
            'REQUEST_URI' => '/api/v1/articles',
            'HTTP_HOST' => 'localhost',
            'SERVER_PROTOCOL' => 'HTTP/1.1'
        ]);
        $_REQUEST = [];
        $_FILES = [];

        $request = $masterFactory->createRequestFromGlobals();

        $this->assertInstanceOf(HttpPostRequest::class, $request);

        $parsedBody = $request->getParsedBody();
        $this->assertInstanceOf(PhpInputStream::class, $parsedBody);
        $this->assertSame('{"data": {"type": "articles", "id": "1"}}', $parsedBody->getContents());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->originalServer = $_SERVER;
        $this->originalRequest = $_REQUEST;
        $this->originalFiles = $_FILES;

        // autoload-dev missing
        require_once __DIR__ . '/../Stream/PhpInputStreamMock.php';

        stream_wrapper_unregister('php');
        stream_wrapper_register('php', PhpInputStreamMock::class);
    }

    protected function tearDown(): void
    {
        $_SERVER = $this->originalServer;
        $_REQUEST = $this->originalRequest;
        $_FILES = $this->originalFiles;

        stream_wrapper_restore('php');
        PhpInputStreamMock::$content = '';

        parent::tearDown();
    }

    public static function contentTypeDataProvider(): array
    {
        return [
            'standard json' => ['application/json'],
            'json api' => ['application/vnd.api+json'],
            'json + charset' => ['application/json; charset=utf-8'],
            'json api + charset' => ['application/vnd.api+json; charset=utf-8'],
        ];
    }

    public static function badContentTypeDataProvider(): array
    {
        return [
            [''],
            ['adf'],
        ];
    }
}