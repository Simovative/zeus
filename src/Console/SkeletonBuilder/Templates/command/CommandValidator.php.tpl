<?php
namespace {{appNamespace}}\{{bundleName}}\Command;

use Simovative\Zeus\Command\CommandRequest;
use Simovative\Zeus\Command\CommandValidator;
use Simovative\Zeus\Translator\TranslatorInterface;

class {{name}}CommandValidator extends CommandValidator {
	
	/**
	 * @var TranslatorInterface
	 */
	private $translator;
	
	/**
	 * @param TranslatorInterface $translator
	 */
	public function __construct(TranslatorInterface $translator) {
		$this->translator = $translator;
	}

	/**
 	 * @inheritdoc
	 */
	protected function validateRequest(CommandRequest $commandRequest) {
	}
}