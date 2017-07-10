<?php
namespace Simovative\Zeus\Dependency;

use Simovative\Zeus\Http\Request\HttpRequestInterface;
use Simovative\Zeus\Http\Response\HttpResponseInterface;

/**
 * @author mnoerenberg
 */
interface KernelInterface {
	
	/**
	 * Handles exceptions and errors.
	 * Called by exception handler.
	 * 
	 * @author mnoerenberg
	 * @param \Exception|\Throwable $throwable
	 * @return HttpResponseInterface|string
	 */
	public function report($throwable);
	
	/**
	 * @author shartmann
	 * @param HttpRequestInterface $request
	 * @return HttpResponseInterface
	 */
	public function run(HttpRequestInterface $request);
	
}
