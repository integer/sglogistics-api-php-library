<?php

/**
 * SG Logistics client API
 *
 * @copyright Copyright (c) 2012-2013 Slevomat.cz, s.r.o.
 * @version 1.26
 * @apiVersion 1.2
 */

namespace SgLogistics\Api\Exception;

/**
 * Exception thrown when there is an error with an API parameter value.
 *
 * @category SgLogistics
 * @package  Api
 */
abstract class Value extends Response
{
	/**
	 * Parameter name.
	 *
	 * @var string
	 */
	private $parameterName;

	/**
	 * Parameter value.
	 *
	 * @var mixed
	 */
	private $value;

	/**
	 * Creates the exception.
	 *
	 * @param string $parameterName Parameter name
	 * @param mixed $value Parameter value
	 */
	public function __construct($message, $parameterName, $value)
	{
		parent::__construct($message);

		$this->parameterName = $parameterName;
		$this->value = $value;
	}

	/**
	 * Returns the parameter name.
	 *
	 * @return string
	 */
	public function getParameterName()
	{
		return $this->parameterName;
	}

	/**
	 * Returns the parameter value.
	 *
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}
}
