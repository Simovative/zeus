<?php
namespace Simovative\Zeus\Translator;

use Simovative\Zeus\Filesystem\File;
use Simovative\Zeus\Cache\CacheInterface;
use Simovative\Zeus\Filesystem\Directory;

/**
 * @author mnoerenberg
 */
class Translator implements TranslatorInterface {
	
	/**
	 * @var Directory
	 */
	private $translationDirectory;
	
	/**
	 * @var CacheInterface
	 */
	private $cache;
	
	/**
	 * @var string
	 */
	private $defaultLanguage;
	
	/**
	 * @author mnoerenberg
	 * @param Directory $translationDirectory
	 * @param CacheInterface $cache
	 * @param string $defaultLanguage
	 */
	public function __construct(Directory $translationDirectory, CacheInterface $cache, $defaultLanguage) {
		$this->translationDirectory = $translationDirectory;
		$this->cache = $cache;
		$this->defaultLanguage = $defaultLanguage;
	}
	
	/**
	 * Return key from cache.
	 * 
	 * @author mnoerenberg
	 * @param string $sectionKey
	 * @param string $valueKey
	 * @return string|boolean
	 */
	private function getFromCacheByKeys($sectionKey, $valueKey) {
		if ($this->cache->exists($sectionKey)) {
			$sectionValues = $this->cache->get($sectionKey);
			if (array_key_exists($valueKey, $sectionValues)) {
				return $sectionValues[$valueKey];
			}
		}
		return false;
	}
	
	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function translate($textKey, $language = null) {
		if (empty($language)) {
			$language = $this->defaultLanguage;
		}
		
		if (strpos($textKey, ':') === false) {
			return $textKey;
		}
		
		$exploded = explode(':', strtolower($textKey));
		$sectionKey = $language . '_' . $exploded[0];
		$valueKey = $exploded[1];
		
		// get value from cache.
		$value = $this->getFromCacheByKeys($sectionKey, $valueKey);
		if ($value != false) {
			return $value;
		}
		
		// language was loaded already.
		if ($this->cache->exists($language)) {
			return $textKey;
		}
		
		// load translations from file to cache.
		$translations = $this->loadTranslationsByLanguage($language);
		$this->cacheTranslations($translations, $language);
		
		// find text key.
		$value = $this->getFromCacheByKeys($sectionKey, $valueKey);
		if ($value != false) {
			return $value;
		}
		
		return $textKey;
	}
	
	/**
	 * Cache translations.
	 * 
	 * @author mnoerenberg
	 * @param string[] $translations
	 * @param string $language
	 * @return void
	 */
	private function cacheTranslations(array $translations, $language) {
		$this->cache->set($language, '');
		foreach ($translations as $translationKey => $translationValues) {
			$this->cache->set(strtolower($language . '_' . $translationKey), $translationValues);
		}
	}
	
	/**
	 * Load the translations from the translation file of the language.
	 *
	 * @author mnoerenberg
	 * @param string $language
	 * @return string[]
	 */
	private function loadTranslationsByLanguage($language) {
		$translationFile = new File($this->translationDirectory . '/' . $language . '.ini', true);
		if (! $translationFile->exists()) {
			return array();
		}
		
		return parse_ini_file($translationFile->getPath(), true);
	}
}
