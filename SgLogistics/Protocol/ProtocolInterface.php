<?php

/**
 * SG Logistics client API
 *
 * @copyright Copyright (c) 2012 Slevomat.cz, s.r.o.
 * @version 0.9
 * @apiVersion 0.9
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