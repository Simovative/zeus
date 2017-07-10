<?php
namespace Simovative\Zeus\Command;

/**
 * Implements the command validator passthrough for the command builder.
 *
 * @author Benedikt Schaller
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
	 * @return CommandValidatorInterface
	 */
	public function getCommandValidator() {
		return $this->commandValidator;
	}
	
}