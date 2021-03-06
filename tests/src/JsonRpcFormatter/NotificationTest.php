<?php

namespace JsonRpcFormatter;

require_once dirname(__FILE__) . '/../../../src/JsonRpcFormatter/Notification.php';

/**
 * Test class for Notification.
 * Generated by PHPUnit on 2012-03-27 at 22:43:16.
 */
class NotificationTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var Notification
	 */
	protected $object;

	/**
	 * Test the method is not set yet
	 *
	 * @expectedException JsonRpcFormatter\Exception\LogicException
	 */
	public function testGetMethodFail()
	{
		$validator = $this->getMock('JsonRpcFormatter\\Validator\\ArgumentValidator');
		$this->object = new Notification($validator);
		$this->object->getMethod();
	}

	public function testMethod()
	{
		$validator = $this->getMock('JsonRpcFormatter\\Validator\\ArgumentValidator');
		$validator->expects($this->once())
				->method('isValidMethod')
				->will($this->returnValue(true));
		$this->object = new Notification($validator);
		$string = "asdasd";
		$setterReturn = $this->object->setMethod($string);
		$this->assertInstanceOf('\JsonRpcFormatter\Notification', $setterReturn);
		$this->assertEquals($string, $this->object->getMethod());
		
	}

	/**
	 * @expectedException JsonRpcFormatter\Exception\UnexpectedValueException
	 */
	public function testSetMethodFail()
	{
		$validator = $this->getMock('JsonRpcFormatter\\Validator\\ArgumentValidator');
		$validator->expects($this->once())
				->method('isValidMethod')
				->will($this->returnValue(false));
		$this->object = new Notification($validator);
		$string = "asdasd";
		$this->object->setMethod($string);
	}



	/**
	 * @expectedException JsonRpcFormatter\Exception\LogicException
	 */
	public function testGetParamsFail()
	{
		$validator = $this->getMock('JsonRpcFormatter\\Validator\\ArgumentValidator');
		$this->object = new Notification($validator);
		$this->object->getParams();
	}

	/**
	 * @expectedException JsonRpcFormatter\Exception\UnexpectedValueException
	 */
	public function testSetParamsFail()
	{
		$validator = $this->getMock('JsonRpcFormatter\\Validator\\ArgumentValidator');
		$validator->expects($this->once())
				->method('isValidParamsArgument')
				->will($this->returnValue(false));
		$this->object = new Notification($validator);
		$argument = "asdasd";
		$this->object->setParams($argument);
	}

	public function testParams()
	{
		$validator = $this->getMock('JsonRpcFormatter\\Validator\\ArgumentValidator');
		$validator->expects($this->once())
				->method('isValidParamsArgument')
				->will($this->returnValue(true));
		$this->object = new Notification($validator);
		$string = "asdasd";
		$setterReturn = $this->object->setParams($string);
		$this->assertInstanceOf('\JsonRpcFormatter\Notification', $setterReturn);
		$this->assertEquals($string, $this->object->getParams());
	}

	public function testGetVersion()
	{
		$validator = $this->getMock('JsonRpcFormatter\\Validator\\ArgumentValidator');
		$this->object = new Notification($validator);
		$this->assertEquals("2.0", $this->object->getVersion());
	}

	/**
	 * @expectedException JsonRpcFormatter\Exception\LogicException
	 */
	public function testJsonSerializeNoMethod()
	{
		$validator = $this->getMock('JsonRpcFormatter\\Validator\\ArgumentValidator');
		$this->object = new Notification($validator);

		$this->object->jsonSerialize();
	}

	public function testJsonSerializeFull()
	{
		$validator = $this->getMock('JsonRpcFormatter\\Validator\\ArgumentValidator');
		$validator->expects($this->once())
				->method('isValidMethod')
				->will($this->returnValue(true));
		$validator->expects($this->once())
				->method('isValidParamsArgument')
				->will($this->returnValue(true));

		$this->object = new Notification($validator);

		$method = "Method-test";
		$this->object->setMethod($method);

		$params = array("testing");
		$this->object->setParams($params);

		$json = $this->object->jsonSerialize();
		$this->assertInstanceOf('\stdClass', $json);
		
		$this->assertObjectHasAttribute('jsonrpc', $json);
		$this->assertAttributeEquals('2.0', 'jsonrpc', $json);

		$this->assertObjectHasAttribute('method', $json);
		$this->assertAttributeEquals($method, 'method', $json);
		
		$this->assertObjectHasAttribute('params', $json);
		$this->assertAttributeEquals($params, 'params', $json);

		$this->assertCount(3, (array) $json);
	}
}


