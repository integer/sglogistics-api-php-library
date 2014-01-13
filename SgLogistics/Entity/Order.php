<?php

/**
 * SG Logistics client API
 *
 * @copyright Copyright (c) 2012-2013 Slevomat.cz, s.r.o.
 * @version 1.19
 * @apiVersion 1.2
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
 * @property array $metadata
 * @property boolean $cashOnDelivery
 * @property integer|null $expectedDeliveryDate
 * @property string $attachment
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
	 * It was not possible to deliver the order.
	 *
	 * @var int
	 */
	const STATE_DELIVERY_FAILED = 9;

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
	 * Expedition state - unknown.
	 *
	 * @var integer
	 */
	const EXPEDITION_STATE_UNKNOWN = -1;

	/**
	 * Expedition state - the package is in courier's input depot.
	 *
	 * @var integer
	 */
	const EXPEDITION_STATE_INPUT_DEPOT = 1;

	/**
	 * Expedition state - the package is in courier's output depot.
	 *
	 * @var integer
	 */
	const EXPEDITION_STATE_OUTPUT_DEPOT = 2;

	/**
	 * Expedition state - the package is being delivered by the courier.
	 *
	 * @var integer
	 */
	const EXPEDITION_STATE_BEING_DELIVERED = 3;

	/**
	 * Expedition state - the package was not delivered.
	 *
	 * @var integer
	 */
	const EXPEDITION_STATE_DELIVERY_UNSUCCESSFUL = 4;

	/**
	 * Expedition state - the package was delivered.
	 *
	 * @var integer
	 */
	const EXPEDITION_STATE_DELIVERED = 5;

	/**
	 * Expedition state - the package was not delivered but an additional attempt will be made.
	 *
	 * @var integer
	 */
	const EXPEDITION_STATE_RETRYING_DELIVERY = 6;

	/**
	 * Expedition state - the package was returned to the sender.
	 *
	 * @var integer
	 */
	const EXPEDITION_STATE_RETURNED_TO_SENDER = 7;

	/**
	 * Return state - return received.
	 *
	 * @var integer
	 */
	const RETURN_STATE_RECEIVED = 1;

	/**
	 * Return state - return is being processed.
	 *
	 * @var integer
	 */
	const RETURN_STATE_IN_PROGRESS = 2;

	/**
	 * Return state - return has been approved.
	 *
	 * @var integer
	 */
	const RETURN_STATE_APPROVED = 3;

	/**
	 * Return state - return has been declined.
	 *
	 * @var integer
	 */
	const RETURN_STATE_DECLINED = 4;

	/**
	 * Return state - return is being transported between seller's warehouses.
	 *
	 * @var integer
	 */
	const RETURN_STATE_TRANSPORTED = 7;

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
		'items' => array(),
		'metadata' => array(),
		'cashOnDelivery' => false,
		'expectedDeliveryDate' => null,
		'attachment' => null,
	);

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
	 * @throws \InvalidArgumentException If the file provided as the "attachment" attribute value does not exist
	 * @throws \InvalidArgumentException If the "shippingAddress" attribute was not an instance of \SgLogistics\Api\Entity\Address
	 * @throws \InvalidArgumentException If the "billingAddress" attribute was not an instance of \SgLogistics\Api\Entity\Address
	 */
	public function __set($name, $value)
	{
		if ('attachment' === $name && !is_file($value)) {
			throw new \InvalidArgumentException(sprintf('File "%s" does not exist.', $value));
		}

		if ('billingAddress' === $name || 'shippingAddress' === $name) {
			if (!$value instanceof Address) {
				throw new \InvalidArgumentException(sprintf('The value of the "%s" attribute has to be an instance of \SgLogistics\Api\Entity\Address.', $name));
			}
		} elseif ('metadata' === $name) {
			if (!is_array($value)) {
				throw new \InvalidArgumentException(sprintf('The value of the "%s" attribute has to be an array.', $name));
			}

			foreach ($value as $metadataValue) {
				if (!is_scalar($metadataValue)) {
					throw new \InvalidArgumentException(sprintf('The value of the "%s" attribute has to be a single-dimensional array.', $name));
				}
			}
		}

		parent::__set($name, $value);
	}

	/**
	 * Exports the entity into an array.
	 *
	 * @return array
	 */
	public function export()
	{
		$data = parent::export();

		if (!empty($data['attachment'])) {
			$data['attachment'] = '@' . $data['attachment'];
		}

		return $data;
	}
}
