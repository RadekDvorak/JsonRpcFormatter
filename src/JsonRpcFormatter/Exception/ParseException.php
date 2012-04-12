<?php
namespace JsonRpcFormatter\Exception;

/**
 * ParserException
 *
 * @author Radek Dvořák <radek.dvorak@gmail.com>
 */
class ParseException extends \RuntimeException
{
	/**
	 * Data is json-rpc object yet parsing caused unrecoverrable error
	 */
	const INVALID = 1;

	/**
	 * Data is NOT a json-rpc object
	 */
	const UNPARSABLE = 2;
}

