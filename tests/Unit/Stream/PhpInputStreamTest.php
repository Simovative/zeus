<?php
declare(strict_types=1);

namespace Simovative\Zeus\Tests\Unit\Stream;

use PHPUnit\Framework\TestCase;
use Simovative\Zeus\Stream\PhpInputStream;

/**
 * Class PhpInputStreamTest
 * @package Simovative\Zeus\Tests\Unit\Stream
 */
class PhpInputStreamTest extends TestCase {

    /**
     * @author Benedikt Schaller
     * @return void
     */
    public function testThatStreamCanBeConstructed(): void {
        $inputSteam = new PhpInputStream();
		self::assertFalse($inputSteam->eof());
    }
}