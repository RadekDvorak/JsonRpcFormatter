<?php
namespace
{
	// For php-5.3 parse level compatibility
	if (false === interface_exists('JsonSerializable', false))
	{
		interface JsonSerializable
		{

			/**
			* Specify data which should be serialized to JSON
			*
			* @return mixed
			*/
			public function jsonSerialize();
		}

	}
}
namespace JsonRpcFormatter
{

	/**
	 * Base message
	 *
	 * @see http://jsonrpc.org/specification
	 * @see http://software.dzhuvinov.com/files/jsonrpc2base/javadoc/com/thetransactioncompany/jsonrpc2/JSONRPC2Message.html
	 * @see https://gitorious.org/qjsonrpc/qjsonrpc/blobs/master/src/qjsonrpcmessage.h
	 * @author Radek Dvořák <radek.dvorak@gmail.com>
	 */
	abstract class Message implements \JsonSerializable
	{

		/**
		 * Json serialisation method since PHP-5.4
		 *
		 * @return \stdClass
		 */
		abstract public function jsonSerialize();

		/**
		 * Parses string into appropriate message object
		 *
		 * HIC SUNT LEONES
		 *
		 */
		public static function parseString($json)
		{
			$object = json_decode($json);
			if (is_null($object) && is_int(json_last_error()))
			{
				/**
				 * @todo Be more verbose
				 */
				$message = "Decoding json string failed.";
				throw new \InvalidArgumentException($message);
			}

			$decoder = self::getDefaultJsonRpcParser($object);
			if (!$decoder->isJsonRpc() || !$decoder->hasCorrectVersion())
			{
				throw new \InvalidArgumentException($decoder->getLastError());
			}

			$validator = new Validator\ArgumentValidator($strict = false);
			if (isset($object->method))
			{
				if (isset($object->id))
				{
					$return = $decoder->tryBuildRequest($validator);
				}
				else
				{
					$return = $decoder->tryBuildNotification($validator);
				}
			}
			elseif ($decoder->isResponse())
			{
				$return = $decoder->tryBuildResponse($validator);
			}
			else
			{
				$message = "Failed to parse json object as a json-rpc object.";
				throw new \InvalidArgumentException($message);
			}

			return $return;
		}

		/**
		 * Creates the default parser
		 *
		 * @param \stdClass $object
		 * @return \JsonRpcFormatter\JsonRpcParser
		 */
		public static function getDefaultJsonRpcParser(\stdClass $object)
		{
			return new JsonRpcParser($object);
		}

	}

}
