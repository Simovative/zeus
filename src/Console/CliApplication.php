<?php
namespace Simovative\Zeus\Console;

/**
 * Wrapper and Bootstrapper for the Commandline Interface
 *
 * @author shartmann
 */
class CliApplication {
	
	/**
	 * @var Factory
	 */
	protected $factory;
	
	/**
	 * @var \Symfony\Component\Console\Application
	 */
	protected $cli;
	
	/**
	 * CliApplication constructor.
	 *
	 * @author shartmann
	 */
	public function __construct() {
		$this->factory = new Factory();
		$this->cli = $this->factory->getCli();
		$this->cli->add($this->factory->getCreateApplicationCliCommand());
		$this->cli->add($this->factory->getCreateBundleCliCommand());
		$this->cli->add($this->factory->getCreateCommandCliCommand());
		$this->cli->add($this->factory->getCreatePageCliCommand());
		$this->cli->add($this->factory->getCreateValidatedPageCliCommand());
	}
	
	/**
	 * @author shartmann
	 * @return int|mixed
	 */
	public function run() {
		return $this->cli->run();
	}
	
}