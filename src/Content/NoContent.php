<?php
namespace Simovative\Zeus\Content;

/**
 * @author mnoerenberg
 */
class NoContent implements Content {
	
	/**
	 * @author mnoerenberg
	 * @return string
	 */
	public function render() {
		return '';
	}
}
