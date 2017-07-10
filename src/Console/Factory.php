<?php
namespace Simovative\Zeus\Console;

use Simovative\Zeus\Console\SkeletonBuilder\Commands\CreateApplicationCliCommand;
use Simovative\Zeus\Console\SkeletonBuilder\Commands\CreateBundleCliCommand;
use Simovative\Zeus\Console\SkeletonBuilder\Commands\CreateCommandCliCommand;
use Simovative\Zeus\Console\SkeletonBuilder\Commands\CreatePageCliCommand;
use Simovative\Zeus\Console\SkeletonBuilder\Commands\CreateValidatedPageCliCommand;
use Simovative\Zeus\Console\SkeletonBuilder\Model\Builds\ApplicationTemplateBuilder;
use Simovative\Zeus\Console\SkeletonBuilder\Model\Builds\BundleTemplateBuilder;
use Simovative\Zeus\Console\SkeletonBuilder\Model\Builds\CommandTemplateBuilder;
use Simovative\Zeus\Console\SkeletonBuilder\Model\Builds\PageTemplateBuilder;
use Simovative\Zeus\Console\SkeletonBuilder\Model\Helper\ApplicationNamespaceFinder;
use Simovative\Zeus\Console\SkeletonBuilder\Model\TemplateEngine;
use Symfony\Component\Console\Application;

/**
 * Factory for everything CLI related.
 *
 * @author shartmann
 */
class Factory {
	
	/**
	 * @var Application
	 */
	protected $cli;
	
	/**
	 * @author shartmann
	 * @return Application
	 */
	public function getCli() {
		if (null === $this->cli) {
			$this->cli = new Application();
		}
		return $this->cli;
	}
	
	/**
	 * @author shartmann
	 * @return CreateApplicationCliCommand
	 */
	public function getCreateApplicationCliCommand() {
		return new CreateApplicationCliCommand($this->getApplicationBuilder());
	}
	
	/**
	 * @author shartmann
	 * @return CreateBundleCliCommand
	 */
	public function getCreateBundleCliCommand() {
		return new CreateBundleCliCommand($this->getBundleBuilder());
	}
	
	/**
	 * @author shartmann
	 * @return CreateCommandCliCommand
	 */
	public function getCreateCommandCliCommand() {
		return new CreateCommandCliCommand($this->getCommandTemplateBuilder());
	}
	
	/**
	 * @author shartmann
	 * @return CreatePageCliCommand
	 */
	public function getCreatePageCliCommand() {
		return new CreatePageCliCommand($this->getPageTemplateBuiler());
	}
	
	/**
	 * @author shartmann
	 * @return CreateValidatedPageCliCommand
	 */
	public function getCreateValidatedPageCliCommand() {
		return new CreateValidatedPageCliCommand($this->getPageTemplateBuiler());
	}
	
	/**
	 * @author shartmann
	 * @return ApplicationTemplateBuilder
	 */
	public function getApplicationBuilder() {
		return new ApplicationTemplateBuilder($this->getTemplateEngine());
	}
	
	/**
	 * @author shartmann
	 * @return BundleTemplateBuilder
	 */
	public function getBundleBuilder() {
		return new BundleTemplateBuilder(
			$this->getTemplateEngine(),
			$this->getApplicationNamespaceFinder()
		);
	}
	
	/**
	 * @author shartmann
	 * @return CommandTemplateBuilder
	 */
	public function getCommandTemplateBuilder() {
		return new CommandTemplateBuilder(
			$this->getTemplateEngine(),
			$this->getApplicationNamespaceFinder()
		);
	}
	
	/**
	 * @author shartmann
	 * @return PageTemplateBuilder
	 */
	public function getPageTemplateBuiler() {
		return new PageTemplateBuilder(
			$this->getTemplateEngine(),
			$this->getApplicationNamespaceFinder()
		);
	}
	
	/**
	 * @author shartmann
	 * @return ApplicationNamespaceFinder
	 */
	public function getApplicationNamespaceFinder() {
		return new ApplicationNamespaceFinder();
	}
	
	/**
	 * @author shartmann
	 * @return TemplateEngine
	 */
	public function getTemplateEngine() {
		return new TemplateEngine();
	}
	
}