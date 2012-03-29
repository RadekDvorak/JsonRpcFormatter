<?php
namespace JsonRpcFormatter;

/**
 *
 * @author Radek Dvořák <radek.dvorak@gmail.com>
 */
interface JsonRpcParserInterface
{
	/**
	 * Returns the last error message. If there is none, return empty string.
	 *
	 * @return string
	 */
	public function getLastError();

	/**
	 * Tests the object is jsonrpc object
	 *
	 * @return bool
	 */
	public function isJsonRpc();

	/**
	 * Tests the jsonrpc object has the correct version
	 *
	 * @return bool
	 */
	public function hasCorrectVersion();
}

