<?php
namespace Simovative\Zeus\Http\Request;

use Simovative\Zeus\Http\Url\Url;

/**
 * @author mnoerenberg
 */
interface HttpRequestInterface {

	/**
	 * Returns if the request contains a parameter with the given name.
	 *
	 * @author mnoerenberg
	 * @param string $name
	 * @return boolean
	 */
	public function has($name);
	
	/**
     * Returns the parameter with the given name.
     *
     * @author mnoerenberg
     * @param string $name
     * @param mixed $default - default: null
     * @return mixed
     */
	public function get($name, $default = null);
	
	/**
	 * Returns all parameters given by request.
	 *
	 * @author mnoerenberg
	 * @return string[]
	 */
	public function all();

	/**
     * Returns the url of the current request.
     *
     * @author mnoerenberg
     * @return Url
     */
	public function getUrl();
	
	/**
	 * Returns if we have a POST request.
	 *
	 * @author mnoerenberg
	 * @return boolean
	 */
	public function isPost();

	/**
	 * Returns if we have a GET request.
	 *
	 * @author mnoerenberg
	 * @return boolean
	 */
	public function isGet();
	
	/**
	 * Returns if we have a put request.
	 *
	 * @author Benedikt Schaller
	 * @return boolean
	 */
	public function isPut();
	
	/**
	 * Returns if we have a patch request.
	 *
	 * @author Benedikt Schaller
	 * @return boolean
	 */
	public function isPatch();
	
	/**
	 * Returns if we have a delete request.
	 *
	 * @author Benedikt Schaller
	 * @return boolean
	 */
	public function isDelete();
	
	/**
	 * Returns if we have a head request.
	 *
	 * @author Benedikt Schaller
	 * @return boolean
	 */
	public function isHead();

    /**
     * Returns if we have an option request.
     */
    public function isOption(): bool;
	
	/**
	 * Retrieve any parameters provided in the request body.
	 *
	 * If the request Content-Type is either application/x-www-form-urlencoded
	 * or multipart/form-data, and the request method is POST, this method MUST
	 * return the contents of $_POST.
	 *
	 * Otherwise, this method may return any results of deserializing
	 * the request body content; as parsing returns structured content, the
	 * potential types MUST be arrays or objects only. A null value indicates
	 * the absence of body content.
	 *
	 * @return null|array|object The deserialized body parameters, if any.
	 *     These will typically be an array or object.
	 */
	public function getParsedBody();
	
	/**
	 * @author Benedikt Schaller
	 * @return string|null
	 */
	public function getContentType(): ?string;
	
	/**
	 * Retrieve server parameters.
	 *
	 * Retrieves data related to the incoming request environment,
	 * typically derived from PHP's $_SERVER superglobal. The data IS NOT
	 * REQUIRED to originate from $_SERVER.
	 *
	 * @return array
	 */
	public function getServerParams(): array;
}
