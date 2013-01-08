<?php

/**
 * SG Logistics client API
 *
 * @copyright Copyright (c) 2012-2013 Slevomat.cz, s.r.o.
 * @version 1.5
 * @apiVersion 1.0
 */

namespace SgLogistics\Api;

/**
 * API client.
 *
 * @category SgLogistics
 * @package  Api
 */
class Client
{
	/**
	 * Document format will be PDF.
	 *
	 * @var string
	 */
	const DOCUMENT_FORMAT_PDF = 'pdf';

	/**
	 * Document format will be HTML.
	 *
	 * @var string
	 */
	const DOCUMENT_FORMAT_HTML = 'html';

	/**
	 * The communication protcol.
	 *
	 * @var Protocol\ProtocolInterface
	 */
	private $protocol;

	/**
	 * Client name.
	 *
	 * @var string
	 */
	private $name;

	/**
	 * Client secret.
	 *
	 * @var string
	 */
	private $secret;

	/**
	 * Constructor.
	 *
	 * @param Protocol\ProtocolInterface $protocol The communication protcol.
	 */
	public function __construct(Protocol\ProtocolInterface $protocol)
	{
		$this->protocol = $protocol;
	}

	/**
	 * Call the given remote method.
	 *
	 * @param string $method Name of the method to be called.
	 * @param array $arguments A list of arguments to be passed to the called method.
	 *
	 * @return mixed The result returned by the called method.
	 *
	 * @throws \Exception An exception thrown during the method execution.
	 * @throws \BadMethodCallException If some other error occured.
	 */
	public function call($method, array $arguments = array())
	{
		$arguments['accessToken'] = $this->getAccessToken($method);

		$response = $this->protocol->request((string) $method, $arguments);
		if ($response::STATUS_SUCCESS !== $response->getStatus()) {
			throw $response->getException();
		}

		return $response->getResult();
	}

	/**
	 * Set the given credentials.
	 *
	 * @param string $name Client name.
	 * @param string $secret Client secret.
	 *
	 * @return Client Provides a fluent interface.
	 */
	public function setCredentials($name, $secret)
	{
		$this->name = $name;
		$this->secret = $secret;

		return $this;
	}

	/**
	 * Verify if the set credentials are correct.
	 *
	 * @return bool True if the set credentials are correct, false otherwise.
	 */
	public function verify()
	{
		return (bool) $this->call('verify');
	}

	/**
	 * Get the access token.
	 *
	 * @param string $method Name of the method to be called.
	 *
	 * @return string The access token.
	 *
	 * @throws \BadMethodCallException If no credentials are set and therefore there is no access token.
	 */
	protected function getAccessToken($method)
	{
		if (empty($this->name) || empty($this->secret)) {
			throw new \BadMethodCallException('You have to set your client name and secret in order to use the API.');
		}

		$now = time();
		$name = strtolower($this->name);
		$tokenData = strtolower($method) . '|' . $name . '|' . strtolower($this->secret) . '|' . $now;

		return sha1($tokenData) . '.' .  $name . '.' . $now;
	}

	/**
	 * Make sure that the given product will exist within the SGL system.
	 * If there is no such product then the given product is added into the system.
	 *
	 * @param Entity\Product $product A product that should exists within the system.
	 *
	 * @return bool True if the operation was successful, false otherwise.
	 *
	 * @throws Exception\MissingValue If a value of some required property is missing.
	 * @throws Exception\InvalidValue If a value of some property is not valid one.
	 */
	public function addProduct(Entity\Product $product)
	{
		return (bool) $this->call(__FUNCTION__, $product->export());
	}

	/**
	 * Add the given customer order to the SGL system.
	 *
	 * @param Entity\Order $order The customer order to be added.
	 *
	 * @return bool True if the operation was successful, false otherwise.
	 *
	 * @throws Exception\MissingValue If a value of some required property is missing.
	 * @throws Exception\InvalidValue If a value of some property is not valid one.
	 */
	public function addOrder(Entity\Order $order)
	{
		return (bool) $this->call(__FUNCTION__, $order->export());
	}

	/**
	 * Cancel the given order entirely.
	 *
	 * @param string $id The ID of order to be cancelled entirely.
	 *
	 * @return int Cancel ID
	 *
	 * @throws \InvalidArgumentException If there is no such order.
	 */
	public function cancelOrder($id)
	{
		return (int) $this->call(__FUNCTION__, array('id' => $id));
	}

	/**
	 * Cancel the given part of an order.
	 *
	 * @param Entity\OrderPart $part The order part to be cancelled.
	 * @param boolean $returnShippingPrice Should the price paid for S/H be returned as well?
	 *
	 * @return int Cancel ID
	 *
	 * @throws \InvalidArgumentException If there is no such order.
	 * @throws Exception\InvalidValue If the given order does not contain the given product or the amount to cancel
	 *                                is higher than amount contained within the order.
	 */
	public function cancelOrderPart(Entity\OrderPart $part, $returnShippingPrice = false)
	{
		return (int) $this->call(__FUNCTION__, $part->export() + array('returnShippingPrice' => $returnShippingPrice));
	}

	/**
	 * Get order(s) state(s).
	 *
	 * The returned array is in the following format:
	 * <code>
	 * [
	 *		order_id => [
	 *			'state' => order_state_code,
	 *			'items' => [
	 * 				[
	 *					'brand' => product_brand,
	 *					'code' => product_code,
	 *					'amount' => amount_of_pieces,
	 *					'state' => item_state_code
	 * 				],
	 * 				...
	 *			]
	 *		],
	 * 		...
	 * ]
	 * </code>
	 *
	 * @param array $id Order IDs. Can be a single ID or a list of IDs in which case the result will an array
	 *                          where its keys are IDs of corresponding orders.
	 *
	 * @return array The result in a format described above.
	 *
	 * @throws Exception\InvalidValue If there is no such order.
	 */
	public function getOrderState($id)
	{
		return $this->call(__FUNCTION__, array('id' => $id));
	}

	/**
	 * Get order(s) log(s).
	 *
	 * The returned array is in the following format:
	 * <code>
	 * [
	 *		order_id => [
	 *			[
	 *				[
	 *					'date' => date_and_time_of_an_event,
	 *					'state' => order_state_code,
	 *				],
	 *				...
	 *			]
	 *		],
	 *		...
	 * ]
	 * </code>
	 *
	 * @param array $id Order IDs. Can be a single ID or a list of IDs in which case the result will an array
	 *                          where its keys are IDs of corresponding orders.
	 *
	 * @return array The result in a format described above.
	 *
	 * @throws Exception\InvalidValue If there is no such order.
	 */
	public function getOrderStateLog($id)
	{
		return $this->call(__FUNCTION__, array('id' => $id));
	}

	/**
	 * Close the given order (the order has been picked up by a customer).
	 * The given order has to be meant for a personal pick-up and also has to be ready for it.
	 *
	 * @param int $id Order ID.
	 *
	 * @return true
	 *
	 * @throws Exception\InvalidValue If there is no such order or it is not ready to be picked up.
	 */
	public function closeOrder($id)
	{
		return $this->call(__FUNCTION__, array('id' => $id));
	}

	/**
	 * Get a list of returns (cancels, repayments and complaints) since the given date.
	 *
	 * The returned array is in the following format:
	 * <code>
	 * [
	 *		[
	 * 			'id' => return_id,
	 * 			'orderId => order_id,
	 * 			'type' =>  cancel/replayment/complaint,
	 * 			'date' => date_and_time_when_the_item_was_returned,
	 * 			'items' => [
	 *				[
	 *					'brand' => product_brand,
	 *					'code' => product_code,
	 *					'amount' => amount_of_pieces
	 * 				],
	 * 				...
	 * 			]
	 *		]
	 *		...
	 * ]
	 * </code>
	 *
	 * @param string|int $since Date and time since which the list will be returned.
	 *
	 * @return array The result in a format described above.
	 */
	public function getReturns($since)
	{
		return $this->call(__FUNCTION__, array('since' => $since));
	}

	/**
	 * Returns information about a single cancel/repayment/cimplaint.
	 *
	 * The returned array is in the following format:
	 * <code>
	 * [
	 * 		'id' => return_id,
	 * 		'orderId => order_id,
	 * 		'type' =>  cancel/replayment/complaint,
	 * 		'date' => date_and_time_when_the_item_was_returned,
	 * 		'items' => [
	 *			[
	 *				'brand' => product_brand,
	 *				'code' => product_code,
	 *				'amount' => amount_of_pieces
	 * 			],
	 * 			...
	 * 		]
	 * ]
	 * </code>
	 *
	 * @param int $id Return ID
	 * @return array
	 */
	public function getReturn($id)
	{
		return $this->call(__FUNCTION__, array('id' => $id));
	}

	/**
	 * Get an invoice for the given order.
	 *
	 * @param int $id ID of an order for which to get an invoice.
	 * @param string $format Invoice format.
	 *
	 * @return string The requested invoice as a base64 encoded data stream.
	 *
	 * @throws Exception\InvalidValue If there is no such order or an unknown document format is requested.
	 */
	public function getInvoice($id, $format = self::DOCUMENT_FORMAT_PDF)
	{
		return $this->call(__FUNCTION__, array('id' => $id, 'format' => $format));
	}

	/**
	 * Get a payment confirmation document for the given order.
	 *
	 * @param int $id ID of an order for which to get a payment confirmation document.
	 * @param string $format Document format.
	 *
	 * @return string The requested payment confirmation document as a base64 encoded data stream.
	 *
	 * @throws Exception\InvalidValue If there is no such order.
	 */
	public function getPaymentConfirmation($id, $format = self::DOCUMENT_FORMAT_PDF)
	{
		return $this->call(__FUNCTION__, array('id' => $id, 'format' => $format));
	}

	/**
	 * Get a return receipt document for the given order.
	 *
	 * @param int $id ID of an order for which to get a return receipt document.
	 * @param string $format Document format.
	 *
	 * @return string The requested return receipt document as a base64 encoded data stream.
	 *
	 * @throws Exception\InvalidValue If there is no such order.
	 */
	public function getReturnReceipt($id, $format = self::DOCUMENT_FORMAT_PDF)
	{
		return $this->call(__FUNCTION__, array('id' => $id, 'format' => $format));
	}

	/**
	 * Updates the given order shipping address.
	 *
	 * @param int $id ID of an order for which to update the shipping address.
	 * @param Entity\Address $address New shipping address.
	 *
	 * @return boolean
	 *
	 * @throws Exception\InvalidValue If there is no such order.
	 * @throws Exception\InvalidValue If the given shipping address is not valid
	 */
	public function updateShippingAddress($id, Entity\Address $address)
	{
		return (bool) $this->call(__FUNCTION__, array('id' => $id, 'address' => $address->export()));
	}

	/**
	 * Make a soft reservation of the given product.
	 *
	 * @param string $brand Product brand.
	 * @param string $code Product code.
	 * @param int $amount Amount of pieces of the given product.
	 *
	 * @return int Amount of remaining pieces of the given product.
	 *
	 * @throws Exception\InvalidValue If there is no such product.
	 * @throws Exception\SharedLimitDoesNotExist If there is no shared limit for the given product.
	 * @throws Exception\SharedLimitReached If the requested number of pieces is not avialable.
	 */
	public function makeSoftProductReservation($brand, $code, $amount = 1)
	{
		return (int) $this->call(
			__FUNCTION__, array('brand' => (string) $brand, 'code' => (string) $code, 'amount' => (int) $amount)
		);
	}

	/**
	 * Make a hard reservation of the given product.
	 *
	 * @param string $brand Product brand.
	 * @param string $code Product code.
	 * @param int $amount Amount of pieces of the given product.
	 *
	 * @return int Amount of remaining pieces of the given product.
	 *
	 * @throws Exception\InvalidValue If there is no such product.
	 * @throws Exception\SharedLimitDoesNotExist If there is no shared limit for the given product.
	 * @throws Exception\SharedLimitReached If the requested number of pieces is not avialable.
	 */
	public function makeHardProductReservation($brand, $code, $amount = 1)
	{
		return (int) $this->call(
			__FUNCTION__, array('brand' => (string) $brand, 'code' => (string) $code, 'amount' => (int) $amount)
		);
	}

	/**
	 * Try to harden the given soft reservations.
	 * If any of the given reservations do not exist corresponding hard ones are tried to be created.
	 *
	 * @param array $softReservations List of soft reservations which should be made hard.
	 *
	 * @return array List of successfully hardened reservations.
	 */
	public function makeSoftReservationsHard(array $softReservations)
	{
		return (array) $this->call(__FUNCTION__, array('softReservations' => $softReservations));
	}

	/**
	 * Try to soften the given hard reservations.
	 * If any of the given reservations do not exist corresponding soft ones are tried to be created.
	 *
	 * @param array $hardReservations List of hard reservations which should be made soft.
	 *
	 * @return array List of successfully softened reservations.
	 */
	public function makeHardReservationsSoft(array $hardReservations)
	{
		return (array) $this->call(__FUNCTION__, array('hardReservations' => $hardReservations));
	}

	/**
	 * Try to prolong the given soft reservations.
	 * If any of the given reservations do not exist corresponding ones are tried to be created.
	 *
	 * @param array $softReservations List of soft reservations which should be made hard.
	 *
	 * @return array List of successfully prolonged reservations.
	 */
	public function prolongSoftReservations(array $softReservations)
	{
		return (array) $this->call(__FUNCTION__, array('softReservations' => $softReservations));
	}

	/**
	 * Unmake a soft reservation of the given product.
	 *
	 * @param string $brand Product brand.
	 * @param string $code Product code.
	 * @param int $amount Amount of pieces of the given product.
	 *
	 * @return int Amount of remaining pieces of the given product.
	 *
	 * @throws Exception\InvalidValue If there is no such product.
	 */
	public function unmakeSoftProductReservation($brand, $code, $amount = 1)
	{
		return (int) $this->call(
			__FUNCTION__, array('brand' => (string) $brand, 'code' => (string) $code, 'amount' => (int) $amount)
		);
	}

	/**
	 * Unmake the given soft product reservations.
	 *
	 * @param array $softReservations List of soft reservations which should be unmade.
	 *
	 * @return true
	 */
	public function unmakeSoftProductReservations(array $softReservations)
	{
		return (array) $this->call(__FUNCTION__, array('softReservations' => $softReservations));
	}

	/**
	 * Unmake a hard reservation of the given product.
	 *
	 * @param string $brand Product brand.
	 * @param string $code Product code.
	 * @param int $amount Amount of pieces of the given product.
	 *
	 * @return int Amount of remaining pieces of the given product.
	 *
	 * @throws Exception\InvalidValue If there is no such product.
	 */
	public function unmakeHardProductReservation($brand, $code, $amount = 1)
	{
		return (int) $this->call(
			__FUNCTION__, array('brand' => (string) $brand, 'code' => (string) $code, 'amount' => (int) $amount)
		);
	}

	/**
	 * Unmake the given hard product reservations.
	 *
	 * @param array $hardReservations List of hard reservations which should be unmade.
	 *
	 * @return true
	 */
	public function unmakeHardProductReservations(array $hardReservations)
	{
		return (array) $this->call(__FUNCTION__, array('hardReservations' => $hardReservations));
	}

	/**
	 * Get an amount of remaining pieces of the given product.
	 *
	 * @param string $brand Product brand.
	 * @param string $code Product code.
	 *
	 * @return int Amount of remaining pieces of the given product.
	 *
	 * @throws Exception\InvalidValue If there is no such product.
	 */
	public function getRemainingAmount($brand, $code)
	{
		return (int) $this->call(__FUNCTION__, array('brand' => (string) $brand, 'code' => (string) $code));
	}

	/**
	 * Get an amount of remaining pieces of multiple products at once.
	 *
	 * @param array $products Product definition
	 *
	 * @return array Amount of remaining pieces of given products.
	 */
	public function getRemainingAmounts(array $products)
	{
		return $this->call(__FUNCTION__, array('products' => $products));
	}

	/**
	 * Get the shared limit for the given product.
	 *
	 * @param string $brand Product brand.
	 * @param string $code Product code.
	 *
	 * @return int The shared limit for the given product or -1 if there is no limit.
	 *
	 * @throws Exception\InvalidValue If there is no such product.
	 */
	public function getSharedLimit($brand, $code)
	{
		return (int) $this->call(__FUNCTION__, array('brand' => (string) $brand, 'code' => (string) $code));
	}

	/**
	 * Get the number of reserved pieces of the given product.
	 *
	 * @param string $brand Product brand.
	 * @param string $code Product code.
	 *
	 * @return int The number of reserved pieces of the given product.
	 *
	 * @throws Exception\InvalidValue If there is no such product.
	 */
	public function getReservedAmount($brand, $code)
	{
		return (int) $this->call(__FUNCTION__, array('brand' => (string) $brand, 'code' => (string) $code));
	}

	/**
	 * Get the avaiable amount of the given product in all client's warehouses.
	 *
	 * The returned array is in the following format:
	 * <code>
	 * [
	 *		[
	 * 			'warehouse' => [
	 * 				'id' => warehouse_id,
	 * 				'name' => warehouse_name
	 * 			],
	 * 			'amount' => available_amount
	 *		]
	 *		...
	 * ]
	 * </code>
	 *
	 * @param string $brand Product brand.
	 * @param string $code Product code.
	 *
	 * @return array The available amount in all client's warehouses
	 *
	 * @throws Exception\InvalidValue If there is no such product.
	 */
	public function getInventoryAmount($brand, $code)
	{
		return $this->call(__FUNCTION__, array('brand' => (string) $brand, 'code' => (string) $code));
	}
}
