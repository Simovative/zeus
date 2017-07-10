<?php
namespace Simovative\Zeus\Command;

/**
 * Generic command response that indicates a failure.
 *
 * @author Benedikt Schaller
 */
class CommandFailureResponse implements CommandResponseInterface {
	
	/**
	 * @var mixed|null
	 */
	private $value;
	
	/**
	 * @author Benedikt Schaller
	 * @param null|mixed $value
	 */
	public function __construct($value = null) {
		$this->value = $value;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return mixed|null
	 */
	public function getValue() {
		return $this->value;
	}
}