<?php

/**
 * SG Logistics client API
 *
 * @copyright Copyright (c) 2012-2013 Slevomat.cz, s.r.o.
 * @version 1.18
 * @apiVersion 1.2
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
 * @property int $returnState
 */
class OrderPart extends ApiEntity
{
	/**
	 * Returned goods is in mint state.
	 *
	 * @var int
	 */
	const RETURN_STATE_MINT = 1;

	/**
	 * Returned goods is in good state.
	 *
	 * @var int
	 */
	const RETURN_STATE_GOOD = 2;

	/**
	 * Returned goods is garbage.
	 *
	 * @var int
	 */
	const RETURN_STATE_GARBAGE = 3;

	/**
	 * Entity data.
	 *
	 * @var array
	 */
	protected $data = array(
		'orderId' => null,
		'brand' => null,
		'code' => null,
		'amount' => null,
		'returnState' => null
	);
}
