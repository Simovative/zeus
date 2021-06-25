<?php

namespace Simovative\Zeus\Console\SkeletonBuilder\Commands;

use Simovative\Zeus\Console\SkeletonBuilder\Model\Builds\CommandTemplateBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author shartmann
 */
class CreateCommandCliCommand extends Command {
	
	const ARG_NAME = 'name';
	const ARG_BUNDLE = 'bundle_name';
	const ARG_PROJECT_ROOT = 'project_root';
	
	/**
	 * @var CommandTemplateBuilder
	 */
	private $commandTemplateBuilder;
	
	/**
	 * CreateCommandCliCommand constructor.
	 *
	 * @author shartmann
	 * @param CommandTemplateBuilder $commandTemplateBuilder
	 * @param null|string $name
	 */
	public function __construct(
		CommandTemplateBuilder $commandTemplateBuilder,
		$name = null
	) {
		$this->commandTemplateBuilder = $commandTemplateBuilder;
		parent::__construct($name);
	}
	
	/**
	 * @author shartmann
	 * @return void
	 */
	protected function configure() {
		$this
			->setName('create:command')
			->setDescription('Creates new command')
			->setHelp('This command helps you in creating a new command.')
			->addArgument(self::ARG_NAME, InputArgument::REQUIRED, 'The commands name')
			->addArgument(self::ARG_BUNDLE, InputArgument::REQUIRED, 'For which bundle the command should be built')
			->addArgument(self::ARG_PROJECT_ROOT, InputArgument::OPTIONAL, 'The projects root directory (defaults to cwd)');
		
	}
	
	/**
	 * @author shartmann
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return void
	 */
	protected function interact(InputInterface $input, OutputInterface $output) {
		if (null === $path = $input->getArgument(self::ARG_PROJECT_ROOT)) {
			$input->setArgument(self::ARG_PROJECT_ROOT, getcwd());
		}
		// convert relative path to absolute path
		if ('/' !== substr($path, 0, 1)) {
			$input->setArgument(self::ARG_PROJECT_ROOT, realpath(getcwd() . '/' . $path));
		}
	}
	
	/**
	 * @author shartmann
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return int
	 */
	protected function execute(InputInterface $input, OutputInterface $output) {
		try {
			$this->commandTemplateBuilder->generate(
				$input->getArgument(self::ARG_PROJECT_ROOT),
				$input->getArgument(self::ARG_NAME),
				$input->getArgument(self::ARG_BUNDLE)
			);
		} catch(\Exception $exception) {
			throw new RuntimeException($exception->getMessage());
		}
		$output->writeln(
			array(
				'Command created',
				'Add some parameters and add the Commands classes to your factory',
				'A text file containing factory methods (if you are using the default BundleFactory) has been placed ' .
				'alongside the source code files'
			)
		);
		return 0;
	}
}
