<?php
namespace Simovative\Zeus\Command;

use Exception;
use Simovative\Zeus\Content\ValidatedContent;
use Simovative\Zeus\Dependency\MasterFactory;
use Simovative\Zeus\Exception\RouteNotFoundException;
use Simovative\Zeus\Http\Request\HttpRequestDispatcherInterface;
use Simovative\Zeus\Http\Request\HttpRequestInterface;

/**
 * @author mnoerenberg
 */
class CommandDispatcher implements HttpRequestDispatcherInterface {
	
	/**
	 * @var CommandRequestRouterChain
	 */
	private $router;
	
	/**
	 * @var ApplicationController
	 */
	private $applicationController;
	
	/**
	 * @var MasterFactory
	 */
	private $masterFactory;
	
	/**
	 * @author mnoerenberg
	 * @param CommandRequestRouterChain $router
	 * @param MasterFactory $masterFactory
	 * @param ApplicationController $applicationController
	 */
	public function __construct(CommandRequestRouterChain $router,
								MasterFactory $masterFactory,
								ApplicationController $applicationController) {
		
		$this->router = $router;
		$this->applicationController = $applicationController;
		$this->masterFactory = $masterFactory;
	}
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function dispatch(HttpRequestInterface $request) {
		// match url and create command request.
		$commandBuilder = $this->router->route($request);
		if (null === $commandBuilder) {
			throw new RouteNotFoundException($request->getUrl());
		}
		
		$commandRequest = CommandRequest::fromHttpRequest($request);
		$commandValidator = $commandBuilder->getCommandValidator();
		$validationResult = null;
		if (null !== $commandValidator) {
			$validationResult = $commandValidator->validate($commandRequest);
		}
		
		if (null === $validationResult || $validationResult->isValid()) {
			
			// create command handler by command.
			$command = $commandBuilder->createCommand($commandRequest);
			$commandHandler = $commandBuilder->createCommandHandler($this->masterFactory);
			
			// execute command handler.
			$result = $commandHandler->execute($command);
			
			// which redirect on success
			return $this->applicationController->whichContentForCommandResult($commandBuilder, $commandRequest, $result);
		}
		
		// which redirect on invalid
		// apply validation result
		$content = $this->applicationController->whichContentForFailedValidation($commandBuilder, $commandRequest);
		if (! ($validationResult->isValid() || $content instanceof ValidatedContent)) {
			// TODO: Special exception
			throw new Exception('Error messaged can not be populated to the content!');
		}
		if ($content instanceof ValidatedContent) {
			$content->setValidationResult($validationResult);
		}
		
		return $content;
	}
}
