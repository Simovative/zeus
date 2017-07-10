<?php
namespace Simovative\Zeus\Intl;

/**
 * Settings for the intl formatter.
 *
 * @author Benedikt Schaller
 */
class IntlSettings {
	/**
	 * @var \DateTimeZone
	 */
	private $dateTimeZone;
	/**
	 * @var string
	 */
	private $locale;
	/**
	 * @var null|string
	 */
	private $dateFormat;
	/**
	 * @var null|string
	 */
	private $timeFormat;
	/**
	 * @var null|string
	 */
	private $dateTimeFormat;
	
	/**
	 * @author Benedikt Schaller
	 * @param \DateTimeZone $dateTimeZone
	 * @param string $locale
	 * @param string|null $dateFormat
	 * @param string|null $timeFormat
	 * @param string|null $dateTimeFormat
	 */
	public function __construct(\DateTimeZone $dateTimeZone, $locale, $dateFormat = null, $timeFormat = null, $dateTimeFormat = null) {
		$this->dateTimeZone = $dateTimeZone;
		$this->locale = $locale;
		$this->dateFormat = $dateFormat;
		$this->timeFormat = $timeFormat;
		if ($dateTimeFormat === null && $dateFormat !== null && $timeFormat !== null) {
			$dateTimeFormat = $dateFormat . ' ' . $timeFormat;
		}
		$this->dateTimeFormat = $dateTimeFormat;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return \DateTimeZone
	 */
	public function getDateTimeZone() {
		return $this->dateTimeZone;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return string
	 */
	public function getLocale() {
		return $this->locale;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return null|string
	 */
	public function getDateFormat() {
		return $this->dateFormat;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return null|string
	 */
	public function getTimeFormat() {
		return $this->timeFormat;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return null|string
	 */
	public function getDateTimeFormat() {
		return $this->dateTimeFormat;
	}
}