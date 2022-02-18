<?php
namespace Simovative\Zeus\State;

interface ApplicationStateInterface {
	public function commit(): void;
}
