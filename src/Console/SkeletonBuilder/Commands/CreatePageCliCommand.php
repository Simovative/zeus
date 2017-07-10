<?php

namespace Simovative\Zeus\Console\SkeletonBuilder\Commands;

use Simovative\Zeus\Console\SkeletonBuilder\Model\Builds\PageTemplateBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author shartmann
 */
class CreatePageCliCommand extends Command {
	
	const ARG_NAME = 'name';
	const ARG_BUNDLE = 'bundle_name';
	const ARG_PROJECT_ROOT = 'project_root';
	
	/**
	 * @var PageTemplateBuilder
	 */
	private $pageTemplateBuilder;
	
	/**
	 * CreatePageCliCommand constructor.
	 *
	 * @author shartmann
	 * @param PageTemplateBuilder $pageTemplateBuilder
	 * @param null $name
	 */
	public function __construct(PageTemplateBuilder $pageTemplateBuilder, $name = null) {
		$this->pageTemplateBuilder = $pageTemplateBuilder;
		return parent::__construct($name);
	}
	
	/**
	 * @author shartmann
	 * @return void
	 */
	protected function configure() {
		$this
			->setName('create:page')
			->setDescription('Creates new Page')
			->setHelp('This command helps you in creating a new page.')
			->addArgument(self::ARG_NAME, InputArgument::REQUIRED, 'The name of the page')
			->addArgument(self::ARG_BUNDLE, InputArgument::REQUIRED, 'Which bundle to build the page for')
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
			$this->pageTemplateBuilder->generate(
				$input->getArgument(self::ARG_PROJECT_ROOT),
				$input->getArgument(self::ARG_NAME),
				$input->getArgument(self::ARG_BUNDLE),
				PageTemplateBuilder::TYPE_PAGE
			);
		} catch(\Exception $exception) {
			throw new RuntimeException($exception->getMessage());
		}
		$output->writeln(
			array(
				'Page created successfully',
				'Add it to your bundles factory',
				'A text file containing factory methods (if you are using the default BundleFactory) has been placed ' .
				'alongside the source code files'
			)
		);
		return 0;
	}
}