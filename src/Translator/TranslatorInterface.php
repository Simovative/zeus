<?php
namespace Simovative\Zeus\Translator;

/**
 * @author mnoerenberg
 */
interface TranslatorInterface {
	
	/**
	 * Returns the translated text by given key.
	 * 
	 * @author mnoerenberg
	 * @param string $textKey
	 * @param string $language - default: null
	 * @return string
	 */
	public function translate($textKey, $language = null);
}
