<?php

/**
 * SG Logistics client API
 *
 * @copyright Copyright (c) 2012 Slevomat.cz, s.r.o.
 * @version 1.3
 * @apiVersion 1.0
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
 * @property integer $type
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
	 * Order item type - normal.
	 *
	 * @var int
	 */
	const TYPE_NORMAL = 1;

	/**
	 * Order item type - consignment.
	 *
	 * @var int
	 */
	const TYPE_CONSIGNMENT = 2;

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
		'description' => null,
		'type' => self::TYPE_NORMAL
	);

	/**
	 * Sets an attribute value.
	 *
	 * @param string $name Attribute name
	 * @param mixed $value Attribute value
	 *
	 * @throws \InvalidArgumentException If the "type" attribute was not self::TYPE_NORMAL nor self::TYPE_CONSIGNMENT
	 */
	public function __set($name, $value)
	{
		if ('type' === $name && self::TYPE_CONSIGNMENT !== $value && self::TYPE_NORMAL !== $value) {
			throw new \InvalidArgumentException(sprintf('The value of the "%s" attribute has to be SgLogistics\\Api\\Entity\\OrderItem::TYPE_NORMAL or SgLogistics\\Api\\Entity\\OrderItem::TYPE_CONSIGNMENT.', $name));
		}

		parent::__set($name, $value);
	}
}
