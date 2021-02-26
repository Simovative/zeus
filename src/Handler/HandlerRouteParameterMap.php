<?php

declare(strict_types=1);

namespace Simovative\Zeus\Command;

/**
 * @author tpawlow
 */
class HandlerRouteParameterMap implements HandlerRouteParameterMapInterface
{
    
    /**
     * @var array
     */
    private $routeParameters;
    
    /**
     * @author tpawlow
     * @param string[] $routeParameters
     */
    public function __construct(array $routeParameters)
    {
        $this->routeParameters = $routeParameters;
    }
    
    /**
     * @author tp
     * @inheritDoc
     */
    public function getRouteParameter(int $position): ?string
    {
        return $this->routeParameters[$position] ?? null;
    }
}