<?php

namespace JsonRpcFormatter;

/**
 * Response object
 *
 * @author Radek Dvořák <radek.dvorak@gmail.com>
 */
class Response extends Message
{

	/** @var Validator\ArgumentValidatorInterface $validator Validator instance */
	private $validator;
	private $id;
	private $result;
	private $error;
	private $version = "2.0";

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

		if (!isset($this->error) && !isset($this->result))
		{
			$message = "Either a result or an error has to be set.";
			throw new \LogicException($message);
		}

		if (!isset($this->error))
		{
			$json->result = $this->getResult();
		}
		else
		{
			$json->error = $this->getError();
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
	 * Returns method's rpc version
	 *
	 * @return string
	 */
	public function getVersion()
	{
		return $this->version;
	}

	/**
	 * Returns the result member value
	 *
	 * @return mixed
	 * @throws \LogicException Result member is ommited.
	 */
	public function getResult()
	{
		if (!isset($this->result))
		{
			throw new \LogicException("Result is not set.");
		}

		return $this->result;
	}

	/**
	 * Sets the result member value. Unsets any error member.
	 *
	 * @param mixed $result
	 * @return \JsonRpcFormatter\Response
	 */
	public function setResult($result)
	{
		$this->result = $result;
		unset($this->error);
		return $this;
	}

	/**
	 * Returns the error member value
	 *
	 * @return Error
	 * @throws \LogicException Result member is ommited.
	 */
	public function getError()
	{
		if (!isset($this->error))
		{
			throw new \LogicException("Error is not set.");
		}

		return $this->error;
	}

	/**
	 * Sets the error member value. Unsets any result member.
	 *
	 * @param Error $result
	 * @return \JsonRpcFormatter\Response
	 */
	public function setError(Error $error)
	{
		$this->error = $error;
		unset($this->result);
		return $this;
	}

	/**
	 * Checks if the result subobject is present
	 *
	 * @return bool
	 */
	public function hasResult()
	{
		return isset($this->result);
	}

	/**
	 * Checks if the errror subobject is present
	 *
	 * @return bool
	 */
	public function hasError()
	{
		return isset($this->error);
	}
}
