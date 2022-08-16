<?php
namespace Simovative\Zeus\Template;

use Simovative\Zeus\Intl\IntlDateTimeConverter;
use Simovative\Zeus\Intl\IntlFormatterInterface;
use Simovative\Zeus\Translator\TranslatorInterface;

/**
 * @author mnoerenberg
 */
class SmartyTemplateEngine implements TemplateEngineInterface {
	
	/**
	 * @var \Smarty
	 */
	private $smarty;
	/**
	 * @var TranslatorInterface|null
	 */
	private $translator;
	/**
	 * @var IntlFormatterInterface|null
	 */
	private $intlFormatter;
	/**
	 * @var null|IntlDateTimeConverter
	 */
	private $intlDateTimeConverter;
	
	/**
	 * @author mnoerenberg
	 * @param \Smarty $smarty
	 * @param array $templateDirectories
	 * @param TranslatorInterface|null $translator
	 * @param IntlFormatterInterface|null $intlFormatter
	 * @param IntlDateTimeConverter|null $intlDateTimeConverter
	 */
	public function __construct(
		\Smarty $smarty,
		array $templateDirectories,
		TranslatorInterface $translator = null,
		IntlFormatterInterface $intlFormatter = null,
		IntlDateTimeConverter $intlDateTimeConverter = null
	) {
		$this->smarty = $smarty;
		// template dirs
		foreach ($templateDirectories as $index => $path) {
			$this->smarty->addTemplateDir($path);
		}
		
		$this->setTranslator($translator);
		$this->setIntlFormatter($intlFormatter);
		$this->setIntlDateTimeConverter($intlDateTimeConverter);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @param TranslatorInterface|null $translator
	 * @return void
	 */
	private function setTranslator(TranslatorInterface $translator = null) {
		$this->translator = $translator;
		if ($translator === null) {
			$this->smarty->unregisterPlugin(\Smarty::PLUGIN_MODIFIER, 'translate');
		} else {
			$this->smarty->registerPlugin(\Smarty::PLUGIN_MODIFIER, 'translate', array($this->translator, 'translate'));
		}
	}
	
	/**
	 * @author Benedikt Schaller
	 * @param IntlFormatterInterface|null $intlFormatter
	 * @return void
	 */
	private function setIntlFormatter(IntlFormatterInterface $intlFormatter = null) {
		$this->intlFormatter = $intlFormatter;
		if ($intlFormatter === null) {
			$this->smarty->unregisterPlugin(\Smarty::PLUGIN_MODIFIER, 'date');
			$this->smarty->unregisterPlugin(\Smarty::PLUGIN_MODIFIER, 'datetime');
			$this->smarty->unregisterPlugin(\Smarty::PLUGIN_MODIFIER, 'time');
			$this->smarty->unregisterPlugin(\Smarty::PLUGIN_MODIFIER, 'number');
		} else {
			$this->smarty->registerPlugin(\Smarty::PLUGIN_MODIFIER, 'dateFormat', array($this->intlFormatter, 'customDateFormat'));
			$this->smarty->registerPlugin(\Smarty::PLUGIN_MODIFIER, 'date', array($this->intlFormatter, 'formatDate'));
			$this->smarty->registerPlugin(\Smarty::PLUGIN_MODIFIER, 'datetime', array($this->intlFormatter, 'formatDateTime'));
			$this->smarty->registerPlugin(\Smarty::PLUGIN_MODIFIER, 'time', array($this->intlFormatter, 'formatTime'));
			$this->smarty->registerPlugin(\Smarty::PLUGIN_MODIFIER, 'number', array($this->intlFormatter, 'formatNumber'));
		}
	}
	
	/**
	 * @author Benedikt Schaller
	 * @param IntlDateTimeConverter|null $intlDateTimeConverter
	 * @return void
	 */
	private function setIntlDateTimeConverter(IntlDateTimeConverter $intlDateTimeConverter = null) {
		$this->intlDateTimeConverter = $intlDateTimeConverter;
		if ($intlDateTimeConverter === null) {
			$this->smarty->unregisterPlugin(\Smarty::PLUGIN_MODIFIER, 'userTime');
		} else {
			$this->smarty->registerPlugin(\Smarty::PLUGIN_MODIFIER, 'userTime', array($this->intlDateTimeConverter, 'convertSystemToUserTime'));
		}
	}
	
	/**
	 * @author @author Benedikt Schaller
	 * @return \Smarty
	 */
	protected function getSmarty() {
		return $this->smarty;
	}
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function assignGlobal($placeholder, $value) {
		$this->smarty->assignGlobal($placeholder, $value);
	}
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function assign($placeholder, $value) {
		$this->smarty->assign($placeholder, $value);
	}
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function render($path, array $placeholderValues = array()) {
		foreach ($placeholderValues as $placeholder => $value) {
			$this->assign($placeholder, $value);
		}
		
		return $this->smarty->fetch($path, md5($path));
	}
	
	/**
	 * @author bschaller
	 * @param string $templatePath
	 * @return void
	 */
	public function addTemplatePath($templatePath) {
		$this->smarty->addTemplateDir($templatePath);
	}
}
