<?php

/**
 * SG Logistics client API
 *
 * @copyright Copyright (c) 2012 Slevomat.cz, s.r.o.
 * @version 1.2
 * @apiVersion 1.0
 */

namespace SgLogistics\Api\Exception;

/**
 * Exception thrown when the SGL API reports that a required parameter value is missing.
 *
 * @category SgLogistics
 * @package  Api
 */
class MissingValue extends Value
{
	/**
	 * Creates the exception.
	 *
	 * @param string $parameterName Parameter name
	 */
	public function __construct($parameterName)
	{
		parent::__construct(sprintf('Parameter "%s" is required.', $parameterName), $parameterName, null);
	}
}