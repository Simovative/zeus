<?php

namespace Simovative\Zeus\Console\SkeletonBuilder\Commands;

use Simovative\Zeus\Console\SkeletonBuilder\Model\Builds\BundleTemplateBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author shartmann
 */
class CreateBundleCliCommand extends Command {
	
	const ARG_BUNDLE_NAME = 'bundle_name';
	const ARG_PROJECT_ROOT = 'project_root';
	
	/**
	 * @var BundleTemplateBuilder
	 */
	protected $bundleTemplateBuilder;
	
	/**
	 * CreateBundleCliCommand constructor.
	 *
	 * @author shartmann
	 * @param BundleTemplateBuilder $bundleTemplateBuilder
	 * @param null|string $name
	 */
	public function __construct(BundleTemplateBuilder $bundleTemplateBuilder, $name = null) {
		$this->bundleTemplateBuilder = $bundleTemplateBuilder;
		parent::__construct($name);
	}
	
	/**
	 * @author shartmann
	 * @return void
	 */
	protected function configure() {
		$this
			->setName('create:bundle')
			->setDescription('Creates new application')
			->setHelp('This command helps you in creating a new application.')
			->addArgument(self::ARG_BUNDLE_NAME, InputArgument::REQUIRED, 'The name(space) of the bundle')
			->addArgument(self::ARG_PROJECT_ROOT, InputArgument::OPTIONAL, 'The root directory of the application (defaults to cwd)');
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
			$this->bundleTemplateBuilder->generate(
				$input->getArgument(self::ARG_PROJECT_ROOT),
				$input->getArgument(self::ARG_BUNDLE_NAME)
			);
		} catch (\Exception $exception) {
			throw new RuntimeException($exception->getMessage());
		}
		$output->writeln(
			array(
				'Bundle creation successful',
				'Register your bundle in your Applications Kernel to make use of it'
			)
		);
		return 0;
	}
}
