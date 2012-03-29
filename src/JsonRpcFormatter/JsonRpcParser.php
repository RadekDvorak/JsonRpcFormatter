<?php

namespace JsonRpcFormatter;

/**
 * JSON RPC 2.0 parser
 *
 * @author Radek Dvořák <radek.dvorak@gmail.com>
 */
class JsonRpcParser implements JsonRpcParserInterface
{

	/** @var \stdClass RPC object */
	private $object;
	private $lastError = "";
	private $version = "2.0";

	/**
	 * Cosntructor
	 *
	 * @param stdClass $object
	 * @throws \InvalidArgumentException
	 */
	function __construct($object)
	{

		if (!$object instanceof \stdClass)
		{
			$message = "Already decoded json as a \\stdClass instance was expected.";
			throw new \InvalidArgumentException($message);
		}
		$this->object = $object;
	}

	public function getLastError()
	{
		return $this->lastError;
	}

	public function isJsonRpc()
	{
		$isJson = isset($this->object->jsonrpc);
		return $isJson;
	}

	public function hasCorrectVersion()
	{
		$status = ($this->object->jsonrpc === $this->version);

		if (!$status)
		{
			$message = sprintf("Invalid json-rpc version: '%s' (%s) was expected, '%s' (%s) was found.",
					$this->version,
					gettype($this->version),
					$this->object->jsonrpc,
					gettype($this->object->jsonrpc)
			);
			$this->lastError = $message;
		}
		return $status;
	}

	public function isResponse()
	{
		return isset($this->object->id) && (isset($this->object->result) || isset($this->object->error));
	}

	public function isErrorObject($errorObject)
	{
		$isStdClass = $errorObject instanceof \stdClass;
		if (!$isStdClass)
		{
			$message = "Invalid error object, \\stdClass instance was expected.";
			$this->lastError = $message;
			return false;
		}

		if (!isset($errorObject->code))
		{
			$message = "Failed to find a code of the error object.";
			$this->lastError = $message;
			return false;
		}

		if (!isset($errorObject->message))
		{
			$message = "Failed to find a message of the error object.";
			$this->lastError = $message;
			return false;
		}

		return true;
	}

	public function tryBuildRequest(Validator\ArgumentValidatorInterface $validator)
	{
		$request = new Request($validator);
		$request->setId($this->object->id);
		$request->setMethod($this->object->method);

		if (isset($this->object->params))
		{
			$request->setParams($this->object->params);
		}
		return $request;
	}

	public function tryBuildNotification(Validator\ArgumentValidatorInterface $validator)
	{
		$notification = new Notification($validator);
		$notification->setMethod($this->object->method);

		if (isset($this->object->params))
		{
			$notification->setParams($this->object->params);
		}
		return $notification;
	}

	public function tryBuildResponse(Validator\ArgumentValidatorInterface $validator)
	{
		$response = new Response($validator);
		$response->setMethod($this->object->method);

		if (isset($this->object->result))
		{
			$response->setResult($this->object->result);
		}
		else
		{
			if ($this->isErrorObject($this->object->error))
			{
				throw new \InvalidArgumentException($this->getLastError());
			}

			$error = $this->tryBuildErrorObject($validator, $this->object->error);

			$response->setError($error);
		}
		return $response;
	}

	protected function tryBuildErrorObject(Validator\ArgumentValidatorInterface $validator, \stdClass $errorObject)
	{
		$error = new Error($validator);
		$error->setCode($errorObject->code);
		$error->setMessage($errorObject->message);

		if (isset($errorObject->data))
		{
			$error->setData($errorObject->data);
		}
		return $error;
	}

}

