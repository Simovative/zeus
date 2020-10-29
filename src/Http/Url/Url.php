<?php
declare(strict_types=1);

namespace Simovative\Zeus\Http\Url;

use InvalidArgumentException;

/**
 * @author mnoerenberg
 */
class Url {
	
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
	public function getPath() {
		return parse_url($this->url, PHP_URL_PATH);
	}

	/**
	 * @author mnoerenberg
	 * @return string
	 */
	public function __toString() {
		return (string) $this->url;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return string
	 */
	public function getScheme() {
		return parse_url($this->url, PHP_URL_SCHEME);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return string
	 */
	public function getHost() {
		return parse_url($this->url, PHP_URL_HOST);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return string
	 */
	public function getPort() {
		return parse_url($this->url, PHP_URL_PORT);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return string
	 */
	public function getUser() {
		return parse_url($this->url, PHP_URL_USER);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return string
	 */
	public function getPassword() {
		return parse_url($this->url, PHP_URL_PASS);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return string
	 */
	public function getQuery() {
		return parse_url($this->url, PHP_URL_QUERY);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return string
	 */
	public function getFragment() {
		return parse_url($this->url, PHP_URL_FRAGMENT);
	}
	
	/**
	 * Creates the called url from the given server array.
	 *
	 * @author Benedikt Schaller
	 * @param mixed[] $serverArray
	 * @return Url
	 */
	public static function createFromServerArray($serverArray) {
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
}
