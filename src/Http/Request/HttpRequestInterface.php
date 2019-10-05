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
	 * Returns if we have a header request.
	 *
	 * @author Benedikt Schaller
	 * @return boolean
	 */
	public function isHeader();
}
