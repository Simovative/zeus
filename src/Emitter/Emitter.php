<?php

declare(strict_types=1);

namespace Simovative\Zeus\Emitter;

use Psr\Http\Message\ResponseInterface;
use Simovative\Zeus\Emitter\Exception\HeaderAlreadySentException;
use Simovative\Zeus\Emitter\Exception\OutputAlreadySentException;

use function header;
use function headers_sent;
use function sprintf;
use function ob_get_length;
use function ob_get_level;

/**
 * @author mnoernberg
 */
final class Emitter implements EmitterInterface
{
    /**
     * @author mnoernberg
     * @param ResponseInterface $response
     *
     * @return void
     */
    public function emit(ResponseInterface $response): void
    {
        $this->ensureNoPreviousOutput();
        $this->emitHeaders($response);
        $this->emitBody($response);
    }

    /**
     * @author mnoernberg
     * @param ResponseInterface $response
     *
     * @return void
     */
    public function __invoke(ResponseInterface $response): void
    {
        $this->emit($response);
    }

    /**
     * @author mnoernberg
     * @return void
     */
    private function ensureNoPreviousOutput(): void
    {
        if (ob_get_level() > 0 && ob_get_length() > 0) {
            throw OutputAlreadySentException::forOutput();
        }

        if (headers_sent()) {
            throw HeaderAlreadySentException::forHeader();
        }
    }

    /**
     * @author mnoernberg
     * @param ResponseInterface $response
     *
     * @return void
     */
    private function emitHeaders(ResponseInterface $response): void
    {
        $reasonPhrase = $response->getReasonPhrase();
        $statusCode = $response->getStatusCode();

        $statusHeader = sprintf(
            'HTTP/%s %d%s',
            $response->getProtocolVersion(),
            $statusCode,
            ($reasonPhrase ? ' ' . $reasonPhrase : '')
        );

        header($statusHeader, true, $statusCode);

        foreach ($response->getHeaders() as $header => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $header, $value), true, $statusCode);
            }
        }
    }

    /**
     * @author mnoernberg
     * @param ResponseInterface $response
     *
     * @return void
     */
    private function emitBody(ResponseInterface $response): void
    {
        echo $response->getBody();
    }
}
