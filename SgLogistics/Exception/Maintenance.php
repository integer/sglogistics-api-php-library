<?php

/**
 * SG Logistics client API
 *
 * @copyright Copyright (c) 2012-2013 Slevomat.cz, s.r.o.
 * @version 1.22
 * @apiVersion 1.2
 */

namespace SgLogistics\Api\Exception;

/**
 * Exception thrown when the SGL system is down for maintenance.
 *
 * @category SgLogistics
 * @package  Api
 */
class Maintenance extends Response
{
	/**
	 * Creates the exception.
	 */
	public function __construct()
	{
		parent::__construct('The SGL system is down for maintenance.');
	}
}
