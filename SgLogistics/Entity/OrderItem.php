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
 * Order item.
 *
 * @category SgLogistics
 * @package  Api
 *
 * @property string $id
 * @property string $brand
 * @property string $code
 * @property integer $amount
 * @property float $unitPrice
 * @property string $description
 */
class OrderItem extends ApiEntity
{
	/**
	 * Order item state - default.
	 *
	 * @var int
	 */
	const STATE_DEFAULT = 1;

	/**
	 * Order item state - canceled.
	 *
	 * @var int
	 */
	const STATE_CANCELLED = 2;

	/**
	 * Order item state - returned.
	 *
	 * @var int
	 */
	const STATE_REPAYMENT = 3;

	/**
	 * Order item state - warranty claim.
	 *
	 * @var int
	 */
	const STATE_COMPLAINT = 4;

	/**
	 * Entity data.
	 *
	 * @var array
	 */
	protected $data = array(
		'id' => null,
		'brand' => null,
		'code' => null,
		'amount' => null,
		'unitPrice' => null,
		'description' => null
	);
}
