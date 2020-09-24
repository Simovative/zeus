<?php
declare(strict_types=1);

namespace Simovative\Zeus\Tests\Unit\Stream;

use Simovative\Zeus\Stream\Stream;
use PHPUnit\Framework\TestCase;

/**
 * @author Benedikt Schaller
 */
class StreamTest extends TestCase {

    /**
     * @var Stream
     */
    private $testStream;

	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

        $this->testStream = new Stream('file:///' . __DIR__ . '/../data/stream_test_file', 'r');
	}

	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function testThatStreamCanBeRead(): void {
		self::assertEquals('stream test content', $this->testStream->getContents());
	}

    /**
     * @author Benedikt Schaller
     * @return void
     */
    public function testThatStreamCanBeUsedAsString(): void {
		self::assertEquals('stream test content', (string) $this->testStream);
    }

    /**
     * @author Benedikt Schaller
     * @return void
     */
    public function testThatStreamCanBeReadPartially(): void {
        $text = $this->testStream->read(8);
		self::assertEquals('stream t', $text);
        $text2 = $this->testStream->read(11);
		self::assertEquals('est content', $text2);
    }

    /**
     * @author Benedikt Schaller
     * @return void
     */
    public function testThatStreamEndOfFileIsReached(): void {
        $this->testStream->read(8);
		self::assertFalse($this->testStream->eof());
        $this->testStream->read(12);
		self::assertTrue($this->testStream->eof());
    }

    /**
     * @author Benedikt Schaller
     * @return void
     */
    public function testThatStreamCanBeClosedAndReopened(): void {
        $text = $this->testStream->read(8);
        $this->testStream->close();
        $text2 = $this->testStream->read(8);
		self::assertEquals($text, $text2);
    }

    /**
     * @author Benedikt Schaller
     * @return void
     */
    public function testThatDoubleCallToCloseWontBreakAnything(): void {
        $text = $this->testStream->read(8);
        $this->testStream->close();
        $this->testStream->close();
        $text2 = $this->testStream->read(8);
		self::assertEquals($text, $text2);
    }

    /**
     * @author Benedikt Schaller
     * @return void
     */
    public function testThatNewStreamIsNotEndOfFile(): void {
        $endOfFile = $this->testStream->eof();
		self::assertFalse($endOfFile);
    }
}
