<?php

declare(strict_types=1);

namespace Simovative\Zeus\Tests\Unit\Handler;

use PHPUnit\Framework\TestCase;
use Simovative\Zeus\Command\HandlerRouteParameterMap;

/**
 * @author tp
 */
class HandlerRouteParameterMapTest extends TestCase
{
    
    /**
     * @author tpawlow
     * @return void
     */
    public function testRouteParametersAreCorrectlyMapped(): void
    {
        $map = new HandlerRouteParameterMap([0 => 'api', 1 => 'v1', 2 => 'skeleton', 3 => 'id']);
        self::assertSame('api', $map->getRouteParameter(0));
        self::assertSame('v1', $map->getRouteParameter(1));
        self::assertSame('skeleton', $map->getRouteParameter(2));
        self::assertSame('id', $map->getRouteParameter(3));
        self::assertNull($map->getRouteParameter(4));
    }
    
}
