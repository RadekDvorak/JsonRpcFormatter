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
	 * The code is considered unparsable as any other json-rpc 2.0 object
	 */
	const UNPARSABLE = 1;
}

