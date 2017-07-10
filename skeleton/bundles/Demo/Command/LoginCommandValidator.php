<?php
namespace Simovative\Skeleton\Demo\Command;

use Simovative\Zeus\Command\CommandRequest;
use Simovative\Zeus\Command\CommandValidator;
use Simovative\Zeus\Translator\TranslatorInterface;

/**
 * @author Benedikt Schaller
 */
class LoginCommandValidator extends CommandValidator {
	
	const CORRECT_PASSWORD = 'test123';
	
	/**
	 * @var TranslatorInterface
	 */
	private $translator;
	
	/**
	 * @author Benedikt Schaller
	 * @param TranslatorInterface $translator
	 */
	public function __construct(TranslatorInterface $translator) {
		$this->translator = $translator;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritdoc
	 */
	protected function validateRequest(CommandRequest $commandRequest) {
		if (! $commandRequest->get('username')) {
			$this->addError('username', $this->translator->translate('is required'));
		}
		if (! preg_match('/^[a-zA-Z0-9]+$/', $commandRequest->get('username'))) {
			$this->addError('username', $this->translator->translate('Only alphanumeric characters are allowed as username'));
		}
		if (! $commandRequest->get('password')) {
			$this->addError('password', $this->translator->translate('is required'));
		}
	}
	
}