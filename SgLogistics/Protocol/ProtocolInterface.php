<?php

/**
 * SG Logistics client API
 *
 * @copyright Copyright (c) 2012-2013 Slevomat.cz, s.r.o.
 * @version 1.7.2
 * @apiVersion 1.0
 */

namespace SgLogistics\Api\Protocol;

/**
 * Communication protocols interface.
 *
 * @category   SgLogistics
 * @package    Api
 * @subpackage Protocol
 */
interface ProtocolInterface
{
	/**
	 * Sends a request and returns the result.
	 *
	 * @param string $method Method name.
	 * @param array $arguments Array of arguments
	 *
	 * @return \SgLogistics\Api\Response Method call result.
	 */
	public function request($method, array $arguments = array());
}
