<?php
namespace Simovative\Zeus\Command;

use Exception;
use Simovative\Zeus\Content\ValidatedContent;
use Simovative\Zeus\Dependency\MasterFactory;
use Simovative\Zeus\Exception\DispatchingException;
use Simovative\Zeus\Http\Request\HttpRequestDispatcherInterface;
use Simovative\Zeus\Http\Response\HttpResponseInterface;
use Simovative\Zeus\Http\Response\HttpResponseLocatorInterface;
use Simovative\Zeus\Http\Routing\RouteInterface;

class CommandDispatcher implements HttpRequestDispatcherInterface {
	
	/**
	 * @var ApplicationController
	 */
	private $applicationController;
	
	/**
	 * @var MasterFactory
	 */
	private $masterFactory;

    /**
     * @var HttpResponseLocatorInterface
     */
    private $httpResponseLocator;

    public function __construct(
		MasterFactory $masterFactory,
		ApplicationController $applicationController,
        HttpResponseLocatorInterface $httpResponseLocator
    ) {
		$this->applicationController = $applicationController;
		$this->masterFactory = $masterFactory;
        $this->httpResponseLocator = $httpResponseLocator;
    }
	
    /**
     * @throws Exception
     */
    public function dispatch(RouteInterface $route): HttpResponseInterface
    {
        $commandBuilder = $route->getHandler();
        $commandRequest = CommandRequest::fromHttpRequest($route->getRoutedRequest());
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
            $content = $this->applicationController->whichContentForCommandResult($commandBuilder, $commandRequest, $result);
        } else {
            // which redirect on invalid
            // apply validation result
            $content = $this->applicationController->whichContentForFailedValidation($commandBuilder, $commandRequest);
            if ($content instanceof ValidatedContent) {
                $content->setValidationResult($validationResult);
            } else {
                throw DispatchingException::createForPopulationError();
            }
        }

        return $this->httpResponseLocator->getResponseFor($content);
    }
}
