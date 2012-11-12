<?php

/**
 * SG Logistics client API
 *
 * @copyright Copyright (c) 2012 Slevomat.cz, s.r.o.
 * @version 1.1
 * @apiVersion 1.0
 */

namespace SgLogistics\Api\Entity;

/**
 * Order.
 *
 * @category SgLogistics
 * @package  Api
 *
 * @property string $id
 * @property integer|string $created
 * @property integer $deliveryType
 * @property integer $pickupStoreId
 * @property float $shippingPrice
 * @property Address $billingAddress
 * @property Address $shippingAddress
 * @property array $items
 */
class Order extends ApiEntity
{
	/**
	 * The order is pending for handling.
	 *
	 * @var int
	 */
	const STATE_PENDING = 1;

	/**
	 * The order has been cancelled entirely.
	 *
	 * @var int
	 */
	const STATE_CANCELLED = 2;

	/**
	 * The order is now being processed.
	 *
	 * @var int
	 */
	const STATE_PROCESSED = 3;

	/**
	 * The order is being expedited.
	 *
	 * @var int
	 */
	const STATE_IN_EXPEDITION = 4;

	/**
	 * The order was handed over to an expeditor.
	 *
	 * @var int
	 */
	const STATE_EXPEDITED = 5;

	/**
	 * The order is now ready for a customer to pick it up.
	 *
	 * @var int
	 */
	const STATE_READY_FOR_PICKUP = 6;

	/**
	 * The order was handed over to a customer.
	 *
	 * @var int
	 */
	const STATE_CLOSED = 7;

	/**
	 * The entire order was returned by a customer.
	 *
	 * @var int
	 */
	const STATE_RETURNED = 8;

	/**
	 * Delivery method - personal pickup.
	 *
	 * @var int
	 */
	const DELIVERY_PERSONAL = 1;

	/**
	 * Delivery method - courier.
	 *
	 * @var int
	 */
	const DELIVERY_POST = 2;

	/**
	 * Entity data.
	 *
	 * @var array
	 */
	protected $data = array(
		'id' => null,
		'created' => null,
		'deliveryType' => null,
		'pickupStoreId' => null,
		'billingAddress' => null,
		'shippingAddress' => null,
		'shippingPrice' => 0,
		'items' => array()
	);

	/**
	 * Exports the entity into an array.
	 *
	 * @return array
	 */
	public function export()
	{
		$data = $this->data;
		$data['items'] = array_map(function($i) { return $i->export(); }, $data['items']);

		return array_map(function($value) {
			return $value instanceof ApiEntity ? $value->export() : $value;
		}, $data);
	}

	/**
	 * Adds an item to the order.
	 *
	 * @param OrderItem $item
	 * @return Order
	 *
	 * @throws \InvalidArgumentException If the given order item is already in the order.
	 */
	public function addItem(OrderItem $item)
	{
		if (in_array($item, $this->data['items'], true)) {
			throw new \InvalidArgumentException('The given order item is already in the order.');
		}

		$this->data['items'][] = $item;

		return $this;
	}

	/**
	 * Sets an attribute value.
	 *
	 * @param string $name Attribute name
	 * @param mixed $value Attribute value
	 *
	 * @throws \InvalidArgumentException If the "shippingAddress" attribute was not an instance of \SgLogistics\Api\Entity\Address
	 * @throws \InvalidArgumentException If the "billingAddress" attribute was not an instance of \SgLogistics\Api\Entity\Address
	 */
	public function __set($name, $value)
	{
		if (('billingAddress' === $name || 'shippingAddress' === $name) && !$value instanceof Address) {
			throw new \InvalidArgumentException(sprintf('The value of the "%s" attribute has to be an instance of \SgLogistics\Api\Entity\Address.', $name));
		}

		parent::__set($name, $value);
	}
}
