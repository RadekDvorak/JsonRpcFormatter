<?php

namespace JsonRpcFormatter;

/**
 * Test class for Message.
 * 
 */
class MessageTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @covers JsonRpcFormatter\Message::getJsonEncodeOptions
	 * @covers JsonRpcFormatter\Message::setJsonEncodeOptions
	 */
	public function testJsonEncodeOptions()
	{
		$initialValue = 0;
		$newValue = 55;

		$mock = $this->getMockForAbstractClass('\JsonRpcFormatter\Message');
		$this->assertEquals($initialValue, $mock->getJsonEncodeOptions());
		$this->assertInstanceOf('\JsonRpcFormatter\Message', $mock->setJsonEncodeOptions($newValue));
		$this->assertEquals($newValue, $mock->getJsonEncodeOptions());
	}

	/**
	 * @todo Implement test__toString().
	 */
	public function test__toString()
	{
		$mock = $this->getMockForAbstractClass('\JsonRpcFormatter\Message');
		$mock->expects($this->once())
				->method('jsonSerialize')
				->with()
				->will($this->returnValue(new \stdClass));
		$this->assertEquals("{}", strval($mock));
	}

}
