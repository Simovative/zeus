<?php
namespace Simovative\Zeus\Dependency;

use DateTimeZone;
use Simovative\Zeus\Cache\ArrayCache;
use Simovative\Zeus\Command\ApplicationController;
use Simovative\Zeus\Command\CommandDispatcher;
use Simovative\Zeus\Command\CommandRequestRouterChain;
use Simovative\Zeus\Exception\ExceptionHandler;
use Simovative\Zeus\Exception\FilesystemException;
use Simovative\Zeus\Filesystem\Directory;
use Simovative\Zeus\Filesystem\File;
use Simovative\Zeus\Http\Get\HttpGetRequestDispatcher;
use Simovative\Zeus\Http\Get\HttpGetRequestRouterChain;
use Simovative\Zeus\Http\Json\JsonEncodingService;
use Simovative\Zeus\Http\Json\JsonEncodingServiceInterface;
use Simovative\Zeus\Http\Request\HttpRequestDispatcherInterface;
use Simovative\Zeus\Http\Request\HttpRequestDispatcherLocator;
use Simovative\Zeus\Http\Response\HttpResponseLocator;
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

/**
 * @author mnoerenberg
 */
class FrameworkFactory extends Factory {
	
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
	 * @author mnoerenberg
	 * @return HttpResponseLocator
	 */
	public function getHttpResponseLocator() {
		if ($this->httpResponseLocator === null) {
			$this->httpResponseLocator = $this->getMasterFactory()->createHttpResponseLocator();
		}
		return $this->httpResponseLocator;
	}
	
	/**
	 * @author mnoerenberg
	 * @return HttpResponseLocator
	 */
	public function createHttpResponseLocator() {
		return new HttpResponseLocator();
	}
	
	/**
	 * @author mnoerenberg
	 * @return HttpRequestDispatcherLocator
	 */
	public function createHttpRequestDispatcherLocator() {
		return new HttpRequestDispatcherLocator($this);
	}
	
	//***************************************************
	// START EXCEPTION
	//***************************************************
	
	/**
	 * @author mnoerenberg
	 * @param KernelInterface $kernel
	 * @return ExceptionHandler
	 */
	public function createExceptionHandler(KernelInterface $kernel) {
		return new ExceptionHandler($kernel);
	}
	
	//***************************************************
	// START Logger
	//***************************************************
	
	/**
	 * @author Benedikt Schaller
	 * @return SapiLogger
	 */
	public function createSapiLogger() {
		return new SapiLogger();
	}
	
	//***************************************************
	// START GET
	//***************************************************
	
	/**
	 * @author mnoerenberg
	 * @return HttpGetRequestRouterChain
	 */
	public function getHttpGetRequestRouterChain() {
		if ($this->getRequestRouterChain === null) {
			$this->getRequestRouterChain = $this->createHttpGetRequestRouterChain();
		}
		return $this->getRequestRouterChain;
	}
	
	/**
	 * @author mnoerenberg
	 * @return HttpGetRequestRouterChain
	 */
	private function createHttpGetRequestRouterChain() {
		return new HttpGetRequestRouterChain();
	}
	
	/**
	 * @author mnoerenberg
	 * @return HttpGetRequestDispatcher
	 */
	public function createHttpGetRequestDispatcher() {
		return new HttpGetRequestDispatcher(
			$this->getHttpGetRequestRouterChain()
		);
	}
	
	//***************************************************
	// START POST
	//***************************************************
	
	/**
	 * @author mnoerenberg
	 * @return CommandRequestRouterChain
	 */
	public function getCommandRequestRouterChain() {
		if ($this->commandRequestRouterChain === null) {
			$this->commandRequestRouterChain = $this->createHttpPostRequestRouterChain();
		}
		return $this->commandRequestRouterChain;
	}
	
	/**
	 * @author mnoerenberg
	 * @return CommandRequestRouterChain
	 */
	private function createHttpPostRequestRouterChain() {
		return new CommandRequestRouterChain();
	}
	
	/**
	 * @author mnoerenberg
	 * @return HttpRequestDispatcherInterface
	 */
	public function createHttpCommandDispatcher() {
		return new CommandDispatcher(
			$this->getCommandRequestRouterChain(),
			$this->getMasterFactory(),
			$this->getApplicationController()
		);
	}
	
	/**
	 * @author mnoerenberg
	 * @return ApplicationController
	 */
	public function getApplicationController() {
		if ($this->applicationController === null) {
			$this->applicationController = $this->createApplicationController();
		}
		return $this->applicationController;
	}
	
	/**
	 * @author mnoerenberg
	 * @return ApplicationController
	 */
	private function createApplicationController() {
		return new ApplicationController($this->getMasterFactory());
	}
	
	//***************************************************
	// START Router
	//***************************************************
	
	/**
	 * Returns a single instance of an url matcher.
	 *
	 * @author Benedikt Schaller
	 * @return UrlMatcherInterface
	 */
	public function getUrlMatcher() {
		if ($this->urlMatcher === null) {
			$this->urlMatcher = $this->getMasterFactory()->createUrlMatcher();
		}
		return $this->urlMatcher;
	}
	
	/**
	 * @author mnoerenberg
	 * @return UrlMatcherInterface
	 */
	public function createUrlMatcher() {
		return new UrlMatcher();
	}
	
	//***************************************************
	// START Session
	//***************************************************
	
	/**
	 * @author mnoerenberg
	 * @return SessionInterface
	 */
	public function getSession() {
		if ($this->session === null) {
			$this->session = $this->createSession();
		}
		return $this->session;
	}
	
	/**
	 * @author mnoerenberg
	 * @return SessionInterface
	 */
	private function createSession() {
		$session = new Session($this->createSessionStorage());
		$session->start();
		return $session;
	}
	
	/**
	 * @author mnoerenberg
	 * @return NativeSessionStorage
	 */
	private function createSessionStorage() {
		return new NativeSessionStorage($this->createSessionFileHandler());
	}
	
	/**
	 * @author mnoerenberg
	 * @return SessionFileHandler|NULL - returns NULL if PHP_VERSION < 5.4
	 */
	private function createSessionFileHandler() {
		if (PHP_VERSION_ID < 50400) {
			return null;
		}
		
		return new SessionFileHandler();
	}
	
	//***************************************************
	// START Filesystem
	//***************************************************
	
	/**
	 * @author mnoerenberg
	 * @return File[]
	 * @throws FilesystemException
	 */
	public function getLogs() {
		return array(
			$this->getErrorLogfile(),
			$this->getCliLogfile(),
		);
	}
	
	/**
	 * @author mnoerenberg
	 * @return File
	 * @throws FilesystemException
	 */
	public function getErrorLogfile() {
		return new File($this->getLogDirectory()->getPath() . '/error.log', true);
	}
	
	/**
	 * @author mnoerenberg
	 * @return File
	 * @throws FilesystemException
	 */
	public function getCliLogfile() {
		return new File($this->getLogDirectory()->getPath() . '/cli.log', true);
	}
	
	/**
	 * Returns the log directory.
	 *
	 * @author Benedikt Schaller
	 * @return Directory
	 * @throws FilesystemException
	 */
	private function getLogDirectory() {
		return $this->getMasterFactory()->getDataDirectory()->change('logs');
	}
	
	/**
	 * @author mnoerenberg
	 * @return Directory
	 * @throws FilesystemException
	 */
	public function getRootDirectory() {
		return new Directory($this->getMasterFactory()->getConfiguration()->getBasePath());
	}
	
	/**
	 * @author mnoerenberg
	 * @return Directory
	 * @throws FilesystemException
	 */
	public function getDataDirectory() {
		return $this->getRootDirectory()->change('files');
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return BootstrapFormPopulation
	 */
	public function createBootstrapFormPopulation() {
		return new BootstrapFormPopulation(false);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return Translator
	 * @throws FilesystemException
	 */
	public function getTranslator() {
		if ($this->translator === null) {
			$this->translator = $this->getMasterFactory()->createTranslator();
		}
		return $this->translator;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return Translator
	 * @throws FilesystemException
	 */
	public function createTranslator() {
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
	 * @author Benedikt Schaller
	 * @return IntlFormatterInterface
	 */
	public function getIntlFormatter() {
		if ($this->intlFormatter === null) {
			$this->intlFormatter = $this->getMasterFactory()->createIntlFormatter();
		}
		return $this->intlFormatter;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return IntlFormatterInterface
	 */
	public function createIntlFormatter() {
		return new IntlFormatter($this->getMasterFactory()->createIntlSystemSettings());
	}
	
	/**
	 * Creates the settings from the config values. Will use system defaults
	 * from php config, if no values provided.
	 *
	 * @author Benedikt Schaller
	 * @return IntlSettings
	 */
	public function createIntlSystemSettings() {
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
	
	/**
	 * @author Benedikt Schaller
	 * @return IntlDateTimeConverter
	 */
	public function getIntlDateTimeConverter() {
		if ($this->intlDateTimeConverter === null) {
			$this->intlDateTimeConverter = $this->getMasterFactory()->createIntlDateTimeConverter();
		}
		return $this->intlDateTimeConverter;
	}
	
	/**
	 * Default method will use system settings as user settings.
	 * Should be replaced with a method from the application factory
	 * that loads the real user settings.
	 *
	 * @author Benedikt Schaller
	 * @return IntlDateTimeConverter
	 */
	public function createIntlDateTimeConverter() {
		return new IntlDateTimeConverter(
			$this->createIntlSystemSettings(),
			$this->createIntlSystemSettings()
		);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return Smarty
	 * @throws FilesystemException
	 */
	public function createSmarty() {
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
	 * @author Benedikt Schaller
	 */
	public function createSmartyTemplateEngine() {
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
	
	/**
	 * @author Benedikt Schaller
	 * @return JsonEncodingServiceInterface
	 */
	public function createJsonEncodingService() : JsonEncodingServiceInterface {
		return new JsonEncodingService();
	}
}
