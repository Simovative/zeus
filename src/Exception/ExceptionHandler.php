<?php
namespace Simovative\Zeus\Exception;

use Simovative\Zeus\Http\Response\HttpResponse;
use Simovative\Zeus\Dependency\KernelInterface;
use Simovative\Zeus\Logger\LoggerInterface;

/**
 * @author mnoerenberg
 */
class ExceptionHandler {
	
	/**
	 * @var KernelInterface
	 */
	private $kernel;
	
	/**
	 * @var LoggerInterface
	 */
	private $logger;
	
	/**
	 * @author mnoerenberg
	 * @param KernelInterface $kernel
	 * @param LoggerInterface $logger
	 */
	public function __construct(KernelInterface $kernel, LoggerInterface $logger) {
		$this->kernel = $kernel;
		$this->logger = $logger;
	}
	
	/**
	 * @author mnoerenberg
	 * @return void
	 */
	public function registerExceptionHandler() {
		$that = $this;
		set_exception_handler(function ($throwable) use ($that) {
			$that->log($throwable);
			$that->handleException($throwable);
		});
		set_error_handler(array($this, 'handleError'));
		register_shutdown_function(function () use ($that) {
			$error = error_get_last();
			$that->handleError($error['type'], $error['message'], $error['file'], $error['line']);
		});
	}
	
	/**
	 * @author mnoerenberg
	 * @param \Exception|\Throwable $throwable
	 * @return void
	 */
	public function log($throwable) {
		$currentDate = new \DateTime();
		$message = $currentDate->format('[Y-m-d H:i:s]: ') . $throwable->getFile() . ' - on line ' . $throwable->getLine() . "\n";
		$message .= $throwable->getMessage() . "\n";
		$message .= $throwable->getTraceAsString() . "\n";
		$this->logger->log($message);
	}
	
	/**
	 * @author mnoerenberg
	 * @param \Exception|\Throwable $throwable
	 * @return void
	 */
	public function handleException($throwable) {
		$response = $this->kernel->report($throwable);
		if ($response instanceof HttpResponse) {
			$response->send();
		} else {
			echo $response;
		}
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
		//$errorIsEnabled = (bool)($code & ini_get('error_reporting'));
		$errorReporting = E_ALL & ~E_DEPRECATED & ~E_NOTICE;
		$errorIsEnabled = (bool)($code & $errorReporting);
		if ($errorIsEnabled) {
			$this->handleException(new \ErrorException($message, $code, null, $file, $line));
		}
	}
}
