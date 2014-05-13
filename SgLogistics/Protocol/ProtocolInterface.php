<?php

/**
 * SG Logistics client API
 *
 * @copyright Copyright (c) 2012-2013 Slevomat.cz, s.r.o.
 * @version 1.26
 * @apiVersion 1.2
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

	/**
	 * Sets the user agent header value for HTTP requests.
	 *
	 * @param string $userAgent Header value
	 * @return \SgLogistics\Api\Protocol\ProtocolInterface
	 */
	public function setUserAgent($userAgent);
}
