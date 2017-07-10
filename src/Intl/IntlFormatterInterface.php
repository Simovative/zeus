<?php
namespace Simovative\Zeus\Intl;

/**
 * Interface for a class that does internationalization for dates and numbers.
 *
 * @author Benedikt Schaller
 */
interface IntlFormatterInterface {
	
	/**
	 * @author Benedikt Schaller
	 * @param \DateTime|null $dateTime
	 * @param string $emptyValue Value to show on null.
	 * @return string
	 */
	public function formatDate(\DateTime $dateTime = null, $emptyValue = '');
	
	/**
	 * @author Benedikt Schaller
	 * @param \DateTime|null $dateTime
	 * @param string $emptyValue Value to show on null.
	 * @return string
	 */
	public function formatDateTime(\DateTime $dateTime = null, $emptyValue = '');
	
	/**
	 * @author Benedikt Schaller
	 * @param \DateTime|null $dateTime
	 * @param string $emptyValue Value to show on null.
	 * @return string
	 */
	public function formatTime(\DateTime $dateTime = null, $emptyValue = '');
	
	/**
	 * @author Benedikt Schaller
	 * @param int|float|double|null $number
	 * @param int $precision
	 * @param string $emptyValue Value to show on null.
	 * @return string
	 */
	public function formatNumber($number, $precision = 2, $emptyValue = '');
	
	/**
	 * Format a date using a custom format string.
	 *
	 * @author Benedikt Schaller
	 * @param \DateTime|null $dateTime
	 * @param string $customFormat
	 * @param string $emptyValue
	 * @return string
	 */
	public function customDateFormat(\DateTime $dateTime = null, $customFormat = '', $emptyValue = '');
}