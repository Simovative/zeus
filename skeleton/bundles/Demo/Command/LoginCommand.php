<?php
namespace Simovative\Skeleton\Demo\Command;

use Simovative\Zeus\Command\CommandInterface;

/**
 * @author Benedikt Schaller
 */
class LoginCommand implements CommandInterface {
	
	/**
	 * @var string
	 */
	private $username;
	/**
	 * @var string
	 */
	private $password;
	
	/**
	 * @author Benedikt Schaller
	 * @param string $username
	 * @param string $password
	 */
	public function __construct($username, $password) {
		$this->username = $username;
		$this->password = $password;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return string
	 */
	public function getUsername() {
		return $this->username;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}
}