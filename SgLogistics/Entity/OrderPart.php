<?php

/**
 * SG Logistics client API
 *
 * @copyright Copyright (c) 2012 Slevomat.cz, s.r.o.
 * @version 0.9
 * @apiVersion 0.9
 */

namespace SgLogistics\Api\Entity;

/**
 * An order part.
 *
 * @category SgLogistics
 * @package  Api
 *
 * @property int $orderId
 * @property string $brand
 * @property string $code
 * @property int $amount
 */
class OrderPart extends ApiEntity
{
	/**
	 * Entity data.
	 *
	 * @var array
	 */
	protected $data = array(
		'orderId' => null,
		'brand' => null,
		'code' => null,
		'amount' => null
	);
}