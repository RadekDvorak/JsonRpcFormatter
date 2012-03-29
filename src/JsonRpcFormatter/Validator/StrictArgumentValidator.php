<?php

namespace JsonRpcFormatter\Validator;

/**
 * Common validating class
 *
 * @author Radek Dvořák <radek.dvorak@gmail.com>
 */
class StrictArgumentValidator extends ArgumentValidator
{
	public function isValidId($id)
	{
		$isPermitted = is_int($id) || is_string($id);
		if (!$isPermitted)
		{
			$message = sprintf("Id is expected to be an integer or a string in strict mode, got '%s' instead.", gettype($id));
			$this->setLastErrorMessage($message);
			return false;
		}

		return parent::isValidId($id);
	}
}

