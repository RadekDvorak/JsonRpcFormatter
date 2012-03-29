<?php

namespace JsonRpcFormatter;

/**
 * Error object
 *
 * @author Radek Dvořák <radek.dvorak@gmail.com>
 */
class Error extends Message
{

	/** @var Validator\ArgumentValidatorInterface $validator Validator instance */
	private $validator;
	private $code;
	private $message;
	private $data;

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

		$json->code = $this->getCode();
		$json->message = $this->getMessage();

		if (isset($this->data))
		{
			$json->data = $this->getData();
		}

		return $json;
	}

	/**
	 * Returns code member's value
	 *
	 * @return int
	 * @throws \LogicException
	 */
	public function getCode()
	{
		if (!isset($this->code))
		{
			throw new \LogicException("Code is not set yet.");
		}
		return $this->code;
	}

	/**
	 * Sets error code member value
	 *
	 * @param type $code
	 * @return \JsonRpcFormatter\Error
	 * @throws \UnexpectedValueException
	 */
	public function setCode($code)
	{
		if (!$this->validator->isValidErrorCode($code))
		{
			$message = $this->validator->getLastErrorMessage();
			throw new \UnexpectedValueException($message);
		}

		$this->code = $code;
		return $this;
	}

	/**
	 * Returns value of the message member
	 *
	 * @return type
	 * @throws \LogicException
	 */
	public function getMessage()
	{
		if (!isset($this->message))
		{
			throw new \LogicException("Message is not set yet.");
		}

		return $this->message;
	}

	/**
	 * Sets the message member
	 *
	 * @param string $message
	 * @return \JsonRpcFormatter\Error
	 * @throws \UnexpectedValueException
	 */
	public function setMessage($message)
	{
		if (!$this->validator->isValidErrorMessage($message))
		{
			$message = $this->validator->getLastErrorMessage();
			throw new \UnexpectedValueException($message);
		}

		$this->message = $message;
		return $this;
	}

	/**
	 * Returns the value of the data member
	 *
	 * @return mixed
	 * @throws \LogicException Data are omitted.
	 */
	public function getData()
	{
		if (!isset($this->data))
		{
			throw new \LogicException("Data are not set yet.");
		}

		return $this->data;
	}

	/**
	 * Sets data to the data member. May be omitted.
	 *
	 * @param mixed $data
	 * @return \JsonRpcFormatter\Error
	 */
	public function setData($data)
	{
		$this->data = $data;
		return $this;
	}

	/**
	 *  Unsets error object's data member
	 */
	public function unsetData()
	{
		unset($this->data);
	}

}
