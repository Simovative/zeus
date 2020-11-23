<?php

declare(strict_types=0);

namespace Simovative\Zeus\Http\Url;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;

/**
 * @author mnoerenberg
 */
class Url implements UriInterface
{
    
    /**
     * @var string
     */
    private $url;
    
    /**
     * @author mnoerenberg
     * @param string $url
     */
    public function __construct($url)
    {
        $this->url = $url;
    }
    
    /**
     * @author mnoerenberg
     * @return string
     */
    public function getPath()
    {
        return parse_url($this->url, PHP_URL_PATH);
    }
    
    /**
     * @author mnoerenberg
     * @return string
     */
    public function __toString()
    {
        return (string)$this->url;
    }
    
    /**
     * @author Benedikt Schaller
     * @return string
     */
    public function getScheme()
    {
        return parse_url($this->url, PHP_URL_SCHEME);
    }
    
    /**
     * @author Benedikt Schaller
     * @return string
     */
    public function getHost()
    {
        return parse_url($this->url, PHP_URL_HOST);
    }
    
    /**
     * @author Benedikt Schaller
     * @return string
     */
    public function getPort()
    {
        return parse_url($this->url, PHP_URL_PORT);
    }
    
    /**
     * @author Benedikt Schaller
     * @return string
     */
    public function getUser()
    {
        return parse_url($this->url, PHP_URL_USER);
    }
    
    /**
     * @author Benedikt Schaller
     * @return string
     */
    public function getPassword()
    {
        return parse_url($this->url, PHP_URL_PASS);
    }
    
    /**
     * @author Benedikt Schaller
     * @return string
     */
    public function getQuery()
    {
        return parse_url($this->url, PHP_URL_QUERY);
    }
    
    /**
     * @author Benedikt Schaller
     * @return string
     */
    public function getFragment()
    {
        return parse_url($this->url, PHP_URL_FRAGMENT);
    }
    
    /**
     * Creates the called url from the given server array.
     *
     * @author Benedikt Schaller
     * @param array $serverArray
     * @return Url
     */
    public static function createFromServerArray($serverArray)
    {
        $isSslEnabled = false;
        if (! empty($serverArray['HTTPS']) && $serverArray['HTTPS'] === 'on') {
            $isSslEnabled = true;
        }
        $serverProtocol = strtolower($serverArray['SERVER_PROTOCOL']);
        $protocol = substr($serverProtocol, 0, strpos($serverProtocol, '/'));
        if ($isSslEnabled) {
            $protocol .= 's';
        }
        preg_match('/:(\d+)$/', $serverArray['HTTP_HOST'], $portPosition);
        if (! empty($portPosition[0])) {
            $port = '';
        } else {
            $port = ':' . $serverArray['SERVER_PORT'];
        }
        $isHttpRequest = (! $isSslEnabled && $port == '80');
        $isHttpsRequest = ($isSslEnabled && $port == '443');
        if ($isHttpRequest || $isHttpsRequest) {
            $port = '';
        }
        $host = $serverArray['HTTP_HOST'];
        if (isset($serverArray['HTTP_X_FORWARDED_HOST'])) {
            $host = $serverArray['HTTP_X_FORWARDED_HOST'];
        }
        $url = $protocol . '://' . $host . $port . $serverArray['REQUEST_URI'];
        return new self($url);
    }
    
    /**
     * @author tp
     * @param int $componentNumber
     * @return string
     */
    public function getPathComponent(int $componentNumber): string
    {
        if ($componentNumber === 0) {
            throw new InvalidArgumentException('Component numbers begin at 1.');
        }
        $components = explode('/', $this->getPath());
        if (array_key_exists($componentNumber, $components)) {
            return $components[$componentNumber];
        }
        throw new InvalidArgumentException("Component '$componentNumber' not found.");
    }
    
    public function getAuthority()
    {
        // TODO: Implement getAuthority() method.
    }
    
    public function getUserInfo()
    {
        // TODO: Implement getUserInfo() method.
    }
    
    public function withScheme($scheme)
    {
        // TODO: Implement withScheme() method.
    }
    
    public function withUserInfo($user, $password = null)
    {
        // TODO: Implement withUserInfo() method.
    }
    
    public function withHost($host)
    {
        // TODO: Implement withHost() method.
    }
    
    public function withPort($port)
    {
        // TODO: Implement withPort() method.
    }
    
    public function withPath($path)
    {
        // TODO: Implement withPath() method.
    }
    
    public function withQuery($query)
    {
        // TODO: Implement withQuery() method.
    }
    
    public function withFragment($fragment)
    {
        // TODO: Implement withFragment() method.
    }
}
