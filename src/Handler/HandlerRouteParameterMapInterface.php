<?php

declare(strict_types=1);

namespace Simovative\Zeus\Command;

/**
 * Interface for route parameters.
 *
 * @author tp
 */
interface HandlerRouteParameterMapInterface
{
    
    /**
     * @author tpawlow
     * @param int $position
     * @return mixed
     */
    public function getRouteParameter(int $position);
}