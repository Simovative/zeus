<?php
namespace Simovative\Zeus\Intl;

/**
 * Class to convert date times from user to system time and back.
 *
 * @author Benedikt Schaller
 */
class IntlDateTimeConverter {
	
	/**
	 * @var IntlSettings
	 */
	private $systemSettings;
	/**
	 * @var IntlSettings
	 */
	private $userSettings;
	
	/**
	 * @author Benedikt Schaller
	 * @param IntlSettings $systemSettings
	 * @param IntlSettings $userSettings
	 */
	public function __construct(IntlSettings $systemSettings, IntlSettings $userSettings) {
		$this->systemSettings = $systemSettings;
		$this->userSettings = $userSettings;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @param \DateTime|null $systemDateTime
	 * @return \DateTime|null
	 */
	public function convertSystemToUserTime(\DateTime $systemDateTime = null) {
		if ($systemDateTime === null) {
			return $systemDateTime;
		}
		$userDateTime = clone $systemDateTime;
		$userDateTime->setTimezone($this->userSettings->getDateTimeZone());
		return $userDateTime;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @param \DateTime|null $userDateTime
	 * @return \DateTime|null
	 */
	public function convertUserToSystemTime(\DateTime $userDateTime = null) {
		if ($userDateTime === null) {
			return $userDateTime;
		}
		$systemDateTime = clone $userDateTime;
		$systemDateTime->setTimezone($this->systemSettings->getDateTimeZone());
		return $systemDateTime;
	}
}