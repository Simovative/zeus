<?php
namespace Simovative\AC5\Framework\Test\Http;

use Simovative\Framework\Http\Url;
/**
 * Configuration test case.
 * 
 * @author tpawlow
 * @covers \Simovative\Framework\Http\Url
 */
class UrlTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Tests toString method.
	 * 
	 * @author tpawlow
	 */
	public function testToString() {
		$urlString = 'https://framework.local/path1/path2';
		$paramString = urlencode('EnthältÜmläüteUndSonderzeichen!"§$%&/())=?`');
		$paramStringEncoded = urlencode($paramString);
		$url = new Url($urlString, array(
			'paramInteger' => 1,
			'paramString' => $paramString
		));
		$this->assertEquals($url->__toString(), "$urlString?paramInteger=1&paramString=$paramStringEncoded");
	}
}