<?php
namespace Simovative\Zeus\Intl;

/**
 * @author mnoerenberg
 */
class IntlFormatter implements IntlFormatterInterface {
	
	/**
	 * @var IntlSettings
	 */
	private $systemSettings;
	
	/**
	 * @author mnoerenberg
	 * @param IntlSettings $systemSettings
	 * @internal param \DateTimeZone $serverTimeZone
	 */
	public function __construct(IntlSettings $systemSettings) {
		$this->systemSettings = $systemSettings;
	}
	
	/**
	 * @author mnoerenberg
	 * @inheritdoc
	 */
	public function formatDate(\DateTime $dateTime = null, $emptyValue = '') {
		if ($dateTime === null) {
			return $emptyValue;
		}
		return $dateTime->format($this->systemSettings->getDateFormat());
	}
	
	/**
	 * @author mnoerenberg
	 * @inheritdoc
	 */
	public function formatDateTime(\DateTime $dateTime = null, $emptyValue = '') {
		if ($dateTime === null) {
			return $emptyValue;
		}
		return $dateTime->format($this->systemSettings->getDateTimeFormat());
	}
	
	/**
	 * @author mnoerenberg
	 * @inheritdoc
	 */
	public function formatTime(\DateTime $dateTime = null, $emptyValue = '') {
		if ($dateTime === null) {
			return $emptyValue;
		}
		return $dateTime->format($this->systemSettings->getTimeFormat());
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritdoc
	 */
	public function formatNumber($number, $precision = 2, $emptyValue = '') {
		if (! is_numeric($number)) {
			return $emptyValue;
		}
		$formatter = new \NumberFormatter($this->systemSettings->getLocale(), \NumberFormatter::DEFAULT_STYLE);
		$formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $precision);
		$formatter->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, $precision);
		return $formatter->format($number);
	}
	
	/**
	 * Format a date using a custom format string.
	 *
	 * @author Benedikt Schaller
	 * @param \DateTime|null $dateTime
	 * @param string $customFormat
	 * @param string $emptyValue
	 * @return string
	 */
	public function customDateFormat(\DateTime $dateTime = null, $customFormat = '', $emptyValue = '') {
		if ($dateTime === null) {
			return $emptyValue;
		}
		return $dateTime->format($customFormat);
	}
}
