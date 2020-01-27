<?php
namespace Simovative\Test\Integration\TestBundle;

use Simovative\Zeus\Session\SessionInterface;
use Simovative\Zeus\State\ApplicationStateInterface;

/**
 * @author Benedikt Schaller
 */
class ApplicationState implements ApplicationStateInterface {
	
	const KEY_USER_ID = 'user_id';
	const KEY_USERNAME = 'username';
	
	/**
	 * @var SessionInterface
	 */
	private $session;
	
	/**
	 * @author Benedikt Schaller
	 * @param SessionInterface $session
	 */
	public function __construct(SessionInterface $session) {
		$this->session = $session;
	}
	
	/**
	 * Persists the application state.
	 *
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function commit() {
		// Nothing to do, because the session will auto save on request end
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return int|null
	 */
	public function getUserId() {
		return $this->session->get(self::KEY_USER_ID);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return string|null
	 */
	public function getUsername() {
		return $this->session->get(self::KEY_USERNAME);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @param int $userId
	 * @param string $username
	 * @return void
	 */
	public function setUser($userId, $username) {
		$this->session->set(self::KEY_USER_ID, $userId);
		$this->session->set(self::KEY_USERNAME, $username);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return bool
	 */
	public function isLoggedIn() {
		return ($this->getUserId() > 0);
	}
	
	/**
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function logout() {
		$this->session->remove(self::KEY_USER_ID);
		$this->session->remove(self::KEY_USERNAME);
	}
}