<?php
namespace Simovative\Test\Integration\TestBundle\Command;

use Simovative\Zeus\Command\CommandInterface;
use Simovative\Zeus\Command\CommandRequest;

/**
 * @author Benedikt Schaller
 */
class TestCommand implements CommandInterface {

    /**
     * @var CommandRequest
     */
    private $commandRequest;

    /**
     * @author Benedikt Schaller
     * @param CommandRequest $commandRequest
     */
	public function __construct(CommandRequest $commandRequest) {
        $this->commandRequest = $commandRequest;
    }

    /**
     * @author Benedikt Schaller
     * @return CommandRequest
     */
    public function getCommandRequest(): CommandRequest {
        return $this->commandRequest;
    }
}