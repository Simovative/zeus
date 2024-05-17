<?php
declare(strict_types=1);

namespace Simovative\Zeus\Http\Url;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;
use RuntimeException;

/**
 * @author mnoerenberg
 */
class Url implements UriInterface {

    /**
     * @var string
     */
    private $url;

    /**
     * @author mnoerenberg
     * @param string $url
     */
    public function __construct($url) {
        $this->url = $url;
    }

    /**
     * @author mnoerenberg
     * @return string
     */
    public function getPath(): string {
        return parse_url($this->url, PHP_URL_PATH) ?? '';
    }

    /**
     * @author mnoerenberg
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->url;
    }

    /**
     * @author Benedikt Schaller
     * @return string
     */
    public function getScheme(): string {
        return parse_url($this->url, PHP_URL_SCHEME) ?? '';
    }

    /**
     * @author Benedikt Schaller
     * @return string
     */
    public function getHost(): string {
        return parse_url($this->url, PHP_URL_HOST) ?? '';
    }

    /**
     * @author Benedikt Schaller
     * @return int|null
     */
    public function getPort(): ?int {
        return parse_url($this->url, PHP_URL_PORT);
    }

    /**
     * @author Benedikt Schaller
     * @return string|null
     */
    public function getUser(): ?string {
        return parse_url($this->url, PHP_URL_USER);
    }

    /**
     * @author Benedikt Schaller
     * @return string|null
     */
    public function getPassword(): ?string {
        return parse_url($this->url, PHP_URL_PASS);
    }

    /**
     * @author Benedikt Schaller
     * @return string
     */
    public function getQuery(): string {
        return parse_url($this->url, PHP_URL_QUERY) ?? '';
    }

    /**
     * @author Benedikt Schaller
     * @return string
     */
    public function getFragment(): string {
        return parse_url($this->url, PHP_URL_FRAGMENT) ?? '';
    }

    /**
     * Creates the called url from the given server array.
     *
     * @author Benedikt Schaller
     * @param mixed[] $serverArray
     * @return Url
     */
    public static function createFromServerArray(array $serverArray): Url {
        if (self::isForwardedRequest($serverArray)) {
            return self::createUrlForForwardedRequest($serverArray);
        }
        return self::createUrlForNormalRequest($serverArray);
    }

    /**
     * @author Benedikt Schaller
     * @param mixed[] $serverArray
     * @return bool
     */
    private static function isForwardedRequest(array $serverArray): bool
    {
        return isset($serverArray['HTTP_X_FORWARDED_HOST']);
    }

    /**
     * @author Benedikt Schaller
     * @param mixed[] $serverArray
     * @return Url
     */
    private static function createUrlForForwardedRequest(array $serverArray): Url
    {
        $serverProtocol = $serverArray['HTTP_X_FORWARDED_PROTO'] ?? 'http';
        $serverProtocol = strtolower($serverProtocol);
        $host = $serverArray['HTTP_X_FORWARDED_HOST'];
        $port = $serverArray['HTTP_X_FORWARDED_PORT'] ?? 80;
        $port = (int) $port;
        $standardHttpPorts = [80, 443];
        if (in_array($port, $standardHttpPorts)) {
            $port = '';
        } else {
            $port = ':' . $port;
        }
        $url = $serverProtocol . '://' . $host . $port . $serverArray['REQUEST_URI'];
        return new self($url);
    }

    /**
     * @author Benedikt Schaller
     * @param mixed[] $serverArray
     * @return Url
     */
    private static function createUrlForNormalRequest(array $serverArray): Url
    {
        $isSslEnabled = false;
        if (! empty($serverArray['HTTPS']) && $serverArray['HTTPS'] === 'on' ) {
            $isSslEnabled = true;
        }
        $serverProtocol = strtolower($serverArray['SERVER_PROTOCOL']);
        $protocol = strtolower(
            substr($serverProtocol, 0, strpos($serverProtocol, '/'))
        );
        if ($isSslEnabled && $protocol === 'http') {
            $protocol .= 's';
        }

        # Extract host and port
        $host = $serverArray['HTTP_HOST'];
        $hostContainsPort = preg_match('/:(\d+)$/', $serverArray['HTTP_HOST'], $portPosition);
        if ($hostContainsPort) {
            $portIndex = strpos($host, ':');
            $port = (int) substr($host, $portIndex + 1);
            $host = substr($host, 0, $portIndex);
        } else {
            $port = $serverArray['SERVER_PORT'] ?? null;
            if (! empty($port)) {
                $port = (int) $port;
            }
        }
        # remove port if we have a standard http/https request port, for nicer view
        $standardHttpPorts = [80, 443];
        $portString = '';
        if ($port !== null && ! in_array($port, $standardHttpPorts, true)) {
            $portString = ':' . $port;
        }

        $url = $protocol . '://' . $host . $portString . $serverArray['REQUEST_URI'];
        return new self($url);
    }

    /**
     * @author tp
     * @param int $componentNumber
     * @return string
     */
    public function getPathComponent(int $componentNumber): string {
        if ($componentNumber === 0) {
            throw new InvalidArgumentException('Component numbers begin at 1.');
        }
        $components = explode('/', $this->getPath());
        if (array_key_exists($componentNumber, $components)) {
            return $components[$componentNumber];
        }
        throw new InvalidArgumentException("Component '$componentNumber' not found.");
    }

    /**
     * @author Benedikt Schaller
     * @return string
     */
    public function getUserInfo(): string
    {
        return $this->getUser() ?? '';
    }

    /**
     * @author Benedikt Schaller
     * @inheritDoc
     */
    public function getAuthority(): string
    {
        throw new RuntimeException(__METHOD__ . ' is not implemented');
    }

    /**
     * @author Benedikt Schaller
     * @inheritDoc
     */
    public function withScheme($scheme): UriInterface
    {
        throw new RuntimeException(__METHOD__ . ' is not implemented');
    }

    /**
     * @author Benedikt Schaller
     * @inheritDoc
     */
    public function withUserInfo($user, $password = null): UriInterface
    {
        throw new RuntimeException(__METHOD__ . ' is not implemented');
    }

    /**
     * @author Benedikt Schaller
     * @inheritDoc
     */
    public function withHost($host): UriInterface
    {
        throw new RuntimeException(__METHOD__ . ' is not implemented');
    }

    /**
     * @author Benedikt Schaller
     * @inheritDoc
     */
    public function withPort($port): UriInterface
    {
        throw new RuntimeException(__METHOD__ . ' is not implemented');
    }

    /**
     * @author Benedikt Schaller
     * @inheritDoc
     */
    public function withPath($path): UriInterface
    {
        throw new RuntimeException(__METHOD__ . ' is not implemented');
    }

    /**
     * @author Benedikt Schaller
     * @inheritDoc
     */
    public function withQuery($query): UriInterface
    {
        throw new RuntimeException(__METHOD__ . ' is not implemented');
    }

    /**
     * @author Benedikt Schaller
     * @inheritDoc
     */
    public function withFragment($fragment): UriInterface
    {
        throw new RuntimeException(__METHOD__ . ' is not implemented');
    }
}
