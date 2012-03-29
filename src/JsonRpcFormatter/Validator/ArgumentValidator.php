<?php

namespace JsonRpcFormatter\Validator;

/**
 * Common validating class
 *
 * @author Radek Dvořák <radek.dvorak@gmail.com>
 */
class ArgumentValidator implements ArgumentValidatorInterface
{
	private $lastErrorMessage = "";

	public function isValidId($id)
	{
		$isAcceptable = is_null($id) || is_int($id) || is_string($id);
		if (!$isAcceptable)
		{
			$this->lastErrorMessage = sprintf("Id is expected to be an integer, a string, a float or null, got '%s' instead.", gettype($id));
			return false;
		}
		return true;
	}

	public function isValidParamsArgument($params)
	{
		if (!is_array($params) && !is_object($params))
		{
			$this->lastErrorMessage = sprintf("Params are expected to be a structured data type, got '%s' instead.", gettype($params));
			return false;
		}
		return true;
	}

	public function isValidMethod($method)
	{
		if (!is_string($method))
		{
			$this->lastErrorMessage = sprintf("Method is expected to be a string, got '%s' instead.", gettype($method));
			return false;
		}

		$reserved = (0 === mb_strpos($method, "rpc."));
		if ($reserved)
		{
			$this->lastErrorMessage = "Method names starting 'rpc.' are reserved.";
			return false;
		}
		return true;
	}

	public function isValidErrorMessage($errorMessage)
	{
		if (!is_string($errorMessage))
		{
			$this->lastErrorMessage = sprintf("Error message is expected to be a string, got '%s' instead.", gettype($errorMessage));
			return false;
		}
		return true;
	}

	public function isValidErrorCode($errorCode)
	{
		if (!is_int($errorCode))
		{
			$this->lastErrorMessage = sprintf("Error code is expected to be an integer, got '%s' instead.", gettype($errorCode));
			return false;
		}
		return true;
	}

	public function getLastErrorMessage()
	{
		return $this->lastErrorMessage;
	}

	protected function setLastErrorMessage($message)
	{
		$this->lastErrorMessage = $message;
	}

}

