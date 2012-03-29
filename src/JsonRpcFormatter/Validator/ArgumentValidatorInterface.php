<?php
namespace JsonRpcFormatter\Validator;

/**
 * Validates JSON-RPC 2.0 member values
 *
 * @author Radek Dvořák <radek.dvorak@gmail.com>
 */
interface ArgumentValidatorInterface
{
	/**
	 * Validates the id
	 *
	 * @param mixed $id Id argument to validate
	 * @return bool
	 */
	public function isValidId($id);

	/**
	 * Validates the parameters
	 *
	 * @param mixed $params Params argument to validate
	 * @return bool
	 */
	public function isValidParamsArgument($params);

	/**
	 * Validates the method
	 *
	 * @param mixed $method Method argument to validate
	 * @return bool
	 */
	public function isValidMethod($method);

	/**
	 * Validates error message
	 *
	 * @param mixed $errorMessage ErrorMessage argument to validate
	 * @return bool
	 */
	public function isValidErrorMessage($errorMessage);
	

	/**
	 * Validates error code
	 *
	 * @param mixed $errorCode ErrorCode argument to validate
	 * @return bool
	 */
	public function isValidErrorCode($errorCode);

	/**
	 * Returns an explanation of last validation failure
	 *
	 * @return string
	 */
	public function getLastErrorMessage();


}

