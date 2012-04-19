<?php

namespace
{
	// For php-5.3 parse level compatibility
	if (false === interface_exists('JsonSerializable', false))
	{

		/**
		 * Compatibility interface for php-5.3
		 */
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

		private $jsonEncodeOptions = 0;

		/**
		 * Json serialisation method since PHP-5.4
		 *
		 * @return \stdClass
		 */
		abstract public function jsonSerialize();

		public function __toString()
		{
			return json_encode($this->jsonSerialize());
		}

		/**
		 * Gets current json_encode method's options that are used in __toString
		 * @return type
		 */
		public function getJsonEncodeOptions()
		{
			return $this->jsonEncodeOptions;
		}

		/**
		 * Sets json_encode method's options that is used in __toString
		 *
		 * @param int $jsonEncodeOptions
		 * @return Message
		 * @see http://php.net/manual/en/function.json-encode.php
		 */
		public function setJsonEncodeOptions($jsonEncodeOptions)
		{
			$this->jsonEncodeOptions = $jsonEncodeOptions;
			return $this;
		}

	}

}
