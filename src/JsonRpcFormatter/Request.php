<?php

namespace JsonRpcFormatter;

/**
 * Request object
 *
 * @author Radek Dvořák <radek.dvorak@gmail.com>
 */
class Request extends Message
{

	/** @var Validator\ArgumentValidatorInterface $validator Validator instance */
	private $validator;
	private $id;
	private $method;
	private $params = null;
	private $version = "2.0";

	/**
	 * Constructor
	 *
	 * @param Validator\ArgumentValidatorInterface $validator
	 */
	function __construct(Validator\ArgumentValidatorInterface $validator)
	{
		$this->validator = $validator;
	}

	/**
	 * Json serialization helper
	 *
	 * @see parent::jsonSerialize()
	 * @return \stdClass
	 */
	public function jsonSerialize()
	{
		$json = new \stdClass;
		$json->jsonrpc = $this->version;

		$json->id = $this->getId();
		$json->method = $this->getMethod();

		if (isset($this->params))
		{
			$json->params = $this->getParams();
		}

		return $json;
	}

	/**
	 * Returns the id member value
	 * @return mixed
	 * @throws \LogicException
	 */
	public function getId()
	{
		if (!isset($this->id))
		{
			throw new \LogicException("Id is not set yet.");
		}

		return $this->id;
	}

	/**
	 * Sets the id member value
	 *
	 * @param int|string $id
	 * @return \JsonRpcFormatter\Request
	 * @throws \UnexpectedValueException
	 */
	public function setId($id)
	{
		if (!$this->validator->isValidId($id))
		{
			$message = $this->validator->getLastErrorMessage();
			throw new \UnexpectedValueException($message);
		}

		$this->id = $id;
		return $this;
	}

	/**
	 * Returns the method member value
	 *
	 * @return string
	 * @throws \LogicException
	 */
	public function getMethod()
	{
		if (!isset($this->method))
		{
			throw new \LogicException("Method name is not set yet.");
		}

		return $this->method;
	}

	/**
	 * Sets the method member value
	 *
	 * Methods rpc.* are reserved.
	 *
	 * @param string $method
	 * @return \JsonRpcFormatter\Request
	 * @throws \UnexpectedValueException
	 */
	public function setMethod($method)
	{
		if (!$this->validator->isValidMethod($method))
		{
			$message = $this->validator->getLastErrorMessage();
			throw new \UnexpectedValueException($message);
		}

		$this->method = $method;
		return $this;
	}

	/**
	 * Returns the parameters.
	 *
	 * @return type
	 * @throws \LogicException Parameters are omitted.
	 */
	public function getParams()
	{
		if (!isset($this->params))
		{
			throw new \LogicException("Parameters are not set yet.");
		}

		return $this->params;
	}

	/**
	 * Sets the parameters.
	 *
	 * @param type $params
	 * @return \JsonRpcFormatter\Request
	 * @throws \UnexpectedValueException
	 */
	public function setParams($params)
	{
		if (!$this->validator->isValidParamsArgument($params))
		{
			$message = $this->validator->getLastErrorMessage();
			throw new \UnexpectedValueException($message);
		}

		$this->params = $params;
		return $this;
	}

	/**
	 * Returns method's rpc version
	 *
	 * @return string
	 */
	public function getVersion()
	{
		return $this->version;
	}

}
