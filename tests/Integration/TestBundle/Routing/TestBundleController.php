<?php

namespace Simovative\Test\Integration\TestBundle\Routing;

use Simovative\Test\Integration\TestBundle\ApplicationFactory;
use Simovative\Test\Integration\TestBundle\Command\TestCommandBuilder;
use Simovative\Zeus\Bundle\BundleController;
use Simovative\Zeus\Command\CommandBuilderInterface;
use Simovative\Zeus\Command\CommandFailureResponse;
use Simovative\Zeus\Command\CommandRequest;
use Simovative\Zeus\Command\CommandResponseInterface;
use Simovative\Zeus\Command\CommandSuccessResponse;
use Simovative\Zeus\Command\CommandValidationResult;
use Simovative\Zeus\Content\Redirect;
use Simovative\Zeus\Dependency\MasterFactory;
use Simovative\Zeus\Exception\FilesystemException;
use Simovative\Zeus\Http\Url\Url;

if (!function_exists('array_key_first')) {
    function array_key_first(array $array)
    {
        foreach ($array as $key => $unused) {
            return $key;
        }
        return null;
    }
}

if (!function_exists("array_key_last")) {
    function array_key_last($array)
    {
        if (!is_array($array) || empty($array)) {
            return null;
        }

        return array_keys($array)[count($array) - 1];
    }
}


/**
 * @author Benedikt Schaller
 */
class TestBundleController extends BundleController
{

    public const TEST_URL_START = '/test';

    /**
     * @author Benedikt Schaller
     * @return MasterFactory|ApplicationFactory
     */
    protected function getMasterFactory()
    {
        return parent::getMasterFactory();
    }

    /**
     * @author Benedikt Schaller
     * @inheritdoc
     * @throws FilesystemException
     */
    public function whichContentForFailedValidation(
        CommandBuilderInterface $commandBuilder,
        CommandRequest $commandRequest
    ) {
        if ($commandBuilder instanceof TestCommandBuilder) {
            return $this->getMasterFactory()->createTestPage();
        }
        return null;
    }

    /**
     * @author Benedikt Schaller
     * @inheritdoc
     * @throws FilesystemException
     */
    public function whichContentForCommandResult(
        CommandBuilderInterface $commandBuilder,
        CommandRequest $commandRequest,
        CommandResponseInterface $commandResponse
    ) {
        if ($commandBuilder instanceof TestCommandBuilder) {
            $requestBody = $commandRequest->getBody();
            if (is_array($requestBody)) {
                $values = '';
                foreach ($requestBody as $key => $value) {
                    if (array_key_first($requestBody) === $key) {
                        $values .= '?';
                    } else {
                        if (array_key_last($requestBody) !== $key) {
                            $values .= '&';
                        }
                    }
                    $values .= $key . '=' . urlencode($value);
                }
                return new Redirect(new Url(ApplicationFactory::URL_PREFIX . self::TEST_URL_START . $values));
            }
            if ($commandResponse instanceof CommandSuccessResponse) {
                return new Redirect(
                    new Url(
                        ApplicationFactory::URL_PREFIX . self::TEST_URL_START . '?value=' . urlencode(
                            (string)$requestBody
                        )
                    )
                );
            }
            if ($commandResponse instanceof CommandFailureResponse) {
                $testPage = $this->getMasterFactory()->createTestPage();
                $testPage->setValidationResult(
                    new CommandValidationResult(false, array(), array('password' => $commandResponse->getValue()))
                );
                return $testPage;
            }
        }
        return null;
    }
}