<?php
namespace Simovative\Zeus\Command;

use Simovative\Zeus\Content\ValidatedContent;
use Simovative\Zeus\Dependency\MasterFactory;
use Simovative\Zeus\Http\Post\HttpPostRequest;
use Simovative\Zeus\Http\Post\HttpPostRequestDispatcherInterface;

/**
 * @author mnoerenberg
 */
class CommandDispatcher implements HttpPostRequestDispatcherInterface {
	
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
	public function dispatch(HttpPostRequest $request) {
		// match url and create command request.
		$commandBuilder = $this->router->route($request);
		$commandRequest = CommandRequest::fromHttpPostRequest($request);
		$validationResult = $commandBuilder->getCommandValidator()->validate($commandRequest);
		if ($validationResult->isValid()) {
			
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
			throw new \Exception('Error messaged can not be populated to the content!');
		}
		if ($content instanceof ValidatedContent) {
			$content->setValidationResult($validationResult);
		}
		
		return $content;
	}
}
