<?php
namespace Simovative\Zeus\Console\SkeletonBuilder\Commands;

use Simovative\Zeus\Console\SkeletonBuilder\Model\Builds\ApplicationTemplateBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author shartmann
 */
class CreateApplicationCliCommand extends Command {
	
	const ARG_NAMESPACE = 'namespace';
	const ARG_PREFIX = 'prefix';
	const ARG_PROJECT_ROOT = 'project_root';
	
	/**
	 * @var ApplicationTemplateBuilder
	 */
	protected $applicationTemplateBuilder;
	
	/**
	 * CreateApplicationCliCommand constructor.
	 *
	 * @author shartmann
	 * @param ApplicationTemplateBuilder $applicationTemplateBuilder
	 * @param null|string $name
	 */
	public function __construct(ApplicationTemplateBuilder $applicationTemplateBuilder, $name = null) {
		$this->applicationTemplateBuilder = $applicationTemplateBuilder;
		parent::__construct($name);
	}
	
	/**
	 * @author shartmann
	 * @return void
	 */
	protected function configure() {
		$this
			->setName('create:application')
			->setDescription('Creates new application')
			->setHelp('This command helps you in creating a new application.')
			->addArgument(self::ARG_NAMESPACE, InputArgument::REQUIRED, 'The applications root namespace')
			->addArgument(self::ARG_PREFIX, InputArgument::OPTIONAL, 'Additional prefix for the application bundle')
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
		
		if (null === $input->getArgument(self::ARG_PREFIX)) {
			$input->setArgument(self::ARG_PREFIX, '');
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
			$this->applicationTemplateBuilder->generate(
				$input->getArgument(self::ARG_PROJECT_ROOT),
				$input->getArgument(self::ARG_NAMESPACE),
				$input->getArgument(self::ARG_PREFIX)
			);
		} catch (\Exception $exception) {
			throw new RuntimeException($exception->getMessage());
		}
		$output->writeln(
			array(
				'Application created successfully',
				'Please continue with creating a bundle'
			)
		);
		return 0;
	}
}
