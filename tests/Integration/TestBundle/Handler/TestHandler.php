<?php
declare(strict_types=1);

namespace Simovative\Test\Integration\TestBundle\Handler;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @author Benedikt Schaller
 */
class TestHandler implements RequestHandlerInterface
{

    /**
     * @inheritDoc
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(200, [], 'Welcome to your handler');
    }
}