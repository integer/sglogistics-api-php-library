<?php

/**
 * SG Logistics client API
 *
 * @copyright Copyright (c) 2012 Slevomat.cz, s.r.o.
 * @version 0.9
 * @apiVersion 0.9
 */

namespace SgLogistics\Api\Exception;

/**
 * Exception thrown when the SGL API reports that an invalid parameter value was provided.
 *
 * @category SgLogistics
 * @package  Api
 */
class InvalidValue extends Value
{
	/**
	 * Creates the exception.
	 *
	 * @param string $message Exception message
	 * @param string $parameterName Parameter name
	 * @param mixed $value Parameter value
	 */
	public function __construct($message, $parameterName, $value)
	{
		parent::__construct($message, $parameterName, $value);
	}
}