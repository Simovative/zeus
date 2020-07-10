<?php
namespace Simovative\Zeus\Template;

use PHPUnit\Framework\TestCase;
use Simovative\Test\Integration\TestBundle\HttpTestResponse;
use Simovative\Test\Integration\TestBundle\Routing\TestBundleController;
use Simovative\Test\Integration\TestBundle\TestKernel;
use Simovative\Zeus\Configuration\Configuration;
use Simovative\Zeus\Dependency\MasterFactory;
use Simovative\Zeus\Http\Get\HttpGetRequest;
use Simovative\Zeus\Http\HttpDeleteRequest;
use Simovative\Zeus\Http\HttpHeaderRequest;
use Simovative\Zeus\Http\HttpPatchRequest;
use Simovative\Zeus\Http\HttpPutRequest;
use Simovative\Zeus\Http\Post\HttpPostRequest;
use Simovative\Zeus\Http\Url\Url;

/**
 * Tests the features of the kernel with all needed components like bundles, routing, dispatching, etc.
 */
class ExampleKernelTest extends TestCase {
	
	/**
	 * @var TestKernel
	 */
	private $kernel;
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	protected function setUp() {
		parent::setUp();
		
		$factory = new MasterFactory(new Configuration(['bundle_dir' => __DIR__], dirname(__DIR__)));
		$this->kernel = new TestKernel($factory);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatGetRequestIsDispatched() {
		$request = new HttpGetRequest(new Url('/test'), []);
		$response = $this->kernel->run($request, false);
		$content = '';
		if ($response instanceof HttpTestResponse) {
			$content = $response->getContent()->render();
		}
		$this->assertStringContainsString('Welcome to your home page', $content, 'Expected string of test template is not contained in output of /test page');
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatSuccessfulPostRequestReturnsRedirect() {
		$request = new HttpPostRequest(new Url('/test'), [], [], []);
		$response = $this->kernel->run($request, false);
		$content = '';
		if ($response instanceof HttpTestResponse) {
			$content = $response->getContent()->render();
		}
		$this->assertStringStartsWith(TestBundleController::TEST_URL_START, $content, 'Expected string of test url start is not returned as redirect for test post request');
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatSuccessfulHeaderRequestIsDispatched() {
		$request = new HttpHeaderRequest(new Url('/test'), []);
		$response = $this->kernel->run($request, false);
		$content = '';
		if ($response instanceof HttpTestResponse) {
			$content = $response->getContent()->render();
		}
		$this->assertStringContainsString('Welcome to your home page', $content, 'Expected string of test template is not contained in output of /test page');
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatSuccessfulPutRequestReturnsRedirect() {
		$request = new HttpPutRequest(new Url('/test'), []);
		$response = $this->kernel->run($request, false);
		$content = '';
		if ($response instanceof HttpTestResponse) {
			$content = $response->getContent()->render();
		}
		$this->assertStringStartsWith(TestBundleController::TEST_URL_START, $content, 'Expected string of test url start is not returned as redirect for test put request');
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatSuccessfulPatchRequestReturnsRedirect() {
		$request = new HttpPatchRequest(new Url('/test'), []);
		$response = $this->kernel->run($request, false);
		$content = '';
		if ($response instanceof HttpTestResponse) {
			$content = $response->getContent()->render();
		}
		$this->assertStringStartsWith(TestBundleController::TEST_URL_START, $content, 'Expected string of test url start is not returned as redirect for test patch request');
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatSuccessfulDeleteRequestReturnsRedirect() {
		$request = new HttpDeleteRequest(new Url('/test'), []);
		$response = $this->kernel->run($request, false);
		$content = '';
		if ($response instanceof HttpTestResponse) {
			$content = $response->getContent()->render();
		}
		$this->assertStringStartsWith(TestBundleController::TEST_URL_START, $content, 'Expected string of test url start is not returned as redirect for test delete request');
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	protected function tearDown() {
		$this->kernel = null;
		
		parent::tearDown();
	}

    /**
     * @author Benedikt Schaller
     * @return void
     */
    public function testThatCommandRequestHasBody() {
        $bodyParameters = ['test' => 'body'];
        $request = new HttpPostRequest(
            new Url('/test'),
            $bodyParameters,
            ['CONTENT_TYPE' => 'application/x-www-form-urlencoded'],
            []
        );
        $response = $this->kernel->run($request, false);
        $content = '';
        if ($response instanceof HttpTestResponse) {
            $content = $response->getContent()->render();
        }
        $this->assertEquals(TestBundleController::TEST_URL_START . '?test=body', $content);
    }
}

