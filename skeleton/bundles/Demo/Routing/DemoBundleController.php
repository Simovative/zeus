<?php
namespace Simovative\Skeleton\Demo\Routing;

use Command\CommandValidationResult;
use Simovative\Skeleton\Application\ApplicationFactory;
use Simovative\Skeleton\Demo\Command\LoginCommandBuilder;
use Simovative\Skeleton\Demo\Command\LogoutCommandBuilder;
use Simovative\Skeleton\Demo\DemoFactory;
use Simovative\Zeus\Bundle\BundleController;
use Simovative\Zeus\Command\CommandBuilderInterface;
use Simovative\Zeus\Command\CommandFailureResponse;
use Simovative\Zeus\Command\CommandRequest;
use Simovative\Zeus\Command\CommandResponseInterface;
use Simovative\Zeus\Command\CommandSuccessResponse;
use Simovative\Zeus\Content\Redirect;
use Simovative\Zeus\Dependency\MasterFactory;
use Simovative\Zeus\Http\Url\Url;

/**
 * @author Benedikt Schaller
 */
class DemoBundleController extends BundleController {
	
	/**
	 * @author Benedikt Schaller
	 * @return MasterFactory|DemoFactory
	 */
	protected function getMasterFactory() {
		return parent::getMasterFactory();
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritdoc
	 */
	public function whichContentForFailedValidation(CommandBuilderInterface $commandBuilder, CommandRequest $commandRequest) {
		if ($commandBuilder instanceof LoginCommandBuilder) {
			return $this->getMasterFactory()->createDemoLoginPage();
		}
		return null;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritdoc
	 */
	public function whichContentForCommandResult(CommandBuilderInterface $commandBuilder, CommandRequest $commandRequest, CommandResponseInterface $commandResponse) {
		if ($commandBuilder instanceof LoginCommandBuilder) {
			if ($commandResponse instanceof CommandSuccessResponse) {
				return new Redirect(new Url(ApplicationFactory::URL_PREFIX . '/demo/home?username=' . urlencode($commandResponse->getValue())));
			}
			if ($commandResponse instanceof CommandFailureResponse) {
				$loginPage = $this->getMasterFactory()->createDemoLoginPage();
				$loginPage->setValidationResult(new CommandValidationResult(false, array(), array('password' => $commandResponse->getValue())));
				return $loginPage;
			}
		}
		if ($commandBuilder instanceof LogoutCommandBuilder) {
			return new Redirect(new Url(ApplicationFactory::URL_PREFIX . '/demo/login'));
		}
		return null;
	}
}