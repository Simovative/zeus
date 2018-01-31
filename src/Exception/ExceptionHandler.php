<?php
namespace Simovative\Zeus\Exception;

use Simovative\Zeus\Dependency\KernelInterface;

/**
 * @author shartmann
 */
class ExceptionHandler {
	
	/**
	 * @var KernelInterface
	 */
	private $kernel;
	
	/**
	 * @param KernelInterface $kernel
	 */
	public function __construct(KernelInterface $kernel) {
		$this->kernel = $kernel;
	}
	
	/**
	 * @author shartmann
	 * @param \Throwable|\Exception $throwable
	 * @return void
	 */
	public function handleThrowable($throwable) {
		$this->kernel->report($throwable);
	}
	
	/**
	 * The error will be converted to an error exception and thrown.
	 *
	 * @author mnoerenberg
	 * @param int $code
	 * @param string $message
	 * @param string $file
	 * @param string $line
	 * @throws \ErrorException
	 * @return void
	 */
	public function handleError($code, $message, $file, $line) {
		$this->handleThrowable(new \ErrorException($message, $code, null, $file, $line));
		if (function_exists('error_clear_last')) {
			error_clear_last();
		}
	}
	
	/**
	 * @return void
	 */
	public function register() {
		$that = $this;
		set_exception_handler(function ($throwable) use ($that) {
			$that->handleThrowable($throwable);
		});
		set_error_handler(function ($level, $message, $file, $line) use ($that) {
			$that->handleError($level, $message, $file, $line);
		});
		register_shutdown_function(function () use ($that) {
			$error = error_get_last();
			if (null !== $error) {
				$that->handleError($error['type'], $error['message'], $error['file'], $error['line']);
			}
		});
	}
}