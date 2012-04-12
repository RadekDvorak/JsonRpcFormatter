<?php

namespace JsonRpcFormatter\Parser;

use JsonRpcFormatter\Validator\ArgumentValidatorInterface;
use JsonRpcFormatter\Notification;
use JsonRpcFormatter\Request;
use JsonRpcFormatter\Response;
use JsonRpcFormatter\Error;
use JsonRpcFormatter\Exception\ParseException;
use JsonRpcFormatter\Exception\InvalidArgumentException;

/**
 * JsonRpc 2.0 parser class
 *
 * @see http://jsonrpc.org/specification
 * @author Radek Dvořák <radek.dvorak@gmail.com>
 */
class Parser implements ParserInterface
{
	/** @var ArgumentValidatorInterface Validator for object construction */
	private $validator;

	/**
	 * Constructor
	 *
	 * @param ArgumentValidatorInterface $validator Validates parameters during tpc object construction.
	 */
	function __construct(ArgumentValidatorInterface $validator)
	{
		$this->validator = $validator;
	}

	/**
	 * Parses data into rpc message
	 *
	 * @param string|\stdClass $json Rpc representation
	 * @return \JsonRpcFormatter\Message
	 */
	public function parseJsonRpc2Message($json)
	{
		$json = $this->parseJsonString($json);
		$this->assertJsonRpc($json);

		try
		{
			return $this->parseJsonRpc2Request($json);
		}
		catch (ParseException $e)
		{
			if (ParseException::INVALID == $e->getCode())
			{
				throw $e;
			}
		}

		try
		{
			return $this->parseJsonRpc2Notification($json);
		}
		catch (ParseException $e)
		{
			if (ParseException::INVALID == $e->getCode())
			{
				throw $e;
			}
		}

		try
		{
			return $this->parseJsonRpc2Response($json);
		}
		catch (ParseException $e)
		{
			if (ParseException::INVALID == $e->getCode())
			{
				throw $e;
			}
		}

		$message = "Failed to parse the message.";
		throw new ParseException($message, ParseException::UNPARSABLE);
	}

	/**
	 * Parses data into rpc request
	 *
	 * @param string|\stdClass $json Rpc representation
	 * @return Request
	 */
	public function parseJsonRpc2Request($json)
	{
		$json = $this->parseJsonString($json);

		if (!isset($json->method))
		{
			$message = "Each jsonRpc 2.0 request contains method member.";
			throw new ParseException($message);
		}
		if (!property_exists($json, "id"))
		{
			$message = "Each jsonRpc 2.0 request contains id member.";
			throw new ParseException($message);
		}

		try
		{
			$request = new Request($this->validator);
			$request->setId($json->id);
			$request->setMethod($json->method);

			if (isset($json->params))
			{
				$request->setParams($json->params);
			}
		}
		catch (\RuntimeException $e)
		{
			$message = "Failed to build the request.";
			throw new ParseException($message, ParseException::INVALID, $e);
		}
		return $request;
	}

	/**
	 * Parses data into rpc notification
	 *
	 * @param string|\stdClass $json Rpc representation
	 * @return Notification
	 */
	public function parseJsonRpc2Notification($json)
	{
		$json = $this->parseJsonString($json);

		if (!isset($json->method))
		{
			$message = "Each jsonRpc 2.0 notification contains method member.";
			throw new ParseException($message);
		}

		try
		{
			$notification = new Notification($this->validator);
			$notification->setMethod($json->method);

			if (isset($json->params))
			{
				$notification->setParams($json->params);
			}
		}
		catch (\RuntimeException $e)
		{
			$message = "Failed to build the request.";
			throw new ParseException($message, ParseException::INVALID, $e);
		}
		return $notification;
	}

	/**
	 * Parses data into rpc response
	 *
	 * @param string|\stdClass $json Rpc representation
	 * @return Response
	 */
	public function parseJsonRpc2Response($json)
	{
		$json = $this->parseJsonString($json);

		if (!property_exists($json, "id"))
		{
			$message = "Each jsonRpc 2.0 response contains id member.";
			throw new ParseException($message);
		}

		if (!isset($json->result) && !isset($json->error))
		{
			$message = "Each jsonRpc 2.0 response contains result or error member.";
			throw new ParseException($message);
		}

		if (isset($json->result) && isset($json->error))
		{
			$message = "JsonRpc 2.0 response must not contain both result and error member.";
			throw new ParseException($message);
		}

		try
		{
			$response = new Response($this->validator);
			$response->setId($json->id);

			if (isset($json->result))
			{
				$response->setResult($json->result);
			}
			else
			{
				$errorObject = $json->error;
				$this->assertIsErrorObject($errorObject);

				$error = new Error($this->validator);
				$error->setCode($errorObject->code);
				$error->setMessage($errorObject->message);

				if (isset($errorObject->data))
				{
					$error->setData($errorObject->data);
				}

				$response->setError($error);
			}
		}
		catch (\RuntimeException $e)
		{
			$message = "Failed to build the request.";
			throw new ParseException($message, ParseException::INVALID, $e);
		}

		return $response;
	}

	/**
	 * Parses string into json object
	 *
	 * @param string $json
	 * @return \stdClass
	 */
	private function parseJsonString($json)
	{
		if ($json instanceof \stdClass)
		{
			return $json;
		}
		elseif (!is_string($json))
		{
			$message = "Argument must be either json object or a string.";
			throw new InvalidArgumentException($message);
		}

		$object = json_decode($json);
		if (is_null($object) && is_int(json_last_error()))
		{
			// @todo Be more verbose
			$message = "Decoding json string failed.";
			throw new InvalidArgumentException($message);
		}
		return $object;
	}

	/**
	 * Checks the json is json-rpc 2.0 formally
	 * 
	 * @param \stdClass $json
	 */
	private function assertJsonRpc($json)
	{
		$isJsonRpc = isset($json->jsonrpc) && $json->jsonrpc === "2.0";
		if (!$isJsonRpc)
		{
			$message = "Each jsonRpc 2.0 message contains jsonrpc member with value \"2.0\".";
			throw new ParseException($message, ParseException::INVALID);
		}
	}

	/**
	 * Checks the object is json-rpc 2.0 error object
	 *
	 * @param \stdClass $errorObject
	 */
	private function assertIsErrorObject($errorObject)
	{
		$isStdClass = $errorObject instanceof \stdClass;
		if (!$isStdClass)
		{
			$message = "Invalid error object, \\stdClass instance was expected.";
			throw new ParseException($message, ParseException::INVALID);
		}

		if (!isset($errorObject->code))
		{
			$message = "Failed to find a code of the error object.";
			throw new ParseException($message, ParseException::INVALID);
		}

		if (!isset($errorObject->message))
		{
			$message = "Failed to find a message of the error object.";
			throw new ParseException($message, ParseException::INVALID);
		}
	}

}
