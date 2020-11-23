<?php

namespace Simovative\Zeus\Http\Url;

use Psr\Http\Message\UriInterface;

/**
 * @author mnoerenberg
 */
interface UrlMatcherInterface
{
    
    /**
     * Returns true if route is matched.
     *
     * @author mnoerenberg
     * @author shartmann
     * @param UriInterface $uri
     * @param string $route
     * @param bool $matchPath if set to true the server name is ignored
     * @return bool
     */
    public function match(UriInterface $uri, $route, $matchPath = true);
    
    /**
     * Returns true if any of the given routes is matched.
     *
     * @author Benedikt Schaller
     * @author shartmann
     * @param UriInterface $uri
     * @param string[] $routes
     * @param bool $matchPath if set to true the server name is ignored
     * @return bool
     */
    public function matchAny(UriInterface $uri, $routes, $matchPath = true);
}
