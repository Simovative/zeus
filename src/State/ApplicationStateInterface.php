<?php
namespace Simovative\Zeus\State;

/**
 * @author Benedikt Schaller
 */
interface ApplicationStateInterface {

	/**
	 * Persists the application state.
	 *
	 * @author Benedikt Schaller
	 * @return void
	 */
	public function commit();
}