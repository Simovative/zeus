<?php
declare(strict_types=1);

namespace Simovative\Test\Integration\TestBundle\Routing;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Simovative\Test\Integration\TestBundle\ApplicationFactory;
use Simovative\Zeus\Command\HandlerRouterInterface;
use Simovative\Zeus\Http\Url\UrlMatcherInterface;

/**
 * @author Benedikt Schaller
 */
class TestHandlerRequestRouter implements HandlerRouterInterface
{
    /**
     * @var UrlMatcherInterface
     */
    private $urlMatcher;

    /**
     * @var ApplicationFactory
     */
    private $applicationFactory;

    /**
     * @author Benedikt Schaller
     * @param ApplicationFactory $applicationFactory
     * @param UrlMatcherInterface $urlMatcher
     */
    public function __construct(ApplicationFactory $applicationFactory, UrlMatcherInterface $urlMatcher) {
        $this->urlMatcher = $urlMatcher;
        $this->applicationFactory = $applicationFactory;
    }

    /**
     * @author Benedikt Schaller
     * @inheritDoc
     */
    public function route(ServerRequestInterface $request): ?RequestHandlerInterface
    {
        if ($this->urlMatcher->match($request->getUri(), '/handler')) {
            return $this->applicationFactory->createTestHandler();
        }
        return null;
    }
}