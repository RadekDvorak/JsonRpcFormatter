<?php

namespace JsonRpcFormatter;

/**
 * Notification object
 *
 * @author Radek Dvořák <radek.dvorak@gmail.com>
 */
class Notification extends Message
{

	/** @var Validator\ArgumentValidatorInterface $validator Validator instance */
	private $validator;
	private $method;
	private $params;
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

		$json->method = $this->getMethod();

		if (isset($this->params))
		{
			$json->params = $this->getParams();
		}
		return $json;
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
			throw new \LogicException("Method is not set yet.");
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
