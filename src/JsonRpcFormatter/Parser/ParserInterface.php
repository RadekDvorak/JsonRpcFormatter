<?php

namespace JsonRpcFormatter\Parser;

/**
 * JsonRpc 2.0 parser interface
 *
 * @see http://jsonrpc.org/specification
 * @author Radek Dvořák <radek.dvorak@gmail.com>
 */
interface ParserInterface
{	/**
	 * Parses data into rpc message
	 *
	 * @param string|\stdClass $json Rpc representation
	 * @return \JsonRpcFormatter\Message
	 */
	public function parseJsonRpc2Message($json);

	/**
	 * Parses data into rpc request
	 *
	 * @param string|\stdClass $json Rpc representation
	 * @return Request
	 */
	public function parseJsonRpc2Request($json);

	/**
	 * Parses data into rpc notification
	 *
	 * @param string|\stdClass $json Rpc representation
	 * @return Notification
	 */
	public function parseJsonRpc2Notification($json);

	/**
	 * Parses data into rpc response
	 *
	 * @param string|\stdClass $json Rpc representation
	 * @return Response
	 */
	public function parseJsonRpc2Response($json);
}
