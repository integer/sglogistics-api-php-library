<?php

/**
 * SG Logistics client API
 *
 * @copyright Copyright (c) 2012-2013 Slevomat.cz, s.r.o.
 * @version 1.26
 * @apiVersion 1.2
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
 * @property string $customProductId
 * @property float $vatRate
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
	 * Order item state - the order was not picked up.
	 *
	 * @var int
	 */
	const STATE_UNANSWERED = 5;

	/**
	 * Order item state - broken by a courier.
	 *
	 * @var int
	 */
	const STATE_BROKEN_BY_COURIER = 6;

	/**
	 * Order item state - processed item.
	 *
	 * @var int
	 */
	const STATE_PROCESSED = 7;

	/**
	 * Order item state - undelivered to a customer
	 *
	 * @var int
	 */
	const STATE_UNDELIVERED = 8;

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
	 * Order item type - gift.
	 *
	 * @var int
	 */
	const TYPE_GIFT = 4;

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
		'type' => self::TYPE_NORMAL,
		'customProductId' => null,
		'vatRate' => null,
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
		if ('type' === $name && !($value & (self::TYPE_CONSIGNMENT | self::TYPE_NORMAL))) {
			throw new \InvalidArgumentException(sprintf('The value of the "%s" attribute has to be SgLogistics\\Api\\Entity\\OrderItem::TYPE_NORMAL or SgLogistics\\Api\\Entity\\OrderItem::TYPE_CONSIGNMENT.', $name));
		}

		parent::__set($name, $value);
	}

	/**
	 * Mark the item as gift.
	 *
	 * @return OrderItem Provides a fluent interface.
	 */
	public function markAsGift()
	{
		$this->type |= self::TYPE_GIFT;

		return $this;
	}
}
