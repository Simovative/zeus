<?php
namespace Simovative\Zeus\Command;

/**
 * Implements the command validator passthrough for the command builder.
 *
 * @author Benedikt Schaller
 * @deprecated Please use the interface CommandBuilderInterface
 */
abstract class CommandBuilderAbstract implements CommandBuilderInterface {
	
	/**
	 * @var CommandValidatorInterface
	 */
	private $commandValidator;
	
	/**
	 * @author Benedikt Schaller
	 * @param CommandValidatorInterface $commandValidator
	 */
	public function __construct(CommandValidatorInterface $commandValidator) {
		$this->commandValidator = $commandValidator;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritDoc
	 */
	public function getCommandValidator() {
		return $this->commandValidator;
	}
	
}