<?php

namespace JsonRpcFormatter;

require_once dirname(__FILE__) . '/../../../src/JsonRpcFormatter/Request.php';

/**
 * Test class for Request.
 * Generated by PHPUnit on 2012-03-27 at 22:43:16.
 */
class RequestTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var Request
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
		$this->object = new Request($validator);
		$this->object->getMethod();
	}

	public function testMethod()
	{
		$validator = $this->getMock('JsonRpcFormatter\\Validator\\ArgumentValidator');
		$validator->expects($this->once())
				->method('isValidMethod')
				->will($this->returnValue(true));
		$this->object = new Request($validator);
		$string = "asdasd";
		$setterReturn = $this->object->setMethod($string);
		$this->assertInstanceOf('\JsonRpcFormatter\Request', $setterReturn);
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
		$this->object = new Request($validator);
		$string = "ID-test";
		$this->object->setMethod($string);
	}

	/**
	 * Test the method is not set yet
	 *
	 * @expectedException JsonRpcFormatter\Exception\LogicException
	 */
	public function testGetIdFail()
	{
		$validator = $this->getMock('JsonRpcFormatter\\Validator\\ArgumentValidator');
		$this->object = new Request($validator);
		$this->object->getId();
	}

	public function testId()
	{
		$validator = $this->getMock('JsonRpcFormatter\\Validator\\ArgumentValidator');
		$validator->expects($this->once())
				->method('isValidId')
				->will($this->returnValue(true));
		$this->object = new Request($validator);
		$string = "ID-test";
		$setterReturn = $this->object->setId($string);
		$this->assertInstanceOf('\JsonRpcFormatter\Request', $setterReturn);
		$this->assertEquals($string, $this->object->getId());

	}

	/**
	 * @expectedException JsonRpcFormatter\Exception\UnexpectedValueException
	 */
	public function testSetIdFail()
	{
		$validator = $this->getMock('JsonRpcFormatter\\Validator\\ArgumentValidator');
		$validator->expects($this->once())
				->method('isValidId')
				->will($this->returnValue(false));
		$this->object = new Request($validator);
		$string = "ID-test";
		$this->object->setId($string);
	}



	/**
	 * @expectedException JsonRpcFormatter\Exception\LogicException
	 */
	public function testGetParamsFail()
	{
		$validator = $this->getMock('JsonRpcFormatter\\Validator\\ArgumentValidator');
		$this->object = new Request($validator);
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
		$this->object = new Request($validator);
		$argument = "asdasd";
		$this->object->setParams($argument);
	}

	public function testParams()
	{
		$validator = $this->getMock('JsonRpcFormatter\\Validator\\ArgumentValidator');
		$validator->expects($this->once())
				->method('isValidParamsArgument')
				->will($this->returnValue(true));
		$this->object = new Request($validator);
		$string = "asdasd";
		$setterReturn = $this->object->setParams($string);
		$this->assertInstanceOf('\JsonRpcFormatter\Request', $setterReturn);
		$this->assertEquals($string, $this->object->getParams());
	}

	public function testGetVersion()
	{
		$validator = $this->getMock('JsonRpcFormatter\\Validator\\ArgumentValidator');
		$this->object = new Request($validator);
		$this->assertEquals("2.0", $this->object->getVersion());
	}

	/**
	 * @expectedException JsonRpcFormatter\Exception\LogicException
	 */
	public function testJsonSerializeNoMethod()
	{
		$validator = $this->getMock('JsonRpcFormatter\\Validator\\ArgumentValidator');
		$this->object = new Request($validator);

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
		$validator->expects($this->once())
				->method('isValidId')
				->will($this->returnValue(true));

		$this->object = new Request($validator);

		$id = "Id-test";
		$this->object->setId($id);

		$method = "Method-test";
		$this->object->setMethod($method);

		$params = array("testing");
		$this->object->setParams($params);

		$json = $this->object->jsonSerialize();
		$this->assertInstanceOf('\stdClass', $json);

		$this->assertObjectHasAttribute('jsonrpc', $json);
		$this->assertAttributeEquals('2.0', 'jsonrpc', $json);

		$this->assertObjectHasAttribute('id', $json);
		$this->assertAttributeEquals($id, 'id', $json);

		$this->assertObjectHasAttribute('method', $json);
		$this->assertAttributeEquals($method, 'method', $json);

		$this->assertObjectHasAttribute('params', $json);
		$this->assertAttributeEquals($params, 'params', $json);

		$this->assertCount(4, (array) $json);
	}
}


