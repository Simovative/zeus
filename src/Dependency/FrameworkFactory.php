<?php

namespace Simovative\Zeus\Dependency;

use DateTimeZone;
use Simovative\Zeus\Cache\ArrayCache;
use Simovative\Zeus\Command\ApplicationController;
use Simovative\Zeus\Command\CommandDispatcher;
use Simovative\Zeus\Command\CommandRequestRouterChain;
use Simovative\Zeus\Command\HandlerDispatcher;
use Simovative\Zeus\Command\HandlerRouterChainInterface;
use Simovative\Zeus\Emitter\Emitter;
use Simovative\Zeus\Emitter\EmitterInterface;
use Simovative\Zeus\Exception\ExceptionHandler;
use Simovative\Zeus\Exception\FilesystemException;
use Simovative\Zeus\Filesystem\Directory;
use Simovative\Zeus\Filesystem\File;
use Simovative\Zeus\Handler\HandlerRouterChain;
use Simovative\Zeus\Http\Get\HttpGetRequestDispatcher;
use Simovative\Zeus\Http\Get\HttpGetRequestRouterChain;
use Simovative\Zeus\Http\Json\JsonEncodingService;
use Simovative\Zeus\Http\Json\JsonEncodingServiceInterface;
use Simovative\Zeus\Http\Request\HttpRequestDispatcherLocator;
use Simovative\Zeus\Http\Response\HttpResponseLocator;
use Simovative\Zeus\Http\Routing\HttpRouter;
use Simovative\Zeus\Http\Routing\RouteFactory;
use Simovative\Zeus\Http\Routing\RouteFactoryInterface;
use Simovative\Zeus\Http\Url\UrlMatcher;
use Simovative\Zeus\Http\Url\UrlMatcherInterface;
use Simovative\Zeus\Intl\IntlDateTimeConverter;
use Simovative\Zeus\Intl\IntlFormatter;
use Simovative\Zeus\Intl\IntlFormatterInterface;
use Simovative\Zeus\Intl\IntlSettings;
use Simovative\Zeus\Logger\SapiLogger;
use Simovative\Zeus\Session\Session;
use Simovative\Zeus\Session\SessionInterface;
use Simovative\Zeus\Session\Storage\Handler\SessionFileHandler;
use Simovative\Zeus\Session\Storage\NativeSessionStorage;
use Simovative\Zeus\Template\BootstrapFormPopulation;
use Simovative\Zeus\Template\SmartyTemplateEngine;
use Simovative\Zeus\Template\TemplateEngineInterface;
use Simovative\Zeus\Translator\Translator;
use Smarty;

class FrameworkFactory extends Factory
{

    /**
     * @var HttpGetRequestRouterChain|NULL
     */
    private $getRequestRouterChain;

    /**
     * @var CommandRequestRouterChain|NULL
     */
    private $commandRequestRouterChain;

    /**
     * @var ApplicationController|NULL
     */
    private $applicationController;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var HttpResponseLocator
     */
    private $httpResponseLocator;

    /**
     * @var UrlMatcherInterface
     */
    private $urlMatcher;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var IntlFormatter
     */
    private $intlFormatter;

    /**
     * @var IntlDateTimeConverter
     */
    private $intlDateTimeConverter;

    /**
     * @var HandlerRouterChainInterface|null
     */
    private $handlerRouterChain;

    public function createEmitter(): EmitterInterface
    {
        return new Emitter();
    }

    public function getHttpResponseLocator(): HttpResponseLocator
    {
        if ($this->httpResponseLocator === null) {
            $this->httpResponseLocator = $this->getMasterFactory()->createHttpResponseLocator();
        }
        return $this->httpResponseLocator;
    }

    public function createHttpResponseLocator(): HttpResponseLocator
    {
        return new HttpResponseLocator();
    }

    public function createHttpRequestDispatcherLocator(): HttpRequestDispatcherLocator
    {
        return new HttpRequestDispatcherLocator($this);
    }

    public function createRouter(): HttpRouter
    {
        return new HttpRouter(
            $this->getCommandRequestRouterChain(),
            $this->getHttpGetRequestRouterChain(),
            $this->getHandlerRouterChain(),
            $this->createRouteFactory()
        );
    }

    //***************************************************
    // START EXCEPTION
    //***************************************************

    public function createExceptionHandler(KernelInterface $kernel): ExceptionHandler
    {
        return new ExceptionHandler($kernel);
    }

    //***************************************************
    // START Logger
    //***************************************************

    public function createSapiLogger(): SapiLogger
    {
        return new SapiLogger();
    }

    //***************************************************
    // START GET
    //***************************************************

    public function getHttpGetRequestRouterChain(): HttpGetRequestRouterChain
    {
        if ($this->getRequestRouterChain === null) {
            $this->getRequestRouterChain = $this->createHttpGetRequestRouterChain();
        }
        return $this->getRequestRouterChain;
    }

    private function createHttpGetRequestRouterChain(): HttpGetRequestRouterChain
    {
        return new HttpGetRequestRouterChain();
    }

    public function createHttpGetRequestDispatcher(): HttpGetRequestDispatcher
    {
        return new HttpGetRequestDispatcher(
            $this->getMasterFactory()->getHttpResponseLocator()
        );
    }

    //***************************************************
    // START POST
    //***************************************************

    public function getCommandRequestRouterChain(): CommandRequestRouterChain
    {
        if ($this->commandRequestRouterChain === null) {
            $this->commandRequestRouterChain = $this->createHttpPostRequestRouterChain();
        }
        return $this->commandRequestRouterChain;
    }

    private function createHttpPostRequestRouterChain(): CommandRequestRouterChain
    {
        return new CommandRequestRouterChain();
    }

    public function createHttpCommandDispatcher(): CommandDispatcher
    {
        return new CommandDispatcher(
            $this->getMasterFactory(),
            $this->getApplicationController(),
            $this->getMasterFactory()->getHttpResponseLocator()
        );
    }

    public function getApplicationController(): ApplicationController
    {
        if ($this->applicationController === null) {
            $this->applicationController = $this->createApplicationController();
        }
        return $this->applicationController;
    }

    private function createApplicationController(): ApplicationController
    {
        return new ApplicationController($this->getMasterFactory());
    }


    //***************************************************
    // START Handler
    //***************************************************

    public function createHandlerDispatcher(): HandlerDispatcher
    {
        return new HandlerDispatcher();
    }

    public function getHandlerRouterChain(): HandlerRouterChainInterface
    {
        if ($this->handlerRouterChain === null) {
            $this->handlerRouterChain = $this->createHandlerRouterChain();
        }
        return $this->handlerRouterChain;
    }

    private function createHandlerRouterChain(): HandlerRouterChainInterface
    {
        return new HandlerRouterChain();
    }

    //***************************************************
    // START Router
    //***************************************************

    public function getUrlMatcher(): UrlMatcher
    {
        if ($this->urlMatcher === null) {
            $this->urlMatcher = $this->getMasterFactory()->createUrlMatcher();
        }
        return $this->urlMatcher;
    }

    public function createUrlMatcher(): UrlMatcher
    {
        return new UrlMatcher();
    }

    //***************************************************
    // START Session
    //***************************************************

    public function getSession(): Session
    {
        if ($this->session === null) {
            $this->session = $this->createSession();
        }
        return $this->session;
    }

    private function createSession(): Session
    {
        $session = new Session($this->createSessionStorage());
        $session->start();
        return $session;
    }

    private function createSessionStorage(): NativeSessionStorage
    {
        return new NativeSessionStorage($this->createSessionFileHandler());
    }

    private function createSessionFileHandler(): SessionFileHandler
    {
        return new SessionFileHandler();
    }

    //***************************************************
    // START Filesystem
    //***************************************************

    /**
     * @return File[]
     * @throws FilesystemException
     */
    public function getLogs(): array
    {
        return array(
            $this->getErrorLogfile(),
            $this->getCliLogfile(),
        );
    }

    /**
     * @throws FilesystemException
     */
    public function getErrorLogfile(): File
    {
        return new File($this->getLogDirectory()->getPath() . '/error.log', true);
    }

    /**
     * @throws FilesystemException
     */
    public function getCliLogfile(): File
    {
        return new File($this->getLogDirectory()->getPath() . '/cli.log', true);
    }

    /**
     * @throws FilesystemException
     */
    private function getLogDirectory(): Directory
    {
        return $this->getMasterFactory()->getDataDirectory()->change('logs');
    }

    /**
     * @throws FilesystemException
     */
    public function getRootDirectory(): Directory
    {
        return new Directory($this->getMasterFactory()->getConfiguration()->getBasePath());
    }

    /**
     * @throws FilesystemException
     */
    public function getDataDirectory(): Directory
    {
        return $this->getRootDirectory()->change('files');
    }

    public function createBootstrapFormPopulation(): BootstrapFormPopulation
    {
        return new BootstrapFormPopulation(false);
    }

    /**
     * @throws FilesystemException
     */
    public function getTranslator(): Translator
    {
        if ($this->translator === null) {
            $this->translator = $this->getMasterFactory()->createTranslator();
        }
        return $this->translator;
    }

    /**
     * @throws FilesystemException
     */
    public function createTranslator(): Translator
    {
        // Fallback smarty directory, used if no separate directories are defined
        $translationDirectoryPath = $this->getConfigurationValue('translation_dir');
        if ($translationDirectoryPath === null) {
            $translationDirectoryPath = $this->getBasePath() . '/data/translation';
        }
        $translationDirectory = new Directory($translationDirectoryPath, true);
        return new Translator(
            $translationDirectory,
            new ArrayCache(),
            $this->getConfigurationValue('default_language')
        );
    }

    /**
     * @return IntlFormatterInterface
     */
    public function getIntlFormatter()
    {
        if ($this->intlFormatter === null) {
            $this->intlFormatter = $this->getMasterFactory()->createIntlFormatter();
        }
        return $this->intlFormatter;
    }

    /**
     * @return IntlFormatterInterface
     */
    public function createIntlFormatter()
    {
        return new IntlFormatter($this->getMasterFactory()->createIntlSystemSettings());
    }

    /**
     * Creates the settings from the config values. Will use system defaults
     * from php config, if no values provided.
     */
    public function createIntlSystemSettings(): IntlSettings
    {
        $timeZoneString = $this->getConfigurationValue('system_time_zone');
        $locale = $this->getConfigurationValue('system_locale');
        $dateFormat = $this->getConfigurationValue('system_date_format');
        $timeFormat = $this->getConfigurationValue('system_time_format');
        $dateTimeFormat = $this->getConfigurationValue('system_date_time_format');

        if (empty($timeZoneString)) {
            $timeZoneString = date_default_timezone_get();
        }
        if (empty($locale)) {
            $locale = locale_get_default();
        }
        return new IntlSettings(
            new DateTimeZone($timeZoneString),
            $locale,
            $dateFormat,
            $timeFormat,
            $dateTimeFormat
        );
    }

    public function getIntlDateTimeConverter(): IntlDateTimeConverter
    {
        if ($this->intlDateTimeConverter === null) {
            $this->intlDateTimeConverter = $this->getMasterFactory()->createIntlDateTimeConverter();
        }
        return $this->intlDateTimeConverter;
    }

    /**
     * Default method will use system settings as user settings.
     * Should be replaced with a method from the application factory
     * that loads the real user settings.
     */
    public function createIntlDateTimeConverter(): IntlDateTimeConverter
    {
        return new IntlDateTimeConverter(
            $this->createIntlSystemSettings(),
            $this->createIntlSystemSettings()
        );
    }

    /**
     * @throws FilesystemException
     */
    public function createSmarty(): Smarty
    {
        $smarty = new Smarty();
        // Fallback smarty directory, used if no separate directories are defined
        $smartyDirectoryPath = $this->getConfigurationValue('smarty_dir');
        if ($smartyDirectoryPath === null) {
            $smartyDirectoryPath = $this->getBasePath() . '/data/smarty';
        }
        $smartyDirectory = new Directory($smartyDirectoryPath, true);
        // Cache directory
        $compileDirectoryPath = $this->getConfigurationValue('smarty_compile_dir');
        if ($compileDirectoryPath === null) {
            $compileDirectoryPath = $smartyDirectory->change('compile', true)->getPath();
        }
        // Compile directory
        $smarty->setCompileDir($compileDirectoryPath);
        $cacheDirectoryPath = $this->getConfigurationValue('smarty_cache_dir');
        if ($cacheDirectoryPath === null) {
            $cacheDirectoryPath = $smartyDirectory->change('cache', true)->getPath();
        }
        $smarty->setCacheDir($cacheDirectoryPath);
        return $smarty;
    }

    /**
     * @return TemplateEngineInterface
     * @throws FilesystemException
     */
    public function createSmartyTemplateEngine()
    {
        $bundlesPath = $this->getBasePath() . '/bundles';
        if ($this->getConfigurationValue('bundle_dir')) {
            $bundlesPath = $this->getConfigurationValue('bundle_dir');
        }
        $bundlesDirectory = new Directory($bundlesPath);
        $templatePathes = array();
        foreach ($bundlesDirectory->getDirectories() as $bundleDirectory) {
            $bundleTemplateDirectory = $bundleDirectory->change('Template', false);
            if ($bundleTemplateDirectory->exists()) {
                $templatePathes[] = $bundleTemplateDirectory->getPath();
            }
        }

        return new SmartyTemplateEngine(
            $this->getMasterFactory()->createSmarty(),
            $templatePathes,
            $this->getMasterFactory()->getTranslator(),
            $this->getMasterFactory()->getIntlFormatter(),
            $this->getMasterFactory()->getIntlDateTimeConverter()
        );
    }

    public function createJsonEncodingService(): JsonEncodingServiceInterface
    {
        return new JsonEncodingService();
    }

    private function createRouteFactory(): RouteFactoryInterface
    {
        return new RouteFactory();
    }
}
