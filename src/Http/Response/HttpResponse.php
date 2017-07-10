<?php
namespace Simovative\Zeus\Http\Response;

/**
 * @author mnoerenberg
 */
abstract class HttpResponse implements HttpResponseInterface {
	
	/**
	 * @var string[]
	 */
	protected $headers = array();
	
	/**
	 * @author mnoerenberg
	 * @param string $header
	 * @return void
	 */
	public function addHeader($header) {
		$this->headers[] = $header;
	}
	
	/**
	 * @author mnoerenberg
	 * @return void
	 */
	protected function setHeader() {
		foreach ($this->headers as $header) {
			header($header);
		}
	}

	/**
	 * @inheritdoc
	 * @author mnoerenberg
	 */
	public function send() {
		$this->setHeader();
		echo $this->getBody();
	}
	
	/**
	 * @author mnoerenberg
	 * @return string
	 */
	abstract protected function getBody();
}
